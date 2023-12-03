<?php

namespace App\Library\Traits;

trait CanCalculateEMI
{
    public function getEMIEqualPrincipals()
    {

    }

    /**
     * Get EMI equal installments
     *
     * @param float $interest
     * @param integer $numberOfInstallments
     * @param float $amount
     * @return float
     */
    public function getEMIEqualInstallments(
        float $interest,
        int $numberOfInstallments,
        float $amount
    )
    {
        $v1 = pow(1 + $interest, $numberOfInstallments);
        $v2 = $interest * $v1;
        $v3 = $v1 - 1;
        $emi = $amount * $v2 / $v3;
        return $emi;
    }
}
