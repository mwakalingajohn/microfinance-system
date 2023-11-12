<?php

namespace App\Library\Enums;

enum LoanCalculationMethod: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        'Flat' => 'Flat',
        'ReducingBalanceEqualInstallments' => 'Reducing balance equal installments',
        'ReducingBalanceEqualPrincipal' => 'Reducing balance equal principal'
    ];

    case Flat = "Flat";
    case ReducingBalanceEqualInstallments = "Reducing balance equal installments";
    case ReducingBalanceEqualPrincipal = "Reducing balance equal principal";
}
