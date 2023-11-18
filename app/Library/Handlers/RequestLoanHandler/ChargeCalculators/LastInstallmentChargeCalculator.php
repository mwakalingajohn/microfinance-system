<?php

namespace App\Library\Handlers\RequestLoanHandler\ChargeCalculators;

use App\LoanProduct;
use App\Traits\CalculatesCharges;
use App\Traits\DataResponseTrait;

class LastInstallmentChargeCalculator
{
    use DataResponseTrait,
        CalculatesCharges;

    public function handle(array $installments, array $charge)
    {
        // calculate the total principal
        $totalPrincipal = collect($installments)->sum('principal');

        // create an empty array to hold the new installments
        $newInstallments = [];

        // loop through the installments
        foreach ($installments as $key => $installment) {
            // if the installment is the last installment
            if ($key == count($installments) - 1) {
                $installmentCharges = $this->calculateCharges(
                    $totalPrincipal,
                    $charge['type'],
                    $charge['amount_or_percent']
                );

                // add the charge to the installment
                $installment->charges = $installmentCharges;
                $installment->installment = $installment->installment + $installmentCharges;
            }

            // add the installment to the new installments array
            $newInstallments[] = $installment;
        }
        return $newInstallments;
    }
}
