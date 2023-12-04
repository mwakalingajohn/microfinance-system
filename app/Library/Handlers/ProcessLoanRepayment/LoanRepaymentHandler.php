<?php

namespace App\Library\Handlers\ProcessLoanRepayment;

use App\Library\DTOs\InternalResponse;
use App\Library\DTOs\Repayment;
use App\Library\Enums\LoanInstallmentStatus;
use App\Library\Enums\LoanRepaymentStatus;
use App\Library\Enums\LoanStatus;
use App\Library\Traits\HasInternalResponse;
use App\Library\Traits\HasRepaymentOrder;
use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanProduct;
use App\Models\LoanRepayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Pipeline;
use Throwable;

class LoanRepaymentHandler
{
    use HasInternalResponse;
    use HasRepaymentOrder;

    public function __construct(
        public Loan $loan,
        public array $loanRepaymentData,
        public LoanRepayment $loanRepayment
    ) {
    }

    public function handle(): InternalResponse
    {
        return rescue(function () {

            DB::transaction(function () {

                $loanRepaymentData = $this->loanRepaymentData;
                $loanProduct = $this->loan->loanProduct;
                $amount = $loanRepaymentData["amount"];

                $installments = $this->loan->installments()
                    ->where("status", "!=", LoanInstallmentStatus::Paid->value)
                    ->get();

                foreach ($installments as  $installment) {
                    if ($amount <= 0) break;
                    $repayment = Pipeline::send(new Repayment(
                        loanRepaymentData: $loanRepaymentData,
                        loanProduct: $loanProduct,
                        deductionBalance: $amount,
                        installment: $installment,
                        loanRepayment: $this->loanRepayment
                    ))
                        ->through(
                            $this->mapDeductorsToRepaymentOrder()
                        )
                        ->then(fn (Repayment $repayment) => $repayment);
                    $amount = $repayment->deductionBalance;
                    $this->updateInstallmentStatus($installment);
                }

                $this->loanRepayment->update([
                    "status" => LoanRepaymentStatus::Successful->value
                ]);

                $this->updateLoanStatus($this->loan);
            });

            return $this->setResponse(
                success: true,
                message: "Loan repayment successful",
                data: [
                    "loanRepayment" => $this->loanRepayment,
                    "loan" => $this->loan
                ]
            );
        }, function (Throwable $th) {
            return $this->setResponse(
                success: false,
                message: "Loan repayment failed! Error: " . $th->getMessage()
            );
        });
    }


    /**
     * Get loan product
     *
     * @return LoanProduct|null
     */
    protected function getLoanProduct(): ?LoanProduct
    {
        return $this->loan->loanProduct;
    }

    /**
     * Update installment status
     *
     * @param LoanInstallment $installment
     * @return void
     */
    private function updateInstallmentStatus(LoanInstallment $installment)
    {
        if ($installment->installment == $installment->remaining_installment) {
            $installment->update([
                "status" => LoanInstallmentStatus::Unpaid->value
            ]);
        } else if ($installment->remaining_installment > 0) {
            $installment->update([
                "status" => LoanInstallmentStatus::Partial->value
            ]);
        } else {
            $installment->update([
                "status" => LoanInstallmentStatus::Paid->value
            ]);
        }
    }

    private function updateLoanStatus(Loan $loan)
    {
        if ($loan->installments()->sum("remaining_installment") <= 0) {
            $loan->update([
                "status" => LoanStatus::Paid->value
            ]);
        }
    }
}
