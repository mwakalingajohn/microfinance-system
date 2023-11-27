<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanProductResource\Pages\EditLoanProduct;
use App\Filament\Resources\LoanProductResource\RelationManagers\ChargesRelationManager;
use App\Filament\Resources\LoanProductResource\RelationManagers\PenaltiesRelationManager;
use App\Infolists\Components\JsonEntry;
use App\Library\Enums\DueDateMethod;
use App\Library\Enums\InterestPeriod;
use App\Library\Enums\LoanCalculationMethod;
use App\Library\Enums\RepaymentOrderItem;
use App\Models\LoanProduct;
use Miguilim\FilamentAutoPanel\AutoResource;
use Miguilim\FilamentAutoPanel\Mounters\PageMounter;

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

    protected static array $visibleColumns = [
        "label",
        "minimum_principal",
        "maximum_principal",
        "default_interest_rate",
        "interest_period",
        "due_date_method",
        "grace_on_interest"
    ];

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
                \EightyNine\SortableField\Forms\Components\SortableField::make("repayment_order")
                    ->items(RepaymentOrderItem::associativeValues())
            ],
            'infolist' => [
                \EightyNine\SortableField\Infolists\Components\SortableEntry::make("repayment_order")
            ],
        ];
    }

    public static function getExtraPages(): array
    {
        return [
            
        ];
    }
}
