<?php

namespace App\Library\Enums;

enum Gender: string implements ShouldReturnValues
{

    use ReturnsValues;

    case Male = "male";
    case Female = "female";
    case Other = "other";
}
