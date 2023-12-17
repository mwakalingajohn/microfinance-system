<?php

namespace App\Library\Traits;

use App\Library\DTOs\Repayment;
use App\Models\LoanInstallment;
use App\Models\LoanRepaymentDetail;

trait HandlesInstallmentDeduction
{

    protected function updateInstallmentDeduction(
        LoanInstallment &$loanInstallment,
        Repayment &$repayment,
        $column
    ) {

        // get remaining installment
        $remainingValue = $loanInstallment->{$column};
        $remainingInstallment = $loanInstallment->remaining_installment;

        // get balance
        $balance = $repayment->getDeductionBalance();
        $repaidAmount = $balance > $remainingValue ?
            $remainingValue :
            $balance;

        // update remaining interest value in the installment table
        $loanInstallment->{$column} = $balance > $remainingValue ?
            0 :
            $remainingValue - $balance;

        $loanInstallment->remaining_installment = $balance > $remainingValue ?
            $remainingInstallment - $remainingValue :
            $remainingInstallment - $balance;

        $loanInstallment->save();

        // update balance in $repayment object and pass it on to next handler
        $balance = $balance > $remainingValue ?
            $balance - $remainingValue :
            0;

        $repayment->setDeductionBalance($balance);

        return $repaidAmount;
    }
}
