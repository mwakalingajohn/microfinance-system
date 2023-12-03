<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_officer_id',
        'organisation_id',
        'borrower_id',
        'loan_product_id',
        'loan_id',
        'amount',
        'repayment_date',
        'proof_of_payment',
        'status'
    ];
}
