<?php

namespace App\Library\Enums;

enum DueDateMethod: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        'AnyDatetime' => 'Any date and time',
        'EndOfPeriod' => 'End of period',
        'StartOfPeriod' => 'Start of period',
    ];

    case AnyDatetime = "AnyDatetime";
    case EndOfPeriod = "EndOfPeriod";
    case StartOfPeriod = "StartOfPeriod";
}
