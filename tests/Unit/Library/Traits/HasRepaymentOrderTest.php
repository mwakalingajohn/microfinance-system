<?php

namespace Tests\Library\Traits;

use App\Library\Enums\RepaymentOrderItem;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\ChargeDeductor;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\InterestDeductor;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\PenaltyDeductor;
use App\Library\Handlers\ProcessLoanRepayment\Deductor\PrincipalDeductor;
use App\Library\Traits\HasRepaymentOrder;
use PHPUnit\Framework\TestCase;

uses(\Tests\TestCase::class);

it('can manage the repayment order state', function () {
    $class = new class {
        use HasRepaymentOrder;

        public function _getDeductors()
        {
            return $this->getDeductors();
        }

        public function _mapDeductorsToRepaymentOrder()
        {
            return $this->mapDeductorsToRepaymentOrder();
        }
    };

    $defaultOrder = [
        RepaymentOrderItem::Charges->value => ChargeDeductor::class,
        RepaymentOrderItem::Penalties->value => PenaltyDeductor::class,
        RepaymentOrderItem::Interest->value => InterestDeductor::class,
        RepaymentOrderItem::Principal->value => PrincipalDeductor::class,
    ];
    expect($class->_getDeductors())->toBe($defaultOrder)
        ->and($class->_mapDeductorsToRepaymentOrder())->toBe($defaultOrder);
});
