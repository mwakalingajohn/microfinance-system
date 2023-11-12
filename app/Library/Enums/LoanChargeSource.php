<?php

namespace App\Library\Enums;

enum LoanChargeSource: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "FirstInstallment" => "First installment",
        "LastInstallment" => "Last Installment",
        "Principal" => "Principal",
    ];

    case FirstInstallment = "First installment";
    case LastInstallment = "Last Installment";
    case Principal = "Principal";

}
