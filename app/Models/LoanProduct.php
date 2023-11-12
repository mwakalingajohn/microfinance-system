<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LoanProduct extends Model
{
    use HasFactory;

    /**
     * The charges that belong to the LoanProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loanCharges(): BelongsToMany
    {
        return $this->belongsToMany(LoanCharge::class);
    }

    /**
     * The penalties that belong to the LoanProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loanPenalties(): BelongsToMany
    {
        return $this->belongsToMany(LoanPenalty::class);
    }
}
