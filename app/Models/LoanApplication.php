<?php

namespace App\Models;

use EightyNine\Approvals\Models\ApprovableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanApplication extends ApprovableModel
{
    use HasFactory;

    protected $fillable = [
        "borrower_id",
        "borrower_name",
        "loan_product_id",
        "loan_product_name",
        "loan_product_details",
        "amount",
    ];

    /**
     * Get the borrower that owns the LoanApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(Borrower::class);
    }

    /**
     * Get the loanProduct that owns the LoanApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }
}
