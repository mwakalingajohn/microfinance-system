<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LoanProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        "repayment_order" => "array"
    ];

    /**
     * The charges that belong to the LoanProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function charges(): BelongsToMany
    {
        return $this->belongsToMany(Charge::class);
    }

    /**
     * The penalties that belong to the LoanProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function penalties(): BelongsToMany
    {
        return $this->belongsToMany(Penalty::class);
    }
}
