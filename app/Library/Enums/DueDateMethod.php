<?php

namespace App\Library\Enums;

enum DueDateMethod: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        'AnyDate' => 'Any date',
        'EndOfMonth' => 'End of month',
        'StartOfMonth' => 'Start of month',
    ];

    case AnyDate = "Any date";
    case EndOfMonth = "End of month";
    case StartOfMonth = "Start of month";
}
