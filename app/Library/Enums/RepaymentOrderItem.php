<?php

namespace App\Library\Enums;

enum RepaymentOrderItem: string implements ShouldReturnValues
{
    use ReturnsValues;

    case Principal = "principal";
    case Interest = "interest";
    case Charges = "charges";
    case Penalties = "penalties";
}
