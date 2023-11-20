<?php

namespace App\Filament\Resources\LoanProductResource\RelationManagers;

use App\Filament\Resources\LoanProductResource;
use App\Library\Enums\DeductibleValueType;
use App\Library\Enums\LoanChargeSource;
use Filament\Tables\Table;
use Miguilim\FilamentAutoPanel\AutoRelationManager;

class ChargesRelationManager extends AutoRelationManager
{
    protected static string $relatedResource = LoanProductResource::class;

    protected static string $relationship = 'loanCharges';

    protected static ?string $recordTitleAttribute = 'label';

    protected static array $enumDictionary = [
        "type" => DeductibleValueType::data,
        "from" => LoanChargeSource::data
    ];

    protected static array $visibleColumns = [
        "of","type","from","created_at","label","value", "on"
    ];

    protected static array $searchableColumns = [];

    public function getFilters(): array
    {
        return [
            //
        ];
    }

    public function getActions(): array
    {
        return [
            //
        ];
    }

    public function getColumnsOverwrite(): array
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
}
