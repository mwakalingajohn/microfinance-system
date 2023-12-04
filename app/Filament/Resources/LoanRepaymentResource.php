<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanRepaymentResource\Pages;
use App\Filament\Resources\LoanRepaymentResource\RelationManagers;
use App\Models\LoanRepayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanRepaymentResource extends Resource
{
    protected static ?string $model = LoanRepayment::class;


    protected static ?string $navigationGroup = "Loans";

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("loan.loan_code")
                    ->label("Code"),
                TextColumn::make("loan.borrower_name")
                    ->label("Borrower"),
                TextColumn::make("loan.loan_product_name")
                    ->label("Loan product"),
                TextColumn::make("amount")
                    ->money("TZS")
                    ->label("Repaid amount"),
                TextColumn::make("repayment_date")
                    ->dateTime(),
                TextColumn::make("status")
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListLoanRepayments::route('/'),
            'create' => Pages\CreateLoanRepayment::route('/create'),
            'edit' => Pages\EditLoanRepayment::route('/{record}/edit'),
        ];
    }
}
