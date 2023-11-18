<?php

namespace App\Utils\TransactionHandlers\RequestLoanHandler\ChargeCalculators;

use App\LoanProduct;

interface ChargeCalculator
{
    public function handle(array $installments, array $charge);

    public function setData($success = false, $message = null, $data = null);
}
