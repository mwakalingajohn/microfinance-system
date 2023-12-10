<?php

namespace App\Library\Traits;

use App\Library\Enums\DueDateMethod;
use App\Library\Enums\InterestPeriod;
use App\Library\Enums\LoanCalculationMethod;
use Carbon\Carbon;

trait CanCalculateDueDates
{

    public function getDueDate(
        int $iteration,
        Carbon $initialDate,
        InterestPeriod $period,
        DueDateMethod $dueDateMethod
    ): Carbon {
        $initialDate = $initialDate->clone();
        $dueDate = match ($period) {
            InterestPeriod::Daily => $initialDate->addDays($iteration),
            InterestPeriod::Weekly => $initialDate->addWeeks($iteration),
            InterestPeriod::Monthly => $initialDate->addMonths($iteration),
            InterestPeriod::Yearly => $initialDate->addYears($iteration)
        };

        return match ($dueDateMethod) {
            DueDateMethod::AnyDatetime => $dueDate,
            DueDateMethod::EndOfPeriod => match ($period) {
                InterestPeriod::Daily => $dueDate->endOfDay(),
                InterestPeriod::Weekly => $dueDate->endOfWeek(),
                InterestPeriod::Monthly => $dueDate->endOfMonth(),
                InterestPeriod::Yearly => $dueDate->endOfYear()
            },
            DueDateMethod::StartOfPeriod => match ($period) {
                InterestPeriod::Daily => $dueDate->startOfDay(),
                InterestPeriod::Weekly => $dueDate->startOfWeek(),
                InterestPeriod::Monthly => $dueDate->startOfMonth(),
                InterestPeriod::Yearly => $dueDate->startOfYear()
            }
        };
    }
}
