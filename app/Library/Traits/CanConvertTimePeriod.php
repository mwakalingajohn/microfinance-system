<?php

namespace App\Library\Traits;

use App\Library\Enums\InterestPeriod;

trait CanConvertTimePeriod
{
    public function convert(
        InterestPeriod $originCycle,
        InterestPeriod $targetCycle,
        float $value
    ) {
        if ($originCycle == InterestPeriod::Yearly) {
            if ($targetCycle == InterestPeriod::Yearly) {
                $period = round($value, 5);
            }
            if ($targetCycle == InterestPeriod::Monthly) {
                $period = round($value / 12, 5);
            }
            if ($targetCycle == InterestPeriod::Weekly) {
                $period = round($value / 52, 5);
            }
            if ($targetCycle == InterestPeriod::Daily) {
                $period = round($value / 365, 5);
            }
        }
        if ($originCycle == InterestPeriod::Monthly) {
            if ($targetCycle == InterestPeriod::Yearly) {
                $period = round($value * 12, 5);
            }
            if ($targetCycle == InterestPeriod::Monthly) {
                $period = round($value, 5);
            }
            if ($targetCycle == InterestPeriod::Weekly) {
                $period = round($value / 4.3, 5);
            }
            if ($targetCycle == InterestPeriod::Daily) {
                $period = round($value / 30.4, 5);
            }
        }
        if ($originCycle == InterestPeriod::Weekly) {
            if ($targetCycle == InterestPeriod::Yearly) {
                $period = round($value * 52, 5);
            }
            if ($targetCycle == InterestPeriod::Monthly) {
                $period = round($value * 4, 2);
            }
            if ($targetCycle == InterestPeriod::Weekly) {
                $period = round($value * 1, 2);
            }
            if ($targetCycle == InterestPeriod::Daily) {
                $period = round($value / 7, 5);
            }
        }
        if ($originCycle == InterestPeriod::Daily) {
            if ($targetCycle == InterestPeriod::Yearly) {
                $period = round($value * 365, 5);
            }
            if ($targetCycle == InterestPeriod::Monthly) {
                $period = round($value * 30.42, 5);
            }
            if ($targetCycle == InterestPeriod::Weekly) {
                $period = round($value * 7.02, 5);
            }
            if ($targetCycle == InterestPeriod::Daily) {
                $period = round($value, 3);
            }
        }
        return $period;
    }
}
