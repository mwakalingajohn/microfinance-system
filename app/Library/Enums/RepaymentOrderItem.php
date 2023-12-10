<?php

namespace App\Library\Enums;

enum RepaymentOrderItem: string implements ShouldReturnValues
{
    use ReturnsValues;

    case Charges = "charges";
    case Penalties = "penalties";
    case Interest = "interest";
    case Principal = "principal";
}
