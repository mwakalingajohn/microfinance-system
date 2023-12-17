<?php

namespace Tests\Library\Traits;

use App\Library\Traits\CanCalculateEMI;
use PHPUnit\Framework\TestCase;

it('calculates EMI equal installments', function () {
    $emi = (new class {
        use CanCalculateEMI;
    })->getEMIEqualInstallments(0.03, 6, 1000000);
    $emi = round($emi, 2);
    expect($emi)->toBe(184597.50);
});
