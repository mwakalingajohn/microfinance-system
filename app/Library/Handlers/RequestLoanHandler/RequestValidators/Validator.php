<?php

namespace App\Library\Handlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;

interface Validator
{
    public function handle(LoanRequestLog $loanRequestLog, UserRepository $userRepository);

    public function setData($success = false, $message = null, $data = null);
}
