<?php

namespace Tests\Library\Traits;

use App\Library\DTOs\Repayment;
use App\Library\Traits\HandlesLoanRepaymentDetail;
use App\Models\LoanProduct;
use App\Models\LoanRepaymentDetail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

uses(\Tests\TestCase::class, RefreshDatabase::class);

it('creates loan repayment detail', function () {

    $loanProduct = LoanProduct::factory()->create();

    $repayment = new Repayment(
        loanRepaymentData: [
            "repayment_date" => "2021-09-01"
        ],
        loanProduct: $loanProduct,
        deductionBalance: 0,
        installment: new \App\Models\LoanInstallment(),
        loanRepayment: new \App\Models\LoanRepayment()
    );

    $loanRepaymentDetail = (new class {
        use HandlesLoanRepaymentDetail;
    })->createLoanRepaymentDetail($repayment);

    expect($loanRepaymentDetail)->toBeInstanceOf(LoanRepaymentDetail::class)
        ->and($loanRepaymentDetail->loan_officer_id)->toBeNull()
        ->and($loanRepaymentDetail->loan_officer_name)->toBeNull()
        ->and($loanRepaymentDetail->borrower_id)->toBeNull()
        ->and($loanRepaymentDetail->borrower_name)->toBeNull()
        ->and($loanRepaymentDetail->loan_product_id)->toBeNull()
        ->and($loanRepaymentDetail->loan_product_name)->toBeNull()
        ->and($loanRepaymentDetail->organisation_id)->toBeNull()
        ->and($loanRepaymentDetail->organisation_name)->toBeNull()
        ->and($loanRepaymentDetail->loan_id)->toBeNull()
        ->and($loanRepaymentDetail->loan_installment_id)->toBeNull()
        ->and($loanRepaymentDetail->loan_repayment_id)->toBeNull()
        ->and($loanRepaymentDetail->repayment_date)->toBeInstanceOf(Carbon::class);
});

