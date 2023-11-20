<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDisbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        "loan_application_id",
        "loan_id",
        "disbursed_by",
        'method',
        'amount',
        'disbursed_on',
        'comment',
        'status',
    ];
}
