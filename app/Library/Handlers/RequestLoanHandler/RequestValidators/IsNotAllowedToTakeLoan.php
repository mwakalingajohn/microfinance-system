<?php

namespace App\Utils\TransactionHandlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class IsNotAllowedToTakeLoan implements Validator
{
    use DataResponseTrait;

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        $user = $userRepository->getUser();
        if (! $userRepository->loansEnabled()) {
            $message = __('messages.loan_not_allowed');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($user);

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
