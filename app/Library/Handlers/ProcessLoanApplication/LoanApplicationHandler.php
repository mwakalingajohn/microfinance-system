<?php

namespace App\Library\Handlers\ProcessLoanApplication;

use App\Events\LoanDisbursed;
use App\Events\Loans\LoanDisbursing;
use App\Events\Loans\LoanStored;
use App\Events\Loans\LoanStoring;
use App\Events\Loans\LoanValidated;
use App\Events\Loans\LoanValidating;
use App\Library\DTOs\InternalResponse;
use App\Library\Enums\LoanApplicationStatus;
use App\Library\Enums\LoanDisbursementStatus;
use App\Library\Enums\LoanInstallmentStatus;
use App\Library\Enums\LoanStatus;
use App\Library\Handlers\ProcessLoanApplication\Calculators\CalculateCharges;
use App\Library\Handlers\ProcessLoanApplication\Calculators\CalculateInstallments;
use App\Library\Handlers\ProcessLoanApplication\Calculators\LoanCalculation;
use App\Library\Handlers\ProcessLoanApplication\Validators\ItHasNotBeenProcessed;
use App\Library\Handlers\ProcessLoanApplication\Validators\ItIsFullyApproved;
use App\Library\Traits\HasInternalResponse;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Pipeline;
use Throwable;

class LoanApplicationHandler
{
    use HasInternalResponse;

    public function __construct(
        public LoanApplication $loanApplication,
        public array $loanDisbursementData,
        public bool $shouldStore = true,
        public bool $shouldDisburse = true
    ) {
    }

    /**
     * Hanlde the request
     *
     * @return InternalResponse
     */
    public function handle()
    {
        return rescue(function () {

            LoanValidating::dispatch(
                $this->loanApplication,
                $this->loanDisbursementData
            );

            $this->validate();

            LoanValidated::dispatch(
                $this->loanApplication,
                $this->loanDisbursementData
            );

            $loanCalculation = $this->calculate();

            DB::transaction(function () use (&$loanCalculation) {
                if ($this->shouldStore) {
                    LoanStoring::dispatch($loanCalculation);

                    $loan = $this->store($loanCalculation);

                    LoanStored::dispatch($loanCalculation);
                }

                if ($this->shouldDisburse) {

                    LoanDisbursing::dispatch($loanCalculation);

                    $this->disburse($loan, $loanCalculation);

                    LoanDisbursed::dispatch($loanCalculation);
                }
            });

            return $this->setResponse(
                success: true,
                message: "Loan application successful",
                data: [
                    "loanCalculation" => $loanCalculation,
                    "loan" => $loanCalculation->loan
                ]
            );
        }, function (Throwable $throwable) {
            return $this->failed($throwable);
        });
    }

    /**
     * Validate the loan
     *
     * @return void
     */
    private function validate()
    {
        $validators = [
            ItIsFullyApproved::class,
            ItHasNotBeenProcessed::class
        ];

        Pipeline::send($this->loanApplication)
            ->through($validators)
            ->then(fn (LoanApplication $loanApplication) => $loanApplication);
    }

    /**
     * Calculate the loan
     *
     * @return LoanCalculation
     */
    private function calculate(): LoanCalculation
    {
        $loanCalculation = new LoanCalculation(
            loanApplication: $this->loanApplication,
            data: $this->loanDisbursementData
        );

        $calculators = [
            CalculateInstallments::class,
            CalculateCharges::class,
        ];

        return Pipeline::send($loanCalculation)
            ->through($calculators)
            ->then(fn (LoanCalculation $loanCalculation) => $loanCalculation);
    }

    /**
     * Store the loan
     *
     * @return Loan
     */
    private function store(LoanCalculation $loanCalculation): Loan
    {
        $loanApplication = $this->loanApplication;
        $borrower = $loanApplication->borrower;
        $loanProduct = $loanApplication->loanProduct;
        $organisation = $borrower->organisation;
        $loanOfficer = $loanApplication->loanOfficer;

        $principal = $loanApplication->amount;
        $interest = collect($loanCalculation->loanInstallments)->sum("interest");
        $charges = collect($loanCalculation->loanCharges)->sum("amount");
        $total_charges = $interest + $charges;
        $total_loan = $principal + $total_charges;

        // save the loan to DB
        $loan = Loan::create([
            "loan_application_id" => $loanApplication->id,
            "loan_officer_id" => $loanOfficer->id,
            "loan_officer_name" => $loanOfficer->name,
            "borrower_id" => $borrower->id,
            "borrower_name" => $borrower->name,
            "loan_product_id" => $loanProduct->id,
            "loan_product_name" => $loanProduct->label,
            "interest_rate" => $loanApplication->interest,
            "organisation_id" => $organisation?->id,
            "organisation_name" => $organisation?->name,
            "number_of_installments" => $loanApplication->number_of_installments,
            "principal" => $principal,
            "interest" => $interest,
            "charges" => $charges,
            "total_charges" => $total_charges,
            "total_loan" => $total_loan,
            "status" => LoanStatus::Active->value,
        ]);

        // save the installments
        foreach ($loanCalculation->loanInstallments as $installment) {
            $_installment = $loan->installments()->create([
                "loan_officer_id" => $loanOfficer->id,
                "loan_officer_name" => $loanOfficer->name,
                "borrower_id" => $borrower->id,
                "borrower_name" => $borrower->name,
                "loan_product_id" => $loanProduct->id,
                "loan_product_name" => $loanProduct->label,
                "interest_rate" => $loanApplication->interest,
                "organisation_id" => $organisation?->id,
                "organisation_name" => $organisation?->name,
                "loan_balance" => $installment->loanBalance,
                "principal" => $installment->principal,
                "interest" => $installment->interest,
                "charges" => $installment->charges,
                "installment" => $installment->installment,
                "penalty" => 0,
                "remaining_principal" => $installment->principal,
                "remaining_interest" => $installment->interest,
                "remaining_charges" => $installment->charges,
                "remaining_penalty" => 0,
                "remaining_installment" => $installment->installment,
                "due_date" => $installment->dueDate,
                "status" => LoanInstallmentStatus::Unpaid->value
            ]);

            foreach ($installment->installmentCharges as $charge) {
                $_installment->addedCharges()->create([
                    "charge_id" => $charge?->id,
                    "loan_officer_id" => $loanOfficer?->id,
                    "organisation_id" => $organisation?->id,
                    "borrower_id" => $borrower->id,
                    "loan_product_id" => $loanProduct->id,
                    "loan_id" => $loan->id,
                    "label" => $charge->label,
                    "on" => $charge->on,
                    "type" => $charge->type,
                    "of" => $charge->of,
                    "value" => $charge->value,
                    "amount" => $charge->chargedAmount,
                    "remaining_amount" => $charge->chargedAmount
                ]);
            }
        }

        // save the loan charges for each loan and loan installment to the DB
        foreach ($loanCalculation->loanCharges as $charge) {
            $loan->loanCharges()->create([
                "charge_id" => $charge->id,
                "label" => $charge->label,
                "on" => $charge->on,
                "type" => $charge->type,
                "of" => $charge->of,
                "value" => $charge->value,
                "amount" => $charge->amount
            ]);
        }

        // update the final loan application status
        $this->loanApplication->update([
            "status" => LoanApplicationStatus::Disbursed,
            "message" => "Loan disbursed successfully",
            "loan_id" => $loan->id
        ]);

        $loanCalculation->loan = $loan;
        return $loan;
    }

    /**
     * Disburse the amount
     *
     * @return void
     */
    private function disburse(Loan $loan, LoanCalculation $loanCalculation)
    {
        $data = $loanCalculation->data;
        $disbursementAmount = $loanCalculation->disbursementAmount;

        $loan->disbursements()->create([
            "loan_application_id" => $loan->loan_application_id,
            "disbursed_by" => Auth::id(),
            'method' => $data['method'],
            'amount' => $disbursementAmount,
            'disbursed_on' => $data['disbursed_on'],
            'comment' => null,
            'status' => LoanDisbursementStatus::Successful->value,
        ]);

        // TODO
        // implement some other disbursement logic here
    }

    /**
     * Process the failed loan application
     *
     * @param Throwable $th
     * @return void
     */
    private function failed(Throwable $th)
    {
        if ($this->shouldStore) {
            $this->loanApplication->update([
                "status" => LoanApplicationStatus::Failed,
                "message" => $th->getMessage()
            ]);
        }

        return $this->setResponse(
            success: false,
            message: "Loan application failed! Error: " . $th->getMessage()
        );
    }
}
