<?php

namespace App\Library\Traits;

use App\Models\LoanInstallmentCharge;

trait HandlesChargeDeduction
{
    public function updateChargeDeduction(LoanInstallmentCharge $charge, $chargesRepaid)
    {

        if ($chargesRepaid > $charge->remaining_amount) {
            $charge->update([
                "remaining_amount" => 0
            ]);
            $chargesRepaid = $chargesRepaid - $charge->remaining_amount;
        } else {
            $charge->update([
                "remaining_amount" => $charge->remaining_amount - $chargesRepaid
            ]);
            $chargesRepaid = 0;
        }
        
        return $chargesRepaid;
    }
}
