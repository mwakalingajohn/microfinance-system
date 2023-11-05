<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanChargeResource\Pages;
use App\Filament\Resources\LoanChargeResource\RelationManagers;
use App\Models\LoanCharge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanChargeResource extends Resource
{
    protected static ?string $model = LoanCharge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListLoanCharges::route('/'),
            'create' => Pages\CreateLoanCharge::route('/create'),
            'edit' => Pages\EditLoanCharge::route('/{record}/edit'),
        ];
    }    
}
