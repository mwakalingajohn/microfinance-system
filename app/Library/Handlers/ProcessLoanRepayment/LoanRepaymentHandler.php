<?php

namespace App\Library\Handlers\ProcessLoanRepayment;

use App\Library\DTOs\InternalResponse;
use App\Library\Enums\RepaymentOrderItem;
use App\Library\Traits\HasInternalResponse;
use App\Models\Loan;

class LoanRepaymentHandler
{
    use HasInternalResponse;

    public function __construct(
        public Loan $loan,
        public array $loanRepaymentData
    ) {
    }

    public function handle(): InternalResponse
    {

        // get the repayment order
        $this->getRepaymentOrder();

        // deduct the balances according to the order

            // after each deduction update the repayment table

            // if it is charges/penalties deducting then update with respect to the individual charges
            // store the charge details
            // store the penalty details

        // update the loan status and installment status

        return $this->setResponse(false);
    }

    private function getRepaymentOrder(){
        return [
            RepaymentOrderItem::Penalties,
            RepaymentOrderItem::Interest,
            RepaymentOrderItem::Charges,
            RepaymentOrderItem::Principal
        ];
    }
}
