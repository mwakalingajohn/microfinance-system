<?php

namespace App\Library\Handlers\ProcessLoanRepayment\Deductor;

use App\Library\DTOs\Repayment;
use Closure;

class PenaltyDeductor implements Deductor
{

    public function __invoke(Repayment $repayment, Closure $next): Closure
    {

        //.. do some magic here

        return $next($repayment);
    }
}
