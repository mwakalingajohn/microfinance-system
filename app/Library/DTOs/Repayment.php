<?php

namespace App\Library\DTOs;

use App\Models\LoanInstallment;
use App\Models\LoanProduct;
use App\Models\LoanRepayment;
use App\Models\LoanRepaymentDetail;

class Repayment
{
    public function __construct(
        public array $loanRepaymentData,
        public LoanProduct $loanProduct,
        public float $deductionBalance,
        public LoanInstallment $installment,
        public LoanRepayment $loanRepayment
    ) {
    }

    public function getInstallment()
    {
        return $this->installment;
    }

    public function getLoanRepayment(){
        return $this->loanRepayment;
    }

    public function getDeductionBalance(){
        return $this->deductionBalance;
    }

    public function getLoanProduct(){
        return $this->loanProduct;
    }

    public function getLoanRepaymentData(){
        return $this->loanRepaymentData;
    }

    public function setInstallment(LoanInstallment $installment)
    {
        $this->installment = $installment;
    }

    public function setLoanRepayment(LoanRepayment $loanRepayment){
        $this->loanRepayment = $loanRepayment;
    }

    public function setDeductionBalance(float $deductionBalance): void
    {
        $this->deductionBalance = $deductionBalance;
    }

    public function setLoanProduct(LoanProduct $loanProduct){
        $this->loanProduct = $loanProduct;
    }
}
