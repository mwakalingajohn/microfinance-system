<?php

namespace App\Library\Traits;

use App\Library\DTOs\Installment;

trait CanCorrectInstallments
{
    /**
     * Correct the installments
     *
     * @param array<Installment> $installments
     * @return array<Installment>
     */
    public function correctInstallments(array $installments, float $requestedAmount): array
    {
        $_installments = collect($installments);
        $repaymentPrincipal = $_installments->sum('principal');

        if($requestedAmount != $repaymentPrincipal){
            $difference = $requestedAmount - $repaymentPrincipal;
            $_installments = $_installments->map(function ($installment, $index) use ($difference) {
                if($index == 0){
                    $installment->principal += $difference;
                    $installment->installment += $difference;
                }
                return $installment;
            });
        }

        return $_installments->toArray();
    }
}
