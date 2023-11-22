<?php

namespace App\Library\Services;

use App\Library\DTOs\InternalResponse;
use App\Library\Handlers\ProcessLoanApplication\LoanApplicationHandler;
use App\Library\Traits\HasInternalResponse;
use App\Models\LoanApplication;

class LoanService
{
    use HasInternalResponse;

    public function disburse(LoanApplication $loanApplication, array $data): InternalResponse
    {
        $loanApplicationHandler = new LoanApplicationHandler(
            $loanApplication,
            $data
        );
        return $loanApplicationHandler->handle();
    }
}
