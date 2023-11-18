<?php

namespace App\Library\Handlers;

use App\Events\LoanCreated;
use App\Exceptions\LoanInstallmentAmountException;
use App\LoanProduct;
use App\LoanRequest;
use App\LoanRequestLog;
use App\Log;
use App\Repositories\RoleRepository;
use App\Repositories\ShoppingWalletRepository;
use App\Repositories\UserAccountRepository;
use App\Repositories\UserMlipaTransactionRepository;
use App\Repositories\UserRepository;
use App\User;
use App\Utils\MlipaApi\MlipaTransactionHandler;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\DoesntHaveLoanProduct;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\HasOneOrMoreInstallments;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsNotAllowedToTakeLoan;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsNotAllowedToTakePartialLoan;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsNotAllowedToTopUp;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsNotQualifiedForLoan;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsRequestedAmountExceedingLoanLimit;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsRequestedAmountLessThanBalance;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsRequestedLoanLessThanMinimumAllowed;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\IsUserLocked;
use App\Library\Handlers\RequestLoanHandler\RequestValidators\RequestValidator;
use App\Library\Handlers\RequestLoanHandler\TransactionProcessors\TransactionProcessor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;

class RequestLoanHandler extends BaseTransactionHandler
{
    /**
     * The type of loan being taken (Shopping or Normal)
     */
    private string $loanType;

    /**
     * Is this the first loan before confirming everything up
     * (submitting documents or verifying the NIDA card)
     */
    private bool $isFirstLoan;

    /**
     * Amount requested
     */
    private $amount;

    /**
     * User requesting the loan
     */
    private User $user;

    /**
     * Type of transction
     */
    private string $transactionLogType;

    /**
     * Disbursement type, either 'agent' or 'corporate'
     */
    private string $disbursementType;

    /**
     * The laoan product being requested
     */
    private LoanProduct $loanProduct;

    private bool $shouldDisburseLoan;

    public function __construct($amount, $user, string $loanType = 'normal', $shouldDisburseLoan = true)
    {
        $this->loanType = $loanType;
        $this->user = $user;
        $this->amount = $amount;
        $this->shouldDisburseLoan = $shouldDisburseLoan;

        $this->disbursementType = (new RoleRepository)->getDisbursementTypeByUser($user);
        $this->transactionLogType = $this->disbursementType == 'agent' ?
            Log::LOG_TYPES['user_get_agent_loan'] :
            Log::LOG_TYPES['user_get_corporate_loan'];
    }

    /**
     * Handle the loan request
     */
    public function handle()
    {
        $response = $this->runTransaction();
        if ($this->loanType == 'shopping') {
            if ($response->success) {
                return $this->runShoppingTransaction();
            }
        }

        return $response;
    }

    /**
     * Run the process for shopping transaction
     */
    public function runShoppingTransaction()
    {
        $log = $this->logTransaction(
            $this->user,
            $this->amount,
            'user_fill_shopping_wallet'
        );

        DB::beginTransaction();

        $this->updateUsersWallet();

        $log->logSuccess();

        $this->setData(true, __('messages.transaction_success'));

        DB::commit();

        return $this->data();
    }

    /**
     * Add the amount loaned to user's wallet, or if the user doesn't have
     * wallet, create wallet
     */
    public function updateUsersWallet()
    {
        (new ShoppingWalletRepository)->updateWallet($this->user, $this->amount);
    }

    /**
     * Run the full database transaction, considering the conditions
     */
    public function runTransaction()
    {

        // initiate UserRepository class that is used to perform functionalities on the user model
        $userRepository = new UserRepository($this->user);

        /**
         * Log each loan request.
         */
        $loanLog = LoanRequestLog::create([
            'user_id' => $this->user->id,
            'amount' => $this->amount,
            'type' => $this->loanType,
            'status' => 'pending',
        ]);

        /******VALIDATION STEPS***** */
        try {

            /**
             * Create validator to check the loan request
             */
            $loanRequestValidator = new RequestValidator($loanLog, $userRepository);

            /**
             * Create the loan processor
             */
            $transactionProcessor = new TransactionProcessor;

            /**
             * Check if the user is locked(meaning there is another transaction on going)
             */
            $validation = $loanRequestValidator->run(new IsUserLocked);
            if ($validation->fails()) {
                return $validation->response();
            }

            /**
             * Lock the user to prevent multiple requests coming in.
             */
            $userRepository->lockUser($this->user);

            //check if loan product is available, if is available return
            $validation = $loanRequestValidator->run(new DoesntHaveLoanProduct);
            if ($validation->fails()) {
                $userRepository->unlockUser($this->user);
                return $validation->response();
            }
            $this->loanProduct = $validation->response()->data->loanProduct;

            /**
             * Check if the user is allowed to take loan, or is disabled by the admin
             */
            $validation = $loanRequestValidator->run(new IsNotAllowedToTakeLoan);
            if ($validation->fails()) {
                $userRepository->unlockUser($this->user);
                return $validation->response();
            }

            /**
             * Get number of installments for the user, for the amount requested
             *
             * and check for number of installments
             */
            $numberOfInstallments = $this->getNumberOfInstallments($this->user->id, $this->amount, $this->loanProduct);
            $validation = $loanRequestValidator->run(new HasOneOrMoreInstallments($numberOfInstallments));
            if ($validation->fails()) {
                $userRepository->unlockUser($this->user);
                return $validation->response();
            }

            $transactionProcessor->checkLoanType(
                $this->loanProduct,
                $userRepository,
                $numberOfInstallments,
                $this->amount
            );

            /**
             * Check if it is a multi installment loan and if the user has an outstanding loan, if it does
             */
            if ($transactionProcessor->loanIs(TransactionProcessor::TOPUP_LOAN)) {
                /**
                 * Check if the requested amount is more than the balance
                 */
                $validation = $loanRequestValidator->run(new IsRequestedAmountLessThanBalance($this->amount));
                if ($validation->fails()) {
                    $userRepository->unlockUser($this->user);
                    return $validation->response();
                }

                /**
                 * Check if allowed to top up by checking if user has paid more than 60% of his debt
                 */
                $validation = $loanRequestValidator->run(new IsNotAllowedToTopUp);
                if ($validation->fails()) {
                    $userRepository->unlockUser($this->user);
                    return $validation->response();
                }
            }

            /**
             * Check if it is a multi installment loan and if the user has an outstanding loan, if it does
             */
            if ($transactionProcessor->loanIs(TransactionProcessor::PARTIAL_LOAN)) {

                /**
                 * Check if allowed to top up by checking if user has paid more than 60% of his debt
                 */
                $validation = $loanRequestValidator->run(new IsNotAllowedToTakePartialLoan);
                if ($validation->fails()) {
                    $userRepository->unlockUser($this->user);
                    return $validation->response();
                }
            }

            /**
             * Check if the amount requested has exceeded the loan limit of the user
             */
            $validation = $loanRequestValidator->run(new IsRequestedAmountExceedingLoanLimit($this->amount, $transactionProcessor->getLoanType()));
            if ($validation->fails()) {
                $userRepository->unlockUser($this->user);
                return $validation->response();
            }

            /**
             * Check if the user is qualified for the loan, has verified NIDA (when implemented)
             * or has exceeded payment period in previous loans
             */
            $validation = $loanRequestValidator->run(new IsNotQualifiedForLoan);
            if ($validation->fails()) {
                $userRepository->unlockUser($this->user);
                return $validation->response();
            }

            /**
             * Check if user's loan is greater than the minimum acceptable
             */
            $validation = $loanRequestValidator->run(new IsRequestedLoanLessThanMinimumAllowed($this->amount));
            if ($validation->fails()) {
                $userRepository->unlockUser($this->user);
                return $validation->response();
            }

            /**
             * Get the agent that can make facilitate the amount requested, for the installments
             * requested.
             */
            $loaner = $this->getLoaner($this->user, $this->amount, $numberOfInstallments);

            /**
             * If the agent is not found, then return that the amount has not been found, and the loan
             * can not be facilitated and unlock the user to allow subsequent transactions
             */
            if (!$loaner) {
                $message = __('messages.amount_not_available');
                $loanLog->update(['message' => $message, 'status' => 'failed']);
                $userRepository->unlockUser($this->user);

                return $this->setData(false, $message);
            }
        } catch (LoanInstallmentAmountException $e) {
            DB::rollback();
            $message = $e->getMessage();
            FacadesLog::error($message, [$e]);
            $loanLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($this->user);

            return $this->setData(false, $message);
        } catch (\Throwable $th) {
            /**
             * In case anything has gone wrong, then log the error, return the response to
             * the user, then unlock the user to allow subsequent transactions
             */
            FacadesLog::error($th->getMessage(), [$th]);
            $message = __('messages.transaction_failed');
            $loanLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($this->user);

            return $this->setData(false, __('messages.transaction_failed'));
        }

        /**
         * Begin transactions for the following transactions
         */
        DB::beginTransaction();

        try {
            /**
             * Process top up loans differently. If it is a top up loan, check for installments, if
             * it is and it is more than one installment treat it differently, if it is not
             * and it is a top up, treat differently
             */
            $transactionResponse = $transactionProcessor->processLoan(
                $loaner,
                $userRepository,
                $numberOfInstallments,
                $this->amount,
                $this->loanProduct,
                $this->loanType,
                $this->transactionLogType,
                $this->disbursementType
            );

            /**
             * If loan type is not shopping loan, then send the money to the user using mlipa
             * if it is a shopping loan, then do not send the money to the user.
             */
            if ($this->loanType != 'shopping') {
                if ($this->shouldDisburseLoan) {
                    $mlipaResponse = $this->sendMoneyToUser(
                        $transactionResponse->amountToDisburse
                    );

                    $loanRequest = $transactionResponse->loanRequest;

                    $loanRequest->update(['status' => 'success']);
                    $updatedLoanData = $this->completeLoanRequest(
                        $this->user,
                        $this->user->owner,
                        $loaner,
                        $loanRequest,
                        null,
                        $this->loanProduct
                    );

                    if (!$mlipaResponse->success) {
                        /**
                         * Get the transaction ID previously sent to mlipa and then update the transaction
                         */
                        $transactionId = $mlipaResponse->data['transaction_id'] ?? null;
                        (new UserMlipaTransactionRepository)->updateTransaction($transactionId, $loanRequest->id);
                        FacadesLog::error('M-lipa request failed: ', [$mlipaResponse]);
                    }
                }else{
                    info("Disbursement not allowed for this loan!");
                }
            } else {
                $updatedLoanData = $this->completeLoanRequest(
                    $this->user,
                    $this->user->owner,
                    $loaner,
                    $transactionResponse->loanRequest,
                    null,
                    $this->loanProduct
                );
            }

            DB::commit();

            try {
                event(new LoanCreated($updatedLoanData, false));
            } catch (\Throwable $th) {
                FacadesLog::error('Loan created event failed!', [$th]);
            }
            $message = __('messages.transaction_success');
            $loanLog->update(['message' => $message, 'status' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            $message = __('messages.transaction_failed');
            $loanLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($this->user);
            FacadesLog::error($th->getMessage(), [$th]);

            return $this->setData(false, __('messages.transaction_failed'));
        }

        $userRepository->unlockUser($this->user);

        return $this->setData(true, $message);
    }

    /**
     * Send cash to user
     */
    public function sendMoneyToUser($amount = null)
    {
        $userAccount = (new UserAccountRepository)->getAccount($this->user);

        return (new MlipaTransactionHandler)->disburse(
            $userAccount,
            $amount ?: $this->amount,
            'balance'
        );
    }

    /**
     * Complete the loan request
     *
     * @param  User  $borrower
     * @param  User  $owner
     * @param  User  $fundSource
     * @param  LoanRequest  $loan
     * @param  int|float  $interest_rate
     * @param  LoanProduct|null  $loanProduct
     * @return LoanRequest $loan
     */
    public function completeLoanRequest(
        User $borrower,
        User $owner,
        User $fundSource,
        LoanRequest $loan,
        $interest_rate = null,
        LoanProduct $loanProduct = null
    ) {
        if (!$interest_rate) {
            $firstInstallment = $loan->installments()->orderBy('id', 'ASC')->first();
            $interest_rate = ($firstInstallment->interest / $firstInstallment->loan_balance) * 100;
        }

        $interest = $loan->installments->sum('interest');
        $charges = $loan->installments->sum('charges');
        $principal = $loan->installments->sum('principal');
        $user_type = $borrower->isCompanyEmployee() ? 'employee' : 'individual';

        $data = [
            'user_type' => $user_type,
            'agent_organisation_id' => $owner->id,
            'loan_product_id' => $loanProduct->id,
            'loan_product_name' => $loanProduct->name,
            'agent_or_organisation_name' => $owner->name,
            'user_name' => $borrower->name,
            'user_phone' => $borrower->phone,
            'interest_rate' => $interest_rate,
            'principal' => $principal,
            'number_of_installments' => $loan->installments()->count(),
            'interest' => $interest,
            'charges' => $charges,
            'total_loan' => $interest + $charges + $principal,
            'total_charge' => $interest + $charges,
            'loan_code' => $loan->loanCode(),
            'fund_source_name' => $fundSource->name,
            'fund_source_id' => $fundSource->id,
            'fund_source' => get_class($fundSource),
        ];
        $loan->update($data);

        // add the charges for the loan requested
        if ($loanProduct) {
            if ($loanProduct->charges->count() > 0) {
                foreach ($loanProduct->charges as $key => $charge) {
                    $chargeAmount = $charge->type == "Fixed" ?
                        $charge->amount : ($charge->amount / 100) * $principal;

                    $loan->appliedCharges()->create([
                        'charge_id' => $charge->id,
                        'amount' => $chargeAmount,
                        'type' => $charge->type,
                        'value' => $charge->amount_or_percent,
                        'from' => $charge->from,
                        'charge_data' => $charge->toArray()
                    ]);
                }
            }
        }

        return $loan;
    }
}
