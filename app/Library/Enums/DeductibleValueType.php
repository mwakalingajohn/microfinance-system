<?php

namespace App\Library\Enums;

enum DeductibleValueType:string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "Fixed" => "Fixed",
        "Percentage" => "Percentage",
    ];

    case Fixed = "Fixed";
    case Percentage = "Percentage";
}
