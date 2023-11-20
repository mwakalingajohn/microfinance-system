<?php

namespace App\Library\Handlers\ProcessLoanApplication\Calculators;

use App\Library\DTOs\Charge;
use App\Library\Enums\CanConvertTimePeriod;
use App\Library\Enums\DeductibleValueType;
use App\Library\Enums\LoanChargeDestination;
use App\Library\Enums\LoanChargeSource;
use App\Library\Traits\CanCalculateDueDates;
use App\Library\Traits\CanCalculateEMI;
use App\Models\LoanApplication;
use App\Models\LoanCharge;
use Closure;
use Illuminate\Support\Fluent;

class CalculateCharges
{
    protected ?LoanApplication $loanApplication;
    protected ?Fluent $data;

    public function __invoke(LoanCalculation $loanCalculation, Closure $next)
    {
        $this->loanApplication = $loanCalculation->loanApplication;
        $this->data = new Fluent($loanCalculation->data);

        $loanCalculation->loanCharges = $this->calculate($loanCalculation);

        $loanCalculation = $this->applyCharges($loanCalculation);
        
        return $next($loanCalculation);
    }

    public function applyCharges(LoanCalculation $loanCalculation): LoanCalculation
    {
        $loanCharges = $loanCalculation->loanCharges;
        $installmentCount = count($loanCalculation->installments);

        foreach ($loanCharges as $charge) {
            $charge = new Fluent($charge);

            info("Charge on:" . $charge->on);
            switch ($charge->on) {
                case LoanChargeDestination::FirstInstallment->value:
                    $loanCalculation->loanInstallments = collect($loanCalculation->loanInstallments)
                        ->map(function ($installment, $index) use ($charge) {
                            if ($index == 0) {
                                $installment->charges += $charge->amount;
                                $installment->installment += $charge->amount;
                            }
                            return $installment;
                        })->toArray();
                    break;
                case LoanChargeDestination::LastInstallment->value:
                    $loanCalculation->loanInstallments = collect($loanCalculation->loanInstallments)
                        ->map(function ($installment, $index) use ($charge, $installmentCount) {
                            if ($index == ($installmentCount - 1)) {
                                $installment->charges += $charge->amount;
                                $installment->installment += $charge->amount;
                            }
                            return $installment;
                        })->toArray();
                    break;
                case LoanChargeDestination::DisbursedAmount->value:
                    $loanCalculation->disbursementAmount = $loanCalculation->disbursementAmount - $charge->amount;
                    break;
                case LoanChargeDestination::DividedInEachInstallment->value:
                    $chargeAmount = $charge->amount / count($loanCalculation->installments);
                    $loanCalculation->loanInstallments = collect($loanCalculation->loanInstallments)
                        ->map(function ($installment) use ($chargeAmount) {
                            $installment->charges += $chargeAmount;
                            $installment->installment += $chargeAmount;
                            return $installment;
                        })->toArray();
                    break;

                default:
                    info("failed charge", [$charge]);
                    break;
            }
        }
        return $loanCalculation;
    }


    /**
     * Calculate the charges
     *
     * @param array $charges
     * @param LoanCalculation $loanCalculation
     * @return array<Charge>
     */
    public function calculate(LoanCalculation $loanCalculation): array
    {
        $charges = $loanCalculation->loanApplication->loan_product_details->loanCharges;
        $_charges = [];

        foreach ($charges as $charge) {

            $amount = match ($charge->type) {
                DeductibleValueType::Fixed->value => $charge->value,
                DeductibleValueType::Percentage->value =>  match ($charge->of) {
                    LoanChargeSource::Installment->value => ($charge->value / 100) * $loanCalculation->disbursementAmount,
                    LoanChargeSource::Principal->value => ($charge->value / 100) * $loanCalculation->principal,
                    default => 0
                }
            };

            $_charges[] = new Charge(
                label: $charge->label,
                on: $charge->on,
                type: $charge->type,
                of: $charge->of ?? "",
                value: $charge->value,
                amount: (float)$amount
            );
        }

        return $_charges;
    }
}
