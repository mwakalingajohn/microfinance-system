<?php

namespace App\Library\Enums;

enum InterestPeriod: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        'Daily' => 'Daily',
        'Weekly' => 'Weekly',
        'Monthly' => 'Monthly',
        'Yearly' => 'Yearly',
    ];

    case Daily = 'Daily';
    case Weekly = 'Weekly';
    case Monthly = 'Monthly';
    case Yearly = 'Yearly';

    public function inPlural()
    {
        return match ($this) {
            self::Daily => 'Days',
            self::Weekly => 'Weeks',
            self::Monthly => 'Months',
            self::Yearly => 'Years',
            default => null
        };
    }
}
