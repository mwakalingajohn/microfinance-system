<?php

namespace App\Models;

use App\Library\Enums\RepaymentOrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepaymentDetail extends Model
{
    use HasFactory;

    protected $casts = [
        "type" => RepaymentOrderItem::class,
        "repayment_date" => "datetime",
    ];

    protected $fillable = [
        "loan_officer_id",
        "loan_officer_name",
        "borrower_id",
        "borrower_name",
        "loan_product_id",
        "loan_product_name",
        "organisation_id",
        "organisation_name",
        "loan_id",
        "loan_installment_id",
        "loan_repayment_id",
        'type',
        "repayment_date",
        'amount',
    ];
}
