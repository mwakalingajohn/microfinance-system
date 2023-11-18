<?php

namespace App\Utils\TransactionHandlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class IsRequestedLoanLessThanMinimumAllowed implements Validator
{
    use DataResponseTrait;

    private $amount;

    private $user;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        $this->user = $userRepository->getUser();
        if (! $userRepository->loanIsGreaterThanMinimum($this->amount)) {
            $message = __(
                'messages.loan_less_minimum',
                [
                    'minimum' => $userRepository->getMinimumLoanRequest(),
                ]
            );
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($this->user);

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
