<?php

namespace App\Library\Handlers\ProcessLoanApplication\Calculators;

use App\Library\Enums\CanConvertTimePeriod;
use App\Library\Traits\CanCalculateDueDates;
use App\Library\Traits\CanCalculateEMI;
use App\Models\LoanApplication;
use Closure;
use Illuminate\Support\Fluent;

class CalculateCharges
{
    protected ?LoanApplication $loanApplication;
    protected ?Fluent $data;

    public function __invoke(LoanCalculation $loanCalculation, Closure $next)
    {
        $this->loanApplication = $loanCalculation->loanApplication;
        $this->data = new Fluent($loanCalculation->data);

        // get charges
        // calculate the charge amount
        // add charges to loan calculation
        // create consolidate installments
        return $next($loanCalculation);
    }
}
