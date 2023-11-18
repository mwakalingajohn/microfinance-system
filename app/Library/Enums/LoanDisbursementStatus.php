<?php

namespace App\Library\Enums;

enum LoanDisbursementStatus: string implements ShouldReturnValues
{
    use ReturnsValues;
    case Pending = 'pending';
    case Successful = 'successful';
    case Failed = 'failed';

}
