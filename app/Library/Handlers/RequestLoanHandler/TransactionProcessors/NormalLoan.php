<?php

namespace App\Library\Handlers\RequestLoanHandler\TransactionProcessors;

use App\LoanProduct;
use App\Repositories\UserRepository;
use App\User;

class













































































NormalLoan implements Processor
{
    use CanProcessTransaction;

    public function isType(
        LoanProduct $loanProduct,
        UserRepository $userRepository,
        int $numberOfInstallments,
        float $amount
    ): bool {
        return $userRepository->hasOutstandingLoan() == false;
    }

    public function processLoan(
        User $loaner,
        UserRepository $userRepository,
        int $numberOfInstallments,
        float $amount,
        LoanProduct $loanProduct
    ) {
        /**
         * Create a loan request entry in the table for the user which will also be
         * attached with installments, and status of the loan if paid etc
         */
        $loanRequest = $this->createLoanRequest();

        /**
         * Get installments for the given interest, amount and number of installments
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
            'amountToDisburse' => $this->amount,
            'loanRequest' => $loanRequest,
        ];
    }
}
