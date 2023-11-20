<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        "loan_id",
        "loan_officer_id",
        "loan_officer_name",
        "borrower_id",
        "borrower_name",
        "loan_product_id",
        "loan_product_name",
        "interest_rate",
        "organisation_id",
        "organisation_name",
        "loan_balance",
        "principal",
        "interest",
        "charges",
        "installment",
        "penalty",
        "remaining_principal",
        "remaining_interest",
        "remaining_charges",
        "remaining_penalty",
        "remaining_installment",
        "due_date",
    ];

    protected $casts = [
        "due_date" => "datetime"
    ];

    /**
     * Get the loan that owns the LoanInstallment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
