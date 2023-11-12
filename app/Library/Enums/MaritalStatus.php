<?php

namespace App\Library\Enums;

enum MaritalStatus:string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data= [
        "Married" => "Married",
        "Divorced" => "Divorced",
        "Single" => "Single",
        "Widowed" => "Widowed",
    ];
    case Married = "Married";
    case Divorced = "Divorced";
    case Single = "Single";
    case Widowed = "Widowed";
}
