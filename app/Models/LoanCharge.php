<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LoanCharge extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The products that belong to the LoanCharge
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loanProducts(): BelongsToMany
    {
        return $this->belongsToMany(LoanProduct::class);
    }
}
