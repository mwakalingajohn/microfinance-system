<?php

namespace Tests\Library\Traits;

use App\Library\Traits\HandlesChargeDeduction;
use App\Models\LoanInstallmentCharge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('should update charge deduction', function () {
    $class = new class {
        use HandlesChargeDeduction;
    };
    $charge = LoanInstallmentCharge::factory()->create([
        "amount" => 1000,
        "remaining_amount" => 1000
    ]);
    $chargesRepaid = 500;
    $class->updateChargeDeduction($charge, $chargesRepaid);
    expect($charge->remaining_amount)->toBe(500);
});
