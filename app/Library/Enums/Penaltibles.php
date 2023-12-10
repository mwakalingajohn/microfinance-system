<?php

namespace App\Library\Enums;

enum Penaltibles: string implements ShouldReturnValues
{
    use ReturnsValues;

    public const data = [
        "RemainingInstallment" => "Remaining Installment",
        "RemainingPrincipal" => "Remaining Principal",
        "RemainingInterest" => "Remaining Interest",
        "RemainingPenalty" => "Remaining Penalty",
        "RemainingCharges" => "Remaining Charges",
        "InitialInstallment" => "Initial Installment",
        "InitialPrincipal" => "Initial Principal",
        "InitialInterest" => "Initial Interest",
        "InitialPenalty" => "Initial Penalty",
        "InitialCharges" => "Initial Charges",
    ];

    case RemainingInstallment = "Remaining Installment";
    case RemainingPrincipal = "Remaining Principal";
    case RemainingInterest = "Remaining Interest";
    case RemainingPenalty = "Remaining Penalty";
    case RemainingCharges = "Remaining Charges";
    case InitialInstallment = "Initial Installment";
    case InitialPrincipal = "Initial Principal";
    case InitialInterest = "Initial Interest";
    case InitialPenalty = "Initial Penalty";
    case InitialCharges = "Initial Charges";
}
