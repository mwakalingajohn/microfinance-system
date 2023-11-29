<?php

namespace App\Library\Handlers\ProcessLoanRepayment\Deductor;

use App\Library\DTOs\Repayment;
use Closure;

interface Deductor
{
    public function __invoke(Repayment $repayment, Closure $next): Closure;
}
