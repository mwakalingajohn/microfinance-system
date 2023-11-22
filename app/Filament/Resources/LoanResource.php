<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Library\Enums\LoanStatus;
use App\Models\Loan;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;


    protected static ?string $navigationGroup = "Loans";

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
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
                    // ->description('LIn depth loan details')
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
                    ->tabs([
                        Tab::make("Amortization")
                            ->schema([]),
                        Tab::make("Repayments")
                            ->schema([]),
                        Tab::make("Associated charges")
                            ->schema([]),
                        Tab::make("Summary")
                            ->schema([])
                    ])
            ]);
    }
}
