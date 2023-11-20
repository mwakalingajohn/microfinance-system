<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanProductResource\Pages;
use App\Filament\Resources\LoanProductResource\RelationManagers;
use App\Filament\Resources\LoanProductResource\RelationManagers\ChargesRelationManager;
use App\Filament\Resources\LoanProductResource\RelationManagers\PenaltiesRelationManager;
use App\Library\Enums\DueDateMethod;
use App\Library\Enums\InterestPeriod;
use App\Library\Enums\LoanCalculationMethod;
use App\Models\LoanProduct;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Miguilim\FilamentAutoPanel\AutoResource;

class LoanProductResource extends AutoResource
{
    protected static ?string $model = LoanProduct::class;

    protected static ?string $navigationGroup = 'Configuration';

    protected static array $enumDictionary = [
        "interest_period" => InterestPeriod::data,
        "repayment_period" => InterestPeriod::data,
        "calculation_method" => LoanCalculationMethod::data,
        "due_date_method" => DueDateMethod::data
    ];

    protected static array $visibleColumns = [];

    protected static array $searchableColumns = [];

    public static function getFilters(): array
    {
        return [
            //
        ];
    }

    public static function getActions(): array
    {
        return [
            //
        ];
    }

    public static function getRelations(): array
    {
        return [
            ChargesRelationManager::class,
            PenaltiesRelationManager::class
        ];
    }

    public static function getHeaderWidgets(): array
    {
        return [
            'list' => [
                //
            ],
            'view' => [
                //
            ],
        ];
    }

    public static function getFooterWidgets(): array
    {
        return [
            'list' => [
                //
            ],
            'view' => [
                //
            ],
        ];
    }

    public static function getColumnsOverwrite(): array
    {
        return [
            'table' => [],
            'form' => [
            ],
            'infolist' => [
                //
            ],
        ];
    }

    public static function getExtraPages(): array
    {
        return [
            //
        ];
    }
}
