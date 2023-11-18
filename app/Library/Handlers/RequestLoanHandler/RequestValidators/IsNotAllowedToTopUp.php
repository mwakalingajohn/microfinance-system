<?php

namespace App\Utils\TransactionHandlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class IsNotAllowedToTopUp implements Validator
{
    use DataResponseTrait;

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        if (! $userRepository->isAllowedToTopUp()) {
            $message = __('messages.not_allowed_to_top_up');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($userRepository->getUser());

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
