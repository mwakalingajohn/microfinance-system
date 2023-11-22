<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        "loan_application_id",
        "loan_officer_id",
        "loan_officer_name",
        "borrower_id",
        "borrower_name",
        "loan_product_id",
        "loan_product_name",
        "interest_rate",
        "organisation_id",
        "organisation_name",
        "number_of_installments",
        "principal",
        "interest",
        "charges",
        "total_charges",
        "total_loan",
        "status",
    ];

    /**
     * Get all of the disbursements for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disbursements(): HasMany
    {
        return $this->hasMany(LoanDisbursement::class);
    }

    /**
     * Get the loanApplication that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    /**
     * Get all of the installments for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class);
    }

    /**
     * Get all of the charges for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function charges(): HasMany
    {
        return $this->hasMany(LoanCharge::class);
    }


    /**
     * Get the borrower that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(Borrower::class);
    }

    /**
     * Get the product that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }
}
