<?php

namespace App\Utils\TransactionHandlers\RequestLoanHandler\ChargeCalculators;

use App\LoanProduct;
use App\Traits\DataResponseTrait;

class PrincipleChargeCalculator implements ChargeCalculator
{

    use DataResponseTrait;

    public function handle(array $installments, array $charge){

    }
}
