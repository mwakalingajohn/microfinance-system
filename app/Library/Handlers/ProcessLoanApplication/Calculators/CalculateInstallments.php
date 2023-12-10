<?php

namespace App\Library\Handlers\ProcessLoanApplication\Calculators;

use App\Library\DTOs\Installment;
use App\Library\Enums\DueDateMethod;
use App\Library\Enums\InterestPeriod;
use App\Library\Enums\LoanCalculationMethod;
use App\Library\Traits\CanCalculateDueDates;
use App\Library\Traits\CanCalculateEMI;
use App\Library\Traits\CanConvertTimePeriod;
use App\Library\Traits\CanCorrectInstallments;
use App\Models\LoanApplication;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Fluent;

class CalculateInstallments
{
    use CanCalculateDueDates;
    use CanCalculateEMI;
    use CanConvertTimePeriod;
    use CanCorrectInstallments;

    protected ?LoanApplication $loanApplication;
    protected ?Fluent $data;

    public function __invoke(LoanCalculation $loanCalculation, Closure $next)
    {
        $this->loanApplication = $loanCalculation->loanApplication;
        $this->data = new Fluent($loanCalculation->data);

        $installments = match ($this->loanApplication->loan_product_details->calculation_method) {
            LoanCalculationMethod::Flat->value => $this->flat(),
            LoanCalculationMethod::ReducingBalanceEqualInstallments->value => $this->reducingBalanceEqualInstallments(),
            default => []
        };
        $loanCalculation->loanInstallments = $installments;
        $loanCalculation->installments = $installments;
        $loanCalculation->installmentBeforeCharges = collect($installments)->last()->installment;
        $loanCalculation->disbursementAmount = $loanCalculation->loanApplication->amount;
        $loanCalculation->principal = $loanCalculation->loanApplication->amount;
        
        return $next($loanCalculation);
    }

    /**
     * Calculate reducing balance equal installments
     *
     * @return array<Installment>
     */
    private function flat()
    {
        $interest = $this->loanApplication->interest;
        $amount = $this->loanApplication->amount;
        $installments = [];
        $loanProductDetails = $this->loanApplication->loan_product_details;

        $numberOfInstallments = $this->loanApplication->number_of_installments;
        $startDate = $this->data->disbursed_on;

        $loan_balance = $amount;
        $principal = $amount / $numberOfInstallments;
        $interest /= 100;
        $interest = $this->convert(
            InterestPeriod::from($loanProductDetails->interest_period),
            InterestPeriod::from($loanProductDetails->repayment_period),
            $interest
        );

        for ($i = 1; $i <= $numberOfInstallments; $i++) {

            $installment_interest = $principal * $interest;
            $dueDate = $this->getDueDate(
                $i,
                $startDate,
                InterestPeriod::from($loanProductDetails->repayment_period),
                DueDateMethod::from($loanProductDetails->due_date_method)
            );

            $installments[] = new Installment(
                loanBalance: round($loan_balance, 2),
                principal: round($principal, 2),
                interest: round($installment_interest, 2),
                installment: round($principal + $installment_interest, 2),
                dueDate: $dueDate,
            );
            $loan_balance = $loan_balance - $principal;
        }

        // correct installments for exceed or reduced amount due to division
        $installments = $this->correctInstallments($installments, $amount);

        return $installments;
    }

    /**
     * Calculate reducing balance equal installments
     *
     * @return array<Installment>
     */
    private function reducingBalanceEqualInstallments()
    {
        $interest = $this->loanApplication->interest;
        $amount = $this->loanApplication->amount;
        $installments = [];
        $loanProductDetails = $this->loanApplication->loan_product_details;

        $numberOfInstallments = $this->loanApplication->number_of_installments;
        $startDate = Carbon::parse($this->data->disbursed_on);

        $loan_balance = $amount;
        $interest /= 100;
        $interest = $this->convert(
            InterestPeriod::from($loanProductDetails->interest_period),
            InterestPeriod::from($loanProductDetails->repayment_period),
            $interest
        );
        $emi = $this->getEMIEqualInstallments($interest, $numberOfInstallments, $amount);

        for ($i = 1; $i <= $numberOfInstallments; $i++) {
            $installment_interest = $loan_balance * $interest;
            $installment_principal = $emi - $installment_interest;
            $dueDate = $this->getDueDate(
                $i,
                $startDate,
                InterestPeriod::from($loanProductDetails->repayment_period),
                DueDateMethod::from($loanProductDetails->due_date_method)
            );

            $installments[] = new Installment(
                loanBalance: round($loan_balance, 2),
                principal: round($emi - $installment_interest, 2),
                interest: round($installment_interest, 2),
                installment: round($emi, 2),
                dueDate: $dueDate
            );

            $loan_balance = $loan_balance - $installment_principal;
        }

        // correct installments for exceed or reduced amount due to division
        $installments = $this->correctInstallments($installments, $amount);

        return $installments;
    }

    private function reducingBalanceEqualPrincipal()
    {
    }
}
