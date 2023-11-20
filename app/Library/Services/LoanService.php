<?php

namespace App\Library\Services;

use App\Library\DTOs\InternalResponse;
use App\Library\Traits\HasInternalResponse;
use App\Models\LoanApplication;

class LoanService
{
    use HasInternalResponse;

    public function disburse(LoanApplication $loanApplication, string $method): InternalResponse
    {
        return new InternalResponse();
    }
}
