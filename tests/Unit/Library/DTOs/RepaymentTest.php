<?php

namespace Tests\Library\DTOs;

use App\Library\DTOs\Repayment;
use App\Models\LoanProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it("can be initiated with and return values values", function () {

    $loanProduct = LoanProduct::factory()->create();

    $repayment = new Repayment(
        loanRepaymentData: [],
        loanProduct: $loanProduct,
        deductionBalance: 0,
        installment: new \App\Models\LoanInstallment(),
        loanRepayment: new \App\Models\LoanRepayment()
    );

    expect($repayment)->toBeInstanceOf(Repayment::class)
        ->and($repayment->getLoanRepaymentData())->toBeArray()
        ->and($repayment->getLoanProduct())->toBeInstanceOf(\App\Models\LoanProduct::class)
        ->and($repayment->getDeductionBalance())->toBeFloat()
        ->and($repayment->getInstallment())->toBeInstanceOf(\App\Models\LoanInstallment::class)
        ->and($repayment->getLoanRepayment())->toBeInstanceOf(\App\Models\LoanRepayment::class);
});

it('can be initiated and values can be set using the setters', function () {

    $loanProduct = LoanProduct::factory()->create();

    $repayment = new Repayment(
        loanRepaymentData: [],
        loanProduct: $loanProduct,
        deductionBalance: 0,
        installment: new \App\Models\LoanInstallment(),
        loanRepayment: new \App\Models\LoanRepayment()
    );

    $repayment->setLoanProduct($loanProduct);
    $repayment->setDeductionBalance(0);
    $repayment->setInstallment(new \App\Models\LoanInstallment());
    $repayment->setLoanRepayment(new \App\Models\LoanRepayment());

    expect($repayment)->toBeInstanceOf(Repayment::class)
        ->and($repayment->getLoanRepaymentData())->toBeArray()
        ->and($repayment->getLoanProduct())->toBeInstanceOf(\App\Models\LoanProduct::class)
        ->and($repayment->getDeductionBalance())->toBeFloat()
        ->and($repayment->getInstallment())->toBeInstanceOf(\App\Models\LoanInstallment::class)
        ->and($repayment->getLoanRepayment())->toBeInstanceOf(\App\Models\LoanRepayment::class);
});
