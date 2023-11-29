<?php

namespace App\Library\Handlers\ProcessLoanRepayment\Deductor;

use App\Library\DTOs\Repayment;
use App\Library\Enums\RepaymentOrderItem;
use App\Library\Traits\HandlesInstallmentDeduction;
use App\Library\Traits\HandlesLoanRepaymentDetail;
use Closure;

class ChargeDeductor implements Deductor
{    
    use HandlesLoanRepaymentDetail;
    use HandlesInstallmentDeduction;

    protected $type = RepaymentOrderItem::Charges;

    /**
     * Deduct remaining charges
     *
     * @param Repayment $repayment
     * @param Closure $next
     * @return Closure
     */
    public function __invoke(Repayment $repayment, Closure $next): Closure
    {

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

        // update charge deduction

        return $next($repayment);
    }
}
