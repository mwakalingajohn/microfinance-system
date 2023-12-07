<?php

namespace App\Filament\Resources;

use App\Library\Enums\CalculatablePeriod;
use App\Library\Enums\DeductibleValueType;
use App\Library\Enums\Penaltibles;
use App\Library\Enums\PenaltyMethod;
use App\Models\Penalty;
use Miguilim\FilamentAutoPanel\AutoResource;

class PenaltyResource extends AutoResource
{
    protected static ?string $model = Penalty::class;

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?string $recordTitleAttribute = 'label';

    

    protected static array $enumDictionary = [
        "on" => Penaltibles::data,
        "after_every" => CalculatablePeriod::data,
        "method" => PenaltyMethod::data,
        "type" => DeductibleValueType::data
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
            //
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
            'table' => [
                //
            ],
            'form' => [
                //
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
