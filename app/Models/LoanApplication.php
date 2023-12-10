<?php

namespace App\Models;

use App\Casts\Json;
use EightyNine\Approvals\Models\ApprovableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        "number_of_installments",
        "interest",
        "branch_id",
        "loan_officer_id",
        "loan_id",
        "status",
        "comment"
    ];

    protected $casts = [
        "loan_product_details" => Json::class
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
     * Get the branch that owns the LoanApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
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

    /**
     * Get the loanOfficer that owns the LoanApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the loan associated with the LoanApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loan(): HasOne
    {
        return $this->hasOne(Loan::class);
    }
}
