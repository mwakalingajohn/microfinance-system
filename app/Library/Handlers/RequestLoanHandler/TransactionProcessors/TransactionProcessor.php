<?php

namespace App\Library\Handlers\RequestLoanHandler\TransactionProcessors;

use App\LoanProduct;
use App\Repositories\UserRepository;
use App\User;

class TransactionProcessor
{
    public const NORMAL_LOAN = 'normal_loan';

    public const PARTIAL_LOAN = 'partial_loan';

    public const TOPUP_LOAN = 'topup_loan';

    private string $loanType;

    /**
     * The available types of loans
     */
    const LOAN_TYPES = [
        self::PARTIAL_LOAN => PartialLoan::class,
        self::NORMAL_LOAN => NormalLoan::class,
        self::TOPUP_LOAN => TopUpLoan::class,
    ];

    /**
     * Get loan type
     */
    public function getLoanType(): string
    {
        return $this->loanType;
    }

    /**
     * Check what type is current loan
     */
    public function checkLoanType(LoanProduct $loanProduct, UserRepository $userRepository, $numberOfInstallments, $amount)
    {
        foreach (self::LOAN_TYPES as $key => $loanType) {
            $loanClass = self::LOAN_TYPES[$key];
            $loanClassInstance = new $loanClass;
            $isType = $loanClassInstance->isType(
                $loanProduct,
                $userRepository,
                $numberOfInstallments,
                $amount
            );
            if ($isType) {
                $this->loanType = $key;
                break;
            }
        }
    }

    /**
     * Check if loan is a certain type
     */
    public function loanIs($string)
    {
        return $string == $this->loanType;
    }

    /**
     * Select the loan processor class and then return the loan
     */
    public function processLoan(
        User $loaner,
        UserRepository $userRepository,
        int $numberOfInstallments,
        float $amount,
        LoanProduct $loanProduct,
        $loanType,
        $transactionLogType,
        $disbursementType
    ) {
        $loanClass = null;
        switch ($this->loanType) {
            case self::NORMAL_LOAN:
                $loanClass = self::LOAN_TYPES[self::NORMAL_LOAN];
                break;

            case self::PARTIAL_LOAN:
                $loanClass = self::LOAN_TYPES[self::PARTIAL_LOAN];
                break;

            case self::TOPUP_LOAN:
                $loanClass = self::LOAN_TYPES[self::TOPUP_LOAN];
                break;

            default:
                break;
        }

        if ($loanClass) {
            $loanClassInstance = new $loanClass;
            $loanClassInstance->set(
                $userRepository->getUser(),
                $amount,
                $loanType,
                $transactionLogType,
                $disbursementType
            );

            return $loanClassInstance->processLoan(
                $loaner,
                $userRepository,
                $numberOfInstallments,
                $amount,
                $loanProduct,
            );
        } else {
            return false;
        }
    }
}
