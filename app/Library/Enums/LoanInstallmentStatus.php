<?php

namespace App\Library\Enums;

enum LoanInstallmentStatus: string implements ShouldReturnValues
{
    use ReturnsValues;

    case Paid = "paid";
    case Unpaid = "unpaid";
    case Partial = "partial";
}
