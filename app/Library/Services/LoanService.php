<?php

namespace App\Library\Services;

use App\Library\DTOs\InternalResponse;
use App\Library\Handlers\ProcessLoanApplication\LoanApplicationHandler;
use App\Library\Handlers\ProcessLoanRepayment\LoanRepaymentHandler;
use App\Library\Traits\HasInternalResponse;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;

class LoanService
{
    use HasInternalResponse;

    public function disburse(LoanApplication $loanApplication, array $data): InternalResponse
    {
        $loanApplicationHandler = new LoanApplicationHandler(
            $loanApplication,
            $data
        );
        return $loanApplicationHandler->handle();
    }

    public function repay(Loan $loan, array $data): InternalResponse
    {
        $data["amount"] = sanitizeMoney($data["amount"]);
        $loanRepayment = LoanRepayment::create([
            'loan_officer_id' => $loan->loan_officer_id,
            'organisation_id' => $loan->organisation_id,
            'borrower_id' => $loan->borrower_id,
            'loan_product_id' => $loan->loan_product_id,
            'loan_id' => $loan->id,
            'amount' => $data["amount"],
            'repayment_date' => $data["repayment_date"],
            'proof_of_payment' => $data["proof_of_payment"]
        ]);
        $loanRepaymentHandler = new LoanRepaymentHandler(
            $loan,
            $data,
            $loanRepayment
        );
        return $loanRepaymentHandler->handle();
    }
}
