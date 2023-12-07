<?php

namespace App\Models;

use App\Library\DTOs\Installment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCalculation extends Model
{
    use HasFactory;
    use \Sushi\Sushi;

    public const LOAN_CALCULATION_DATA_SESSION_KEY = "loan_calculation_session";

    protected $fillable = [
        'loanBalance',
        'principal',
        'interest',
        'installment',
        'due_date',
        'charges',
    ];

    protected $schema = [
        'loanBalance' => "float",
        'principal' => "float",
        'interest' => "float",
        'installment' => "float",
        'due_date' => "float",
        'charges' => "float",
    ];

    public function getRows()
    {
        $loanCalculationData = session(self::LOAN_CALCULATION_DATA_SESSION_KEY, null);
        if ($loanCalculationData) {
            return collect($loanCalculationData->loanInstallments)
                ->map(fn (Installment $installment) => $installment->toArray(false))
                ->toArray();
        }
        return [];
    }
}
