<?php

namespace App\Library\Enums;

enum MaritalStatus:string implements ShouldReturnValues
{
    use ReturnsValues;

    case Married = "Married";
    case Divorced = "Divorced";
    case Single = "Single";
    case Widowed = "Widowed";
}
