<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanChargeResource\Pages;
use App\Filament\Resources\LoanChargeResource\RelationManagers;
use App\Library\Enums\DeductibleValueType;
use App\Library\Enums\LoanChargeDestination;
use App\Library\Enums\LoanChargeSource;
use App\Models\LoanCharge;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Miguilim\FilamentAutoPanel\AutoResource;

class LoanChargeResource extends Resource
{
    protected static ?string $model = LoanCharge::class;

    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make("label"),
                        Select::make("on")
                            ->options(LoanChargeDestination::data)
                            ->native(false),
                        Select::make("type")
                            ->options(DeductibleValueType::data)
                            ->live(),
                        Select::make("of")
                            ->options(LoanChargeSource::data)
                            ->visible(fn (Get $get) => $get("type") == DeductibleValueType::Percentage->value),
                        TextInput::make("value")
                            ->mask(moneyMask())
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("label"),
                TextColumn::make("on"),
                TextColumn::make("type"),
                TextColumn::make("of"),
                TextColumn::make("value"),
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
            'view' => Pages\ViewLoanCharge::route('/{record}'),
        ];
    }
}
