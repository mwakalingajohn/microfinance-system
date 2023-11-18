<?php

namespace App\Library\Handlers\RequestLoanHandler\TransactionProcessors;

use App\Calculations\InstallmentCalculations;
use App\Exceptions\LoanInstallmentAmountException;
use App\LoanProduct;
use App\LoanRequest;
use App\Repositories\LoanInstallmentRepository;
use App\Repositories\LoanRequestRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserAccountRepository;
use App\Repositories\UserAccountTransactionsRepository;
use App\Repositories\UserRepository;
use App\User;
use App\UserAccount;
use Exception;

/**
 * Allow the transaction to process
 */
trait CanProcessTransaction
{
    /**
     * Variables from the previous implementation
     */
    private User $user;

    private float $amount;

    private string $loanType;

    private string $transactionLogType;

    private string $disbursementType;

    private int $minimum_amount_for_installments;

    /**
     * Set the data required for processing
     */
    public function set($user, $amount, $loanType, $transactionLogType, $disbursementType)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->loanType = $loanType;
        $this->transactionLogType = $transactionLogType;
        $this->disbursementType = $disbursementType;
    }

    /**
     * Create loan request and use id to create loan installments
     */
    public function createLoanRequest()
    {
        return (new LoanRequestRepository)->create([
            'user_id' => $this->user->id,
            'amount' => $this->amount,
            'type' => $this->loanType,
            'number_of_installments' => 0,
        ]);
    }

    /**
     * Calculate number of installments
     */
    public function calculateInstallments($numberOfInstallments, $loanProduct = null, $loaner, $user)
    {
        $installmentCalculations = new InstallmentCalculations(
            $loaner,
            $user
        );

        return $installmentCalculations->calculate($this->amount, $numberOfInstallments, $loanProduct);
    }

    /**
     * Store the installments for this particular loan
     */
    public function storeInstallments($installments, $loanRequest)
    {
        $total_charge = 0;

        foreach ($installments as $key => $installment) {
            (new LoanInstallmentRepository)->store([
                'loan_request_id' => $loanRequest->id,
                'loan_balance' => $installment->loanBalance,
                'principal' => $installment->principal,
                'interest' => $installment->interest,
                'charges' => $installment->charges,
                'installment' => $installment->installment,
                'remaining_installment' => $installment->installment,
                'remaining_installment_principle' => $installment->principal,
                'remaining_installment_interest' => $installment->interest,
                'remaining_installment_charges' => $installment->charges,
                'due_date' => $installment->due_date,
            ]);

            $total_charge += $installment->charges + $installment->interest;
        }

        return $total_charge;
    }

    /**
     * Update the loan request to include the actual charge
     */
    public function updateLoanRequest(LoanRequest $loanRequest, $loanCharge)
    {
        (new LoanRequestRepository)->update($loanRequest, $loanCharge);
    }

    /**
     * Update the responsive user accounts
     */
    public function updateAccounts(User $loaner, $loanCharge, $loanRequest)
    {
        $userAccount = (new UserAccountRepository)->getAccount(
            $this->user
        );
        $loanerAccount = (new UserAccountRepository)->getAccount(
            $loaner,
            (new RoleRepository)->getByName('agent')
        );

        $loanerBalance = $this->getLoanerBalance($loanerAccount, $loaner, $this->amount, $loanCharge);
        $userBalance = $this->getUserBalance($userAccount, $this->amount, $loanCharge);

        $loaneeData = $this->loaneeData($this->amount, $loanerAccount->account_number, $userAccount->account_number, $userBalance);
        $loanerData = $this->loanerData($this->amount, $loanerAccount->account_number, $userAccount->account_number, $loanerBalance);

        $this->createUserAccountTransaction($loanerData);
        $userTransaction = $this->createUserAccountTransaction($loaneeData);

        (new UserAccountRepository)->updateBalance($userAccount, $userBalance);
        (new UserAccountRepository)->updateBalance($loanerAccount, $loanerBalance);

        if ($this->disbursementType == 'agent') {
            (new UserAccountRepository)->decreaseAgentBalance($loanerAccount, $this->amount);
        } elseif ($this->disbursementType == 'corporate') {
            (new UserAccountRepository)->decreaseCorporateBalance($loanerAccount, $this->amount);
        }

        /**
         * We are not using company balance anymore, instead the company balance is taken from the cumulative value of it's members
         * balances
         */
        if ($this->user->isCompanyEmployee()) {
            // $this->updateCompanyAccount();
        }

        $loanRequest->loanSuccessful($userTransaction->id);
    }

    /**
     * Get data of the user taking the loan for account transaction record
     */
    public function loanerData(
        $amount,
        $loaner_account_number,
        $loanee_account_number,
        $new_balance
    ) {
        return [
            'account_number' => $loaner_account_number,
            'amount_given' => $amount,
            'amount_given_to' => $loanee_account_number,
            'balance_after_transaction' => $new_balance,
            'type' => $this->transactionLogType,
        ];
    }

    /**
     * Get data of the user taking the loan for account transaction record
     */
    public function loaneeData(
        $amount,
        $loaner_account_number,
        $loanee_account_number,
        $new_balance
    ) {
        return [
            'account_number' => $loanee_account_number,
            'amount_received' => $amount,
            'amount_received_from' => $loaner_account_number,
            'balance_after_transaction' => $new_balance,
            'type' => $this->transactionLogType,
        ];
    }

    /**
     * The current role, used to get role model
     *
     * @var string
     */
    protected $role = null;

    /**
     * Get role used in the current session
     */
    public function getRole()
    {
        if ($this->role) {
            return (new RoleRepository)->getByName($this->role);
        } else {
            return (new RoleRepository)->getDefaultRole(null);
        }
    }

    /**
     * Store the user account transaction
     */
    public function createUserAccountTransaction($data)
    {
        return (new UserAccountTransactionsRepository)->create($data);
    }

    /**
     * fetch insterest from the database depending on the user, and the amount
     */
    public function getInterest($customer_id, $amount, $has_owner = true, $numberOfInstallments = 1)
    {

        // get the owner
        $user = (new UserRepository())->find($customer_id);
        $userRepository = new UserRepository($user);
        $owner = $userRepository->getUserOwner();

        if (! $owner) {
            throw new Exception("User doesn't have an owner!");
        }

        if ($user->hasRole('employee')) {
            if ($numberOfInstallments > 1) {
                return 17;
            } else {
                return 10;
            }
        } elseif ($user->hasRole('customer')) {
            if (
                (($numberOfInstallments == 1) && ($amount > 200000))
            ) {
                throw new LoanInstallmentAmountException(__('messages.bad_installment_amount_more'));
            }
            if (
                (($numberOfInstallments > 1) && ($amount <= 200000))
            ) {
                throw new LoanInstallmentAmountException(__('messages.bad_installment_amount_less'));
            }

            if ($numberOfInstallments > 1 || $amount > 200000) {
                return 17;
            } else {
                return 10;
            }
        }
    }

    /**
     * Minimum amount for which installment more than one can be used
     */
    public function getMinimimumAmountForInstallments()
    {
        return $this->minimum_amount_for_installments = config('kf.minimum_amount_for_installments');
    }

    /**
     * Get the number of installments for a particular user
     */
    public function getNumberOfInstallments($user_id, $amount = null, LoanProduct $loanProduct = null)
    {
        $customer = (new UserRepository)->find($user_id);
        $number_of_installments = $customer->detail->number_of_installments ?? 1;
        if ($number_of_installments > 1) {
            $number_of_installments = $amount <= config('kf.minimum_amount_for_installments') ?
                1 : $number_of_installments;
        }

        return $number_of_installments;
    }

    /**
     * if its company employee, return the company as the loaner, for now
     * if its an agent's member, then check if the agent has cash, if yes then give the account
     * the loan from its original agent, otherwise, check if any other agent has enough balance then
     * give him the loan.
     *
     * Also if the loaner has multiple accounts, do not fret, the account returned already is an agent
     * account
     */
    public function getLoaner($user, $amount, $number_of_installments = 1)
    {
        $userRepository = new UserRepository($user);

        if ($userRepository->getUserOwner() && ! $user->isCompanyEmployee()) {
            if ($user->owner->account->agent_balance > $amount) {
                return $userRepository->getUserOwner();
            }
        }

        $disbursement_type = (new RoleRepository)->getDisbursementTypeByUser($user);

        if ($number_of_installments > 1 && $user->isCompanyEmployee()) {
            return $userRepository->getDefaultAgent($amount);
        }

        $random_account_with_float = $userRepository->randomAccountWithFloat($amount, 'agent', $disbursement_type);

        return $random_account_with_float ?? null;
    }

    /**
     *  when the amount is partial check what is to be paid then update the
     *  accordingly
     */
    public function getInsuranceAmount($user_id, $amount = 0, $is_company_user = false)
    {
        if (! $is_company_user) {
            return $amount < 20000 ?
                ($amount * 0.1) :
                config('kf.loan_insurance_amount');
        } else {
            return config('kf.corporate_loan_insurance_amount');
        }
    }

    /**
     *  when the amount is partial check what is to be paid then update the
     *  accordingly
     */
    public function getInsuranceAmountWithUser($user_id, $amount = 0, $is_company_user = false)
    {
        $user = (new UserRepository())->find($user_id);
        $unpaidLoans = $this->getUnpaidLoans($user);

        if (! $is_company_user) {
            return $amount < 20000 ?
                ($amount * 0.1) :
                config('kf.loan_insurance_amount');
        } else {
            // Add window consideration
            if ($unpaidLoans->count() < 1) {
                return config('kf.corporate_loan_insurance_amount');
            } else {
                return 0;
            }
        }
    }

    /**
     * Get the list of all unpaid loans
     *
     * @param  User  $user
     * @return Collection
     */
    public function getUnpaidLoans(User $user)
    {
        $userRepository = new UserRepository($user);
        if ($userRepository->isProcessingLoans()) {
            $dates = $userRepository->getCompanyCarbonClosingAndOpeningDates();

            return (new LoanRequestRepository())->unpaidLoansWithinCycle($user, $dates->start_date, $dates->end_date);
        } else {
            return (new LoanRequestRepository())->unpaidLoans($user);
        }
    }

    /**
     * Get amount that is required to be paid back for a particular loan
     *
     * TODO:
     * Make sure to get the user coming in here.
     */
    public function getRepaymentAmount($user_id, $amount, $has_owner = true)
    {
        throw new Exception('getRepaymentAmount() not implemented!');
        $interest = $this->getInterest($user_id, $amount, $has_owner);

        return ($interest / 100) * $amount;
    }

    /**
     * Get the balance of the user taking the loan
     */
    public function getUserBalance($userAccount, $amount, $loanCharge = 0)
    {
        return $userAccount->balance + ($amount + $loanCharge);
    }

    /**
     * Get the balance of the user giving the loan
     */
    public function getLoanerBalance(UserAccount $loanerAccount, $loaner, $amount, $loanCharge = 0)
    {
        if ($loaner->isCompany()) {
            return $loanerAccount->balance + ($amount + $loanCharge);
        } elseif ($loaner->isAgent()) {
            return $loanerAccount->balance - $amount;
        }
    }
}
