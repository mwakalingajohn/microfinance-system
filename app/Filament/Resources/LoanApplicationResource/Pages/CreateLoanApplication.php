<?php

namespace App\Filament\Resources\LoanApplicationResource\Pages;

use App\Filament\Resources\LoanApplicationResource;
use App\Models\Borrower;
use App\Models\LoanProduct;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateLoanApplication extends CreateRecord
{
    protected static string $resource = LoanApplicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['loan_officer_id'] = auth()->id();
        $data['loan_product_details'] = LoanProduct::with("loanCharges")->find($data["loan_product_id"])?->toArray();
        $data['borrower_name'] = Borrower::find($data["borrower_id"])?->first_name;
        $data['loan_product_name'] = LoanProduct::find($data["loan_product_id"])?->label;
        $data['amount'] = sanitizeMoney($data["amount"]);
        return $data;
    }


    protected function beforeCreate(): void
    {
        $loanProduct = LoanProduct::find($this->data["loan_product_id"]);
        $amount = sanitizeMoney($this->data["amount"]);
        $interestRate = sanitizeMoney($this->data["interest"]);

        if ($loanProduct->minimum_principal > $amount) {
            $minimumPrincipal = number_format($loanProduct->minimum_principal, 2);
            $amount = number_format(sanitizeMoney($amount), 2);
            $this->fail("The amount {$amount} is less than the product required minimum principal {$minimumPrincipal}");
        }
        if ($loanProduct->maximum_principal < $amount) {
            $maximumPrincipal = number_format($loanProduct->maximum_principal, 2);
            $amount = number_format(sanitizeMoney($amount), 2);
            $this->fail("The amount {$amount} is greater than the product required maximum principal {$maximumPrincipal}");
        }

        if ($loanProduct->minimum_interest_rate > $interestRate) {
            $minimumInterestRate = number_format($loanProduct->minimum_interest_rate, 2);
            $interestRate = number_format(sanitizeMoney($interestRate), 2);
            $this->fail("The interest rate {$interestRate} is less than the product required minimum interest rate {$minimumInterestRate}");
        }
        if ($loanProduct->maximum_interest_rate < $interestRate) {
            $maximumInterestRate = number_format($loanProduct->maximum_interest_rate, 2);
            $interestRate = number_format(sanitizeMoney($interestRate), 2);
            $this->fail("The interest rate {$interestRate} is greater than the product required maximum interest rate {$maximumInterestRate}");
        }
    }

    public function fail($message)
    {
        Notification::make()
            ->danger()
            ->title("Validation failed")
            ->body($message)
            ->send();

        $this->halt();
    }
}
