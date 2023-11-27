<?php

namespace App\Library\Enums;

enum LoanStatus: string implements ShouldReturnValues
{
    use ReturnsValues;

    case Active = "Active";
    case Paid = "Paid";
    case Overdue = "Overdue";
    case Defaulted = "Defaulted";

}
