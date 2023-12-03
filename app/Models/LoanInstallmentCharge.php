<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanInstallmentCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        "loan_installment_id",
        "charge_id",
        "loan_officer_id",
        "organisation_id",
        "borrower_id",
        "loan_product_id",
        "loan_id",
        "label",
        "on",
        "type",
        "of",
        "value",
        "amount",
        "remaining_amount",
    ];
}
