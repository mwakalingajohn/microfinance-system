<?php

namespace App\Library\Traits;

use App\Library\Enums\RepaymentOrderItem;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\ChargeDeductor;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\Deductor;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\InterestDeductor;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\PenaltyDeductor;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\PrincipalDeductor;
use App\Models\LoanProduct;

trait HasRepaymentOrder
{

    protected $deductors = [
        RepaymentOrderItem::Charges->value => ChargeDeductor::class,
        RepaymentOrderItem::Penalties->value => PenaltyDeductor::class,
        RepaymentOrderItem::Interest->value => InterestDeductor::class,
        RepaymentOrderItem::Principal->value => PrincipalDeductor::class
    ];

    /**
     * @return array<Deductor> $deductors
     */
    protected function getDeductors()
    {
        return $this->deductors;
    }

    /**
     * Get loan product
     *
     * @return LoanProduct|null
     */
    protected function getLoanProduct(): ?LoanProduct
    {
        return null;
    }

    /**
     * Map deductors to repayment order
     *
     * @return void
     */
    protected function mapDeductorsToRepaymentOrder()
    {
        $deductors = $this->getDeductors();
        $repaymentOrder = $this->getRepaymentOrder();
        if (is_string($repaymentOrder))
            $repaymentOrder = json_decode($repaymentOrder, true);

        return collect($repaymentOrder)
            ->map(fn ($order, $value) => match (RepaymentOrderItem::from($value)) {
                RepaymentOrderItem::Interest => $deductors[RepaymentOrderItem::Interest->value],
                RepaymentOrderItem::Charges => $deductors[RepaymentOrderItem::Charges->value],
                RepaymentOrderItem::Principal => $deductors[RepaymentOrderItem::Principal->value],
                RepaymentOrderItem::Penalties => $deductors[RepaymentOrderItem::Penalties->value]
            })
            ->toArray();
    }

    /**
     * Get repayment oder
     *
     * @return array
     */
    protected function getRepaymentOrder(): array
    {
        $repaymentOrder = $this->getLoanProduct()->repayment_order ?? $this->defaultRepaymentOrder();
        return is_string($repaymentOrder) ?
            json_decode($repaymentOrder, true) :
            $repaymentOrder;
    }

    /**
     * Default Repayment order
     *
     * @return array
     */
    protected function defaultRepaymentOrder(): array
    {
        return RepaymentOrderItem::associativeValues();
    }
}
