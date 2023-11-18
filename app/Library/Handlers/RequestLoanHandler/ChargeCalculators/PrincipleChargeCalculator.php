<?php

namespace App\Library\Handlers\RequestLoanHandler\ChargeCalculators;

use App\LoanProduct;
use App\Traits\DataResponseTrait;

class PrincipleChargeCalculator implements ChargeCalculator
{

    use DataResponseTrait;

    public function handle(array $installments, array $charge){

    }
}
