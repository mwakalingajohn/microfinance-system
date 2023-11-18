<?php

namespace App\Library\Handlers\RequestLoanHandler\TransactionProcessors;

use App\LoanProduct;
use App\Repositories\UserRepository;
use App\User;
use App\Library\Handlers\RepayLoanHandler;

class TopUpLoan implements Processor
{
    use CanProcessTransaction;

    public function isType(
        LoanProduct $loanProduct,
        UserRepository $userRepository,
        int $numberOfInstallments,
        float $amount
    ): bool {
        return $numberOfInstallments > 1 && $userRepository->hasOutstandingLoan();
    }

    public function processLoan(
        User $loaner,
        UserRepository $userRepository,
        int $numberOfInstallments,
        float $amount,
        LoanProduct $loanProduct,
        $interest_rate = null
    ) {
        /**
         * Check if the loan is a single loan or multiple loan installment transaction
         */
        $isMultiple = $numberOfInstallments > 1;

        /**
         * Get total previous balance
         */
        $outstandingBalance = $this->user->total_loans;
        $outstandingPrinciple = $this->user->total_principle;

        /**
         * Get the amount to be disbursed
         */
        $amountToDisburse = $this->amount - ($isMultiple ?
            $outstandingBalance :
            $outstandingPrinciple);

        /**
         * Repay the previous loan
         */
        (new RepayLoanHandler(
            $this->user,
            $outstandingBalance,
            ''
        ))->handle();

        /**
         * Create a loan request entry in the table for the user which will also be
         * attached with installments, and status of the loan if paid etc
         */
        $loanRequest = $this->createLoanRequest();

        /**
         * Get installments for the given amount and number of installments
         */
        $installments = $this->calculateInstallments($numberOfInstallments, $loanProduct, $loaner, $this->user);

        /**
         * Get the total charge and store the installments in the loan installments table
         */
        $totalLoanCharge = $this->storeInstallments($installments, $loanRequest);

        /**
         * Update the loan request with the total loan charge after calculation
         */
        $this->updateLoanRequest($loanRequest, $totalLoanCharge);

        /**
         * Update the user account and agent account with the changed balances
         */
        $this->updateAccounts($loaner, $totalLoanCharge, $loanRequest);

        /**
         * Update user's last loan date in the users table
         */
        $userRepository->updateLastLoanDate();

        return (object) [
            'amountToDisburse' => $amountToDisburse,
            'loanRequest' => $loanRequest,
        ];
    }
}
