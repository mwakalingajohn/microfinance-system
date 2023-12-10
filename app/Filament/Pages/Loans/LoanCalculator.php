<?php

namespace App\Filament\Pages\Loans;

use App\Library\Enums\InterestPeriod;
use App\Library\Handlers\ProcessLoanApplication\LoanApplicationHandler;
use App\Models\LoanApplication;
use App\Models\LoanCalculation;
use App\Models\LoanProduct;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Fluent;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class LoanCalculator extends Page  implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = "Loans";

    protected static string $view = 'filament.pages.loans.loan-calculator';

    protected ?string $subheading = 'Calculate and see various values based on the loan';

    public ?array $data = [];

    public LoanApplication $loanApplication;

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanCalculation::query())
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('loanBalance')
                    ->money("TZS"),
                TextColumn::make('principal')
                    ->money("TZS")
                    ->summarize(
                        Summarizer::make()
                            ->label('Total')
                            ->using(fn (): string => LoanCalculation::all()->sum("principal"))
                            ->money("TZS")
                    ),
                TextColumn::make('interest')
                    ->money("TZS")
                    ->summarize(
                        Summarizer::make()
                            ->label('Total')
                            ->using(fn (): string => LoanCalculation::all()->sum("interest"))
                            ->money("TZS")
                    ),
                TextColumn::make('charges')
                    ->money("TZS")
                    ->summarize(
                        Summarizer::make()
                            ->label('Total')
                            ->using(fn (): string => LoanCalculation::all()->sum("charges"))
                            ->money("TZS")
                    ),
                TextColumn::make('installment')
                    ->money("TZS")
                    ->summarize(
                        Summarizer::make()
                            ->label('Total')
                            ->using(fn (): string => LoanCalculation::all()->sum("installment"))
                            ->money("TZS")
                    ),
                TextColumn::make('due_date')
                    ->dateTime(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->headerActions([
                ExportAction::make()
            ])
            ->bulkActions([
                // ExportBulkAction::make()
            ]);
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Select::make("loan_product_id")
                    ->relationship(name: "loanProduct", titleAttribute: "label")
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('interest', LoanProduct::find($state)?->default_interest_rate);
                        $set('amount', number_format(LoanProduct::find($state)?->minimum_principal));
                        $set('number_of_installments', 1);
                    })
                    ->live()
                    ->preload(),
                TextInput::make("amount")
                    ->prefix("TZS")
                    ->mask(moneyMask())
                    ->required(),
                TextInput::make("number_of_installments")
                    ->numeric()
                    ->label("Repay in")
                    ->suffix(fn (Get $get) => InterestPeriod::from(LoanProduct::find($get("loan_product_id"))?->repayment_period)->inPlural())
                    ->required(),
                TextInput::make("interest")
                    ->numeric()
                    ->required()
                    ->suffix(fn (Get $get) => LoanProduct::find($get("loan_product_id"))?->interest_period),
            ])
            ->statePath('data')
            ->model(LoanApplication::class);
    }

    public function calculate(): void
    {
        $data = $this->form->getState();
        if ($this->validateFormData($data)) {
            $loanApplication = new LoanApplication([
                "borrower_id" => null,
                "borrower_name" => null,
                "loan_product_id" => $data["loan_product_id"],
                "loan_product_name" => LoanProduct::find($data["loan_product_id"])?->label,
                "loan_product_details" => LoanProduct::with("charges")->find($data["loan_product_id"]),
                "amount" => sanitizeMoney($data["amount"]),
                "number_of_installments" => $data["number_of_installments"],
                "interest" => $data["interest"],
                "branch_id" => null,
                "loan_officer_id" => null
            ]);

            $processLoanApplication = new LoanApplicationHandler(
                $loanApplication,
                ["disbursed_on" => now()],
                false,
                false
            );

            $response = $processLoanApplication->handle();
            if ($response->success) {
                $data = new Fluent($response->data['loanCalculation']);
                session([
                    LoanCalculation::LOAN_CALCULATION_DATA_SESSION_KEY => $data
                ]);
                Notification::make()
                    ->success()
                    ->title("Calculation succesful")
                    ->body("Calculation successful, updating summary")
                    ->send();
            } else {
                Notification::make()
                    ->danger()
                    ->title("Calculation failed")
                    ->body($response->message)
                    ->send();
            }
        }
    }

    public function validateFormData(array $data)
    {
        $loanProduct = LoanProduct::find($data["loan_product_id"]);
        $amount = sanitizeMoney($data["amount"]);
        $interestRate = sanitizeMoney($data["interest"]);

        if ($loanProduct->minimum_principal > $amount) {
            $minimumPrincipal = number_format($loanProduct->minimum_principal, 2);
            $amount = number_format(sanitizeMoney($amount), 2);
            return $this->fail("The amount {$amount} is less than the product required minimum principal {$minimumPrincipal}");
        }
        if ($loanProduct->maximum_principal < $amount) {
            $maximumPrincipal = number_format($loanProduct->maximum_principal, 2);
            $amount = number_format(sanitizeMoney($amount), 2);
            return $this->fail("The amount {$amount} is greater than the product required maximum principal {$maximumPrincipal}");
        }

        if ($loanProduct->minimum_interest_rate > $interestRate) {
            $minimumInterestRate = number_format($loanProduct->minimum_interest_rate, 2);
            $interestRate = number_format(sanitizeMoney($interestRate), 2);
            return $this->fail("The interest rate {$interestRate} is less than the product required minimum interest rate {$minimumInterestRate}");
        }
        if ($loanProduct->maximum_interest_rate < $interestRate) {
            $maximumInterestRate = number_format($loanProduct->maximum_interest_rate, 2);
            $interestRate = number_format(sanitizeMoney($interestRate), 2);
            return $this->fail("The interest rate {$interestRate} is greater than the product required maximum interest rate {$maximumInterestRate}");
        }
        return true;
    }

    public function fail($message)
    {
        Notification::make()
            ->danger()
            ->title("Validation failed")
            ->body($message)
            ->send();
        return false;
    }
}
