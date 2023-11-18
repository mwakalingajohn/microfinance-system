<?php

namespace App\Library\Handlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class IsUserLocked implements Validator
{
    use DataResponseTrait;

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        if ($userRepository->isUserLocked()) {
            $message = __('messages.user_locked');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
