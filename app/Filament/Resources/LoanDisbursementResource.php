<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanDisbursementResource\Pages;
use App\Filament\Resources\LoanDisbursementResource\RelationManagers;
use App\Models\LoanDisbursement;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanDisbursementResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = LoanDisbursement::class;

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
            'disburse',
            'repay',
            'recalculate',
            'cancel'
        ];
    }

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLoanDisbursements::route('/'),
            'create' => Pages\CreateLoanDisbursement::route('/create'),
            'edit' => Pages\EditLoanDisbursement::route('/{record}/edit'),
        ];
    }
}
