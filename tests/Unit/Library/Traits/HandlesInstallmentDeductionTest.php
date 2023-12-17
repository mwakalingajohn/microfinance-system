<?php

namespace Tests\Library\Traits;

use App\Library\DTOs\Repayment;
use App\Library\Traits\HandlesInstallmentDeduction;
use App\Models\LoanInstallment;
use App\Models\LoanProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('should update installment deduction', function () {
    $class = new class {
        use HandlesInstallmentDeduction;

        public function updateInstallmentDeductions(
            LoanInstallment &$loanInstallment,
            Repayment &$repayment,
            $column
        ) {
            return $this->updateInstallmentDeduction(
                $loanInstallment,
                $repayment,
                $column
            );
        }
    };

    $deductionBalance = 50000;
    $defaultAmount = 10000;
    $totalInstallment = $defaultAmount * 4;

    $loanInstallment = LoanInstallment::factory()->create([
        'remaining_interest' => $defaultAmount,
        'remaining_principal' => $defaultAmount,
        'remaining_installment' => $totalInstallment,
        'remaining_penalty' => $defaultAmount,
        'remaining_charges' => $defaultAmount,
        'charges' => $defaultAmount,
        'penalty' => $defaultAmount,
        'interest' => $defaultAmount,
        'principal' => $defaultAmount,
        'installment' => $totalInstallment,
    ]);

    $loanProduct = LoanProduct::factory()->create();

    $repayment = new Repayment(
        loanRepaymentData: [],
        loanProduct: $loanProduct,
        deductionBalance: $deductionBalance,
        installment: new \App\Models\LoanInstallment(),
        loanRepayment: new \App\Models\LoanRepayment()
    );
    $class->updateInstallmentDeductions(
        $loanInstallment,
        $repayment,
        'remaining_interest'
    );

    expect($loanInstallment->remaining_interest)->toEqual(0)
        ->and($loanInstallment->remaining_principal)->toEqual($defaultAmount)
        ->and($loanInstallment->remaining_installment)->toEqual($totalInstallment - $defaultAmount)
        ->and($loanInstallment->remaining_penalty)->toEqual($defaultAmount)
        ->and($loanInstallment->remaining_charges)->toEqual($defaultAmount)
        ->and($repayment->getDeductionBalance())->toEqual(40000);

    $class->updateInstallmentDeductions(
        $loanInstallment,
        $repayment,
        'remaining_principal'
    );

    expect($loanInstallment->remaining_interest)->toEqual(0)
        ->and($loanInstallment->remaining_principal)->toEqual(0)
        ->and($loanInstallment->remaining_installment)->toEqual($totalInstallment - $defaultAmount * 2)
        ->and($loanInstallment->remaining_penalty)->toEqual($defaultAmount)
        ->and($loanInstallment->remaining_charges)->toEqual($defaultAmount)
        ->and($repayment->getDeductionBalance())->toEqual(30000);

    $class->updateInstallmentDeductions(
        $loanInstallment,
        $repayment,
        'remaining_penalty'
    );

    expect($loanInstallment->remaining_interest)->toEqual(0)
        ->and($loanInstallment->remaining_principal)->toEqual(0)
        ->and($loanInstallment->remaining_installment)->toEqual($totalInstallment - $defaultAmount * 3)
        ->and($loanInstallment->remaining_penalty)->toEqual(0)
        ->and($loanInstallment->remaining_charges)->toEqual($defaultAmount)
        ->and($repayment->getDeductionBalance())->toEqual(20000);

    $class->updateInstallmentDeductions(
        $loanInstallment,
        $repayment,
        'remaining_charges'
    );

    expect($loanInstallment->remaining_interest)->toEqual(0)
        ->and($loanInstallment->remaining_principal)->toEqual(0)
        ->and($loanInstallment->remaining_installment)->toEqual($totalInstallment - $defaultAmount * 4)
        ->and($loanInstallment->remaining_penalty)->toEqual(0)
        ->and($loanInstallment->remaining_charges)->toEqual(0)
        ->and($repayment->getDeductionBalance())->toEqual(10000);
});
