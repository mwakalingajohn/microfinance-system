<?php

namespace App\Library\Handlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;
use App\User;

class IsRequestedAmountExceedingLoanLimit implements Validator
{
    use DataResponseTrait;

    private $amount;

    private $user;

    private $loanType;

    public function __construct($amount, $loanType)
    {
        $this->amount = $amount;
        $this->loanType = $loanType;
    }

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        $this->user = $userRepository->getUser();
        if ($this->user->hasRole(User::USER_TYPES['staff-card'])) {
            $mafutaLimit = $userRepository->getMafutaLimit($this->user);
            if ($this->amount > $mafutaLimit) {
                $message = __('messages.loan_exceeds_limit');
                $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
                $userRepository->unlockUser($this->user);

                return $this->setData(false, $message);
            }
        } elseif ($userRepository->exceedsLoanLimit($this->amount, $this->loanType)) {
            $message = __('messages.loan_exceeds_limit');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($this->user);

            return $this->setData(false, $message);
        }

        return $this->setData(true, null);
    }
}
