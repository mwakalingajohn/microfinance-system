<?php

namespace App\Library\Enums;

enum PenaltyMethod: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "Fixed" => "Fixed",
        "Compounding" => "Compounding",
    ];

    case Fixed = "Fixed";
    case Compounding = "Compounding";

}
