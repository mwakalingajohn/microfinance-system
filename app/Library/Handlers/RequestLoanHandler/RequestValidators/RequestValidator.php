<?php

namespace App\Library\Handlers\RequestLoanHandler\RequestValidators;

use App\LoanRequestLog;
use App\Repositories\UserRepository;
use Exception;

class RequestValidator
{
    private $userRepository;

    private $loanRequestLog;

    private $response;

    public function __construct(LoanRequestLog $loanRequestLog, UserRepository $userRepository)
    {
        $this->loanRequestLog = $loanRequestLog;
        $this->userRepository = $userRepository;
    }

    public function run(Validator $validator)
    {
        if ($validator instanceof Validator) {
            $this->response = $validator->handle($this->loanRequestLog, $this->userRepository);

            return $this;
        } else {
            throw new Exception('Incorrect validator type');
        }
    }

    public function check()
    {
        return $this->response->success;
    }

    public function fails()
    {
        return ! $this->check();
    }

    public function response()
    {
        return $this->response;
    }
}
