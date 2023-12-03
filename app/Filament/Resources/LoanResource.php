<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Infolists\Components\TableEntry;
use App\Library\Enums\LoanStatus;
use App\Library\Services\LoanService;
use App\Models\Loan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationGroup = "Loans";

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'repay'
        ];
    }

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("borrower.name"),
                TextColumn::make("loan_product_name"),
                TextColumn::make("principal")
                    ->money("TZS")
                    ->label("amount"),
                TextColumn::make("total_loan")
                    ->money("TZS"),
                TextColumn::make("status")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        LoanStatus::Active->value => 'gray',
                        LoanStatus::Overdue->value => 'warning',
                        LoanStatus::Paid->value => 'success',
                        LoanStatus::Defaulted->value => 'danger',
                    }),
                TextColumn::make("created_at")
                ->dateTime()
                ->label("Date")
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make("repay")
                    ->slideOver()
                    ->modalWidth("md")
                    ->form([
                        TextInput::make("amount")
                            ->mask(moneyMask())
                            ->prefix("TZS")
                            ->required(),
                        FileUpload::make("proof_of_payment")
                            ->label("Proof of payment"),
                        DateTimePicker::make("repayment_date")
                            ->required()
                            ->default(now())
                            ->label("Repaid On")
                    ])
                    ->action(function (Loan $record, array $data) {
                        $response = (new LoanService)->repay($record, $data);
                        if ($response->success) {
                            Notification::make()
                                ->title('Repayment successful')
                                ->body("The loan was successfully repaid")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Repayment failed')
                                ->body($response->message)
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
            'view' => Pages\ViewLoan::route('/{record}'),
        ];
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Loan Details')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('borrower.name'),
                        TextEntry::make("status")
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                LoanStatus::Active->value => 'gray',
                                LoanStatus::Overdue->value => 'warning',
                                LoanStatus::Paid->value => 'success',
                                LoanStatus::Defaulted->value => 'danger',
                            }),
                        TextEntry::make('created_at')
                            ->label("Created")
                            ->dateTime()
                            ->since(),
                        TextEntry::make("loan_product_name")
                            ->label("Product"),
                        TextEntry::make("interest_rate")
                            ->suffix("%"),
                        TextEntry::make("number_of_installments"),
                        TextEntry::make("loan_officer_name")
                    ]),
                Tabs::make("Other details")
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make("Amortization")
                            ->schema([
                                TableEntry::make("installments")
                                    ->title("Loan amortization and balances")
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make("principal")
                                            ->money("TZS"),
                                        TextEntry::make("interest")
                                            ->money("TZS"),
                                        TextEntry::make("charges")
                                            ->money("TZS"),
                                        TextEntry::make("penalty")
                                            ->money("TZS"),
                                        TextEntry::make("installment")
                                            ->money("TZS"),
                                        TextEntry::make("remaining_principal")
                                            ->money("TZS"),
                                        TextEntry::make("remaining_interest")
                                            ->money("TZS"),
                                        TextEntry::make("remaining_charges")
                                            ->money("TZS"),
                                        TextEntry::make("remaining_penalty")
                                            ->money("TZS"),
                                        TextEntry::make("remaining_installment")
                                            ->money("TZS")
                                    ])
                            ]),
                        Tab::make("Associated charges")
                            ->schema([
                                TableEntry::make("loanCharges")
                                    ->title("Charges applied to loan")
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make("label"),
                                        TextEntry::make("on"),
                                        TextEntry::make("type"),
                                        TextEntry::make("of"),
                                        TextEntry::make("amount")
                                            ->money("TZS"),
                                    ])
                            ]),
                        Tab::make("Repayments")
                            ->schema([])
                    ])
            ]);
    }
}
