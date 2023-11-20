<?php

namespace App\Library\Enums;

enum LoanDisbursementMethod: string implements ShouldReturnValues
{

    use ReturnsValues;

    case Cash = "cash";
    case Cheque = "cheque";
    case BankTransfer = "bank_transfer";
    case MobileTransfer = "mobile_transfer";
}
