<?php

namespace App\Utils\TransactionHandlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class IsRequestedAmountLessThanBalance implements Validator
{
    use DataResponseTrait;

    public function __construct(private float $amount)
    {
    }

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        if (! $userRepository->isRequestedAmountMoreThanBalance($this->amount)) {
            $message = __('messages.requested_amount_lower_than_balance');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($userRepository->getUser());

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
