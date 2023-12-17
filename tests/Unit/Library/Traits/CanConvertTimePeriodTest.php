<?php

namespace Tests\Library\Traits;

use App\Library\Enums\InterestPeriod;
use App\Library\Traits\CanConvertTimePeriod;
use PHPUnit\Framework\TestCase;

it('converts time periods', function () {
    $period = (new class {
        use CanConvertTimePeriod;
    });

    $value = 10.0;

    expect($period->convert(
        InterestPeriod::Yearly,
        InterestPeriod::Yearly,
        $value
    ))->toBe($value)
        ->and($period->convert(
            InterestPeriod::Yearly,
            InterestPeriod::Monthly,
            $value
        ))->toBe(round($value / 12, 5))
        ->and($period->convert(
            InterestPeriod::Yearly,
            InterestPeriod::Weekly,
            $value
        ))->toBe(round($value / 52, 5))
        ->and($period->convert(
            InterestPeriod::Yearly,
            InterestPeriod::Daily,
            $value
        ))->toBe(round($value / 365, 5))
        ->and($period->convert(
            InterestPeriod::Monthly,
            InterestPeriod::Yearly,
            $value
        ))->toBe(round($value * 12, 5))
        ->and($period->convert(
            InterestPeriod::Monthly,
            InterestPeriod::Monthly,
            $value
        ))->toBe($value)
        ->and($period->convert(
            InterestPeriod::Monthly,
            InterestPeriod::Weekly,
            $value
        ))->toBe(round($value / 4.3, 5))
        ->and($period->convert(
            InterestPeriod::Monthly,
            InterestPeriod::Daily,
            $value
        ))->toBe(round($value / 30.4, 5))
        ->and($period->convert(
            InterestPeriod::Weekly,
            InterestPeriod::Yearly,
            $value
        ))->toBe(round($value * 52, 5))
        ->and($period->convert(
            InterestPeriod::Weekly,
            InterestPeriod::Monthly,
            $value
        ))->toBe(round($value * 4, 2))
        ->and($period->convert(
            InterestPeriod::Weekly,
            InterestPeriod::Weekly,
            $value
        ))->toBe(round($value, 2))
        ->and($period->convert(
            InterestPeriod::Weekly,
            InterestPeriod::Daily,
            $value
        ))->toBe(round($value / 7, 5))
        ->and($period->convert(
            InterestPeriod::Daily,
            InterestPeriod::Yearly,
            $value
        ))->toBe(round($value * 365, 5))
        ->and($period->convert(
            InterestPeriod::Daily,
            InterestPeriod::Monthly,
            $value
        ))->toBe(round($value * 30.42, 5))
        ->and($period->convert(
            InterestPeriod::Daily,
            InterestPeriod::Weekly,
            $value
        ))->toBe(round($value * 7.02, 5))
        ->and($period->convert(
            InterestPeriod::Daily,
            InterestPeriod::Daily,
            $value
        ))->toBe(round($value, 3));

});
