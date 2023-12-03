<?php

namespace App\Library\Handlers\ProcessLoanRepayment\Deductor;

use App\Library\DTOs\Repayment;
use App\Library\Enums\RepaymentOrderItem;
use App\Library\Traits\HandlesInstallmentDeduction;
use App\Library\Traits\HandlesLoanRepaymentDetail;
use Closure;

class PrincipalDeductor implements Deductor
{

    use HandlesLoanRepaymentDetail;
    use HandlesInstallmentDeduction;

    protected $type = RepaymentOrderItem::Principal;

    /**
     * Deduct remaining principal
     *
     * @param Repayment $repayment
     * @param Closure $next
     * @return Closure
     */
    public function __invoke(Repayment $repayment, Closure $next):  mixed
    {
        
        if($repayment->deductionBalance >  0) {
            
            $installment = $repayment->getInstallment();
            $repaymentDetail = $this->createLoanRepaymentDetail($repayment);
    
            $repaidAmount = $this->updateInstallmentDeduction(
                $installment,
                $repayment,
                "remaining_principal"
            );
            
            $repaymentDetail->update([
                "amount" => $repaidAmount,
                "type" => $this->type
            ]);
        };


        return $next($repayment);
    }
}
