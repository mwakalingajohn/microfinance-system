<?php

namespace App\Library\Enums;

enum LoanChargeSource: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "Installment" => "Installment",
        "Principal" => "Principal"
    ];

    case Installment = "Installment";
    case Principal = "Principal";
}
