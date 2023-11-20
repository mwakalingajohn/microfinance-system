<?php

namespace App\Library\Handlers\ProcessLoanApplication\Validators;

use App\Models\LoanApplication;
use Closure;

class ItIsFullyApproved
{
    public function __invoke(LoanApplication $loanApplication, Closure $next)
    {
        return $next($loanApplication);
    }
}
