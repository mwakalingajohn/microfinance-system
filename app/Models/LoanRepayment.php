<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
        'balance_after_repayment',
        'balance_before_repayment'
    ];

    /**
     * Get the borrower that owns the LoanRepayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(Borower::class);
    }

    /**
     * Get the organiation that owns the LoanRepayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the loanProduct that owns the LoanRepayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }

    /**
     * Get the loan that owns the LoanRepayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
