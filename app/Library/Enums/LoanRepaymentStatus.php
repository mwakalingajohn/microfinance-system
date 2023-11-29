<?php

namespace App\Library\Enums;

enum LoanRepaymentStatus: string implements ShouldReturnValues
{
    use ReturnsValues;

    case Pending = 'pending';
    case Successful = 'successful';
    case Canceled = 'canceled';
    case Failed = 'failed';
}
