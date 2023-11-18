<?php

namespace App\Library\Handlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use App\Traits\DataResponseTrait;

class DoesntHaveLoanProduct implements Validator
{
    use DataResponseTrait;

    private $user;

    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        $this->user = $userRepository->getUser();
        $loanProduct = $userRepository->getLoanProduct();

        if (! $loanProduct) {
            $message = __('messages.loan_product_not_set');
            $loanRequestLog->update(['message' => $message, 'status' => 'failed']);
            $userRepository->unlockUser($this->user);

            return $this->setData(false, $message);
        }

        return $this->setData(true, null, (object) [
            'loanProduct' => $loanProduct,
        ]);
    }
}
