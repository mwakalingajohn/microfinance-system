<?php

namespace App\Library\Handlers;

use App\Events\LoanCreated;
use App\Exceptions\LoanInstallmentAmountException;
use App\Library\DTOs\InternalResponse;
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
use App\Models\LoanApplication;
use App\Models\LoanDisbursement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;

class RequestLoanHandler
{

    public function __construct(
        public LoanApplication $loanApplication,
        public LoanDisbursement $loanDisbursement
    ) {
    }

    /**
     * Handle the loan request
     */
    public function handle()
    {
        return $this->runTransaction();
    }

    /**
     * Run the full database transaction, considering the conditions
     */
    public function runTransaction(): InternalResponse
    {

        /**
         * Create the loan processor
         */
        $transactionProcessor = new TransactionProcessor;
        
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
                } else {
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
