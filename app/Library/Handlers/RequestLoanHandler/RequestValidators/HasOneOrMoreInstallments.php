<?php

namespace App\Utils\TransactionHandlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class HasOneOrMoreInstallments implements Validator
{
    use DataResponseTrait;

    public function __construct(private $numberOfInstallments)
    {
    }

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        if ($this->numberOfInstallments < 1) {
            $message = __('messages.zero_installments_not_allowed');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
