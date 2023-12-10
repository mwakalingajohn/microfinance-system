<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the loan that owns the LoanDisbursement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the loanApplication that owns the LoanDisbursement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    /**
     * Get the disbursedBy that owns the LoanDisbursement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disbursedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }
}
