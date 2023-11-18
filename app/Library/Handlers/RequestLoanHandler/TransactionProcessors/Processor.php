<?php

namespace App\Library\Handlers\RequestLoanHandler\TransactionProcessors;

use App\LoanProduct;
use App\Repositories\UserRepository;
use App\User;

interface Processor
{
    public function isType(
        LoanProduct $loanProduct,
        UserRepository $userRepository,
        int $numberOfInstallments,
        float $amount
    ): bool;

    public function processLoan(
        User $loaner,
        UserRepository $userRepository,
        int $numberOfInstallments,
        float $amount,
        LoanProduct $loanProduct
    );
}
