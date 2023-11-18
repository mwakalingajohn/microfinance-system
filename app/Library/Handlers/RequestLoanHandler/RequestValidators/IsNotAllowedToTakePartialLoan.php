<?php

namespace App\Library\Handlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class IsNotAllowedToTakePartialLoan implements Validator
{
    use DataResponseTrait;

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        if (! $userRepository->isAllowedToTakePartialLoan()) {
            $message = __('messages.not_allowed_to_take_partial');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($userRepository->getUser());

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
