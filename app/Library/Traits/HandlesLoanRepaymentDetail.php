<?php

namespace App\Library\Traits;

use App\Library\DTOs\Repayment;
use App\Models\LoanRepaymentDetail;

trait HandlesLoanRepaymentDetail
{

    /**
     * Create loan repayment detail
     *
     * @param Repayment $repayment
     * @return LoanRepaymentDetail
     */
    public function createLoanRepaymentDetail(Repayment $repayment): LoanRepaymentDetail
    {
        $installment = $repayment->getInstallment();
        $loanRepayment = $repayment->getLoanRepayment();
        $loan = $installment->loan;
        $loanOfficer = $loan->loanOfficer;
        $borrower = $loan->borrower;
        $loanProduct = $loan->loanProduct;
        $organisation = $loan->organisation;
        $repaymentDate = $repayment->getLoanRepaymentData()['repayment_date'];

        return LoanRepaymentDetail::create([
            "loan_officer_id" => $loanOfficer?->id,
            "loan_officer_name" => $loanOfficer?->name,
            "borrower_id" => $borrower?->id,
            "borrower_name" => $borrower?->name,
            "loan_product_id" => $loanProduct?->id,
            "loan_product_name" => $loanProduct?->name,
            "organisation_id" => $organisation?->id,
            "organisation_name" => $organisation?->name,
            "loan_id" => $loan->id,
            "loan_installment_id" => $installment->id,
            "loan_repayment_id" => $loanRepayment->id,
            "repayment_date" => $repaymentDate
        ]);
    }
}
