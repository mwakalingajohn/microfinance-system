<?php

namespace Tests\Library\Traits;

use App\Library\DTOs\Installment;
use App\Library\Traits\CanCorrectInstallments;
use PHPUnit\Framework\TestCase;

it('should correct the installments', function () {
    $requestedAmount = 10000.0;
    $installments = [
        new Installment(10000, 3333.33, 20, 20, today()),
        new Installment(10000, 3333.33, 20, 20, today()),
        new Installment(10000, 3333.33, 20, 20, today()),
    ];

    $correctedInstallments = (new class {
        use CanCorrectInstallments;
    })->correctInstallments($installments, $requestedAmount);

    expect($correctedInstallments)->toBeArray()
        ->and($correctedInstallments)->toHaveCount(3)
        ->and(collect($correctedInstallments)->sum('principal'))->toBe($requestedAmount)
        ->and($correctedInstallments[0]->principal)->toBe(3333.34);
});
