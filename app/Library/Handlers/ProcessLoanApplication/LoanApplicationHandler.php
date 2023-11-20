<?php

namespace App\Library\Handlers\ProcessLoanApplication;

use App\Library\DTOs\InternalResponse;
use App\Library\Enums\LoanApplicationStatus;
use App\Library\Handlers\ProcessLoanApplication\Calculators\CalculateCharges;
use App\Library\Handlers\ProcessLoanApplication\Calculators\CalculateInstallments;
use App\Library\Handlers\ProcessLoanApplication\Calculators\LoanCalculation;
use App\Library\Handlers\ProcessLoanApplication\Validators\ItHasNotBeenProcessed;
use App\Library\Handlers\ProcessLoanApplication\Validators\ItIsFullyApproved;
use App\Library\Traits\HasInternalResponse;
use App\Models\Loan;
use App\Models\LoanApplication;
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

            $this->validate();

            $loanCalculation = $this->calculate();

            if ($this->shouldStore) {
                $loan = $this->store($loanCalculation);
            }

            if ($this->shouldDisburse) {
                $this->disburse($loan, $loanCalculation);
            }

            return $this->setResponse(
                success: true,
                message: "Loan application successful",
                data: [
                    "loanCalculation" => $loanCalculation
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
        // save the loan to DB

        // save the loan installments to the DB

        // save the loan charges for each loan and loan installment to the DB
        return new Loan;
    }

    /**
     * Disburse the amount
     *
     * @return void
     */
    private function disburse(Loan $loan, LoanCalculation $loanCalculation)
    {
        // create a loan disbusement record
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
