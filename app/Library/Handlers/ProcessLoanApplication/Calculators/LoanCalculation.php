<?php

namespace App\Library\Handlers\ProcessLoanApplication\Calculators;

use App\Library\DTOs\Installment;
use App\Models\LoanApplication;
use Illuminate\Database\Eloquent\Model;

class LoanCalculation extends Model
{
    /**
     * Loan calculation object
     *
     * @param LoanApplication $loanApplication
     * @param array|null<Installment> $loanInstallments
     * @param array|null<Installment> $installments
     * @param array|null<Charge> $loanCharges
     * @param array|null $data
     */
    public function __construct(
        public ?LoanApplication $loanApplication = null,
        public ?array $loanInstallments = [],
        public ?array $loanCharges = [],
        public ?array $installments = [],
        public ?array $data = [],
        public ?float $disbursementAmount = 0,
        public ?float $installmentBeforeCharges = 0,
        public ?float $principal = 0
    ) {
    }
}
