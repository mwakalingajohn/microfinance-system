<?php

namespace App\Library\Handlers\ProcessLoanRepayment\Deductor;

use App\Library\DTOs\Repayment;
use App\Library\Enums\RepaymentOrderItem;
use App\Library\Traits\HandlesChargeDeduction;
use App\Library\Traits\HandlesInstallmentDeduction;
use App\Library\Traits\HandlesLoanRepaymentDetail;
use Closure;

class ChargeDeductor implements Deductor
{
    use HandlesLoanRepaymentDetail;
    use HandlesInstallmentDeduction;
    use HandlesChargeDeduction;

    protected $type = RepaymentOrderItem::Charges;

    /**
     * Deduct remaining charges
     *
     * @param Repayment $repayment
     * @param Closure $next
     * @return Closure
     */
    public function __invoke(Repayment $repayment, Closure $next): mixed
    {
        if($repayment->deductionBalance > 0) {

            $installment = $repayment->getInstallment();
            $repaymentDetail = $this->createLoanRepaymentDetail($repayment);
    
            $repaidAmount = $this->updateInstallmentDeduction(
                $installment,
                $repayment,
                "remaining_charges"
            );
    
            $repaymentDetail->update([
                "amount" => $repaidAmount,
                "type" => $this->type
            ]);
    
            $chargesRepaid = $repaidAmount;
    
            // update loan installment charge deduction
            foreach ($installment->addedCharges as $key => $charge) {
                if ($chargesRepaid <= 0) break;
                $chargesRepaid = $this->updateChargeDeduction($charge, $chargesRepaid);
            }
        }


        return $next($repayment);
    }
}
