<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Contracts\Auditable;

class LoanProduct extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        "label",
        "minimum_principal",
        "maximum_principal",
        "default_interest_rate",
        "minimum_interest_rate",
        "maximum_interest_rate",
        "interest_period",
        "repayment_period",
        "calculation_method",
        "due_date_method",
        "grace_on_interest",
        "repayment_order",
    ];

    protected $casts = [
        "repayment_order" => "array"
    ];

     /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'created_at',
        'updated_at',
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
