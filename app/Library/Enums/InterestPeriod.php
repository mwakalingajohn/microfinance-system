<?php

namespace App\Library\Enums;

enum InterestPeriod: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        'Daily' => 'per day',
        'Weekly' => 'per week',
        'Monthly' => 'per month',
    ];

    case Daily = 'per day';
    case Weekly = 'per week';
    case Monthly = 'per month';

}
