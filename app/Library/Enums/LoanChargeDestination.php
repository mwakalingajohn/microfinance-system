<?php

namespace App\Library\Enums;

enum LoanChargeDestination: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "FirstInstallment" => "First installment",
        "LastInstallment" => "Last Installment",
        "DisbursedAmount" => "Disbursed Amount",
        "DividedInEachInstallment" => "Divided in each installment",
    ];

    case FirstInstallment = "First installment";
    case LastInstallment = "Last Installment";
    case DisbursedAmount = "Disbursed Amount";
    case DividedInEachInstallment = "Divided in each installment";

}
