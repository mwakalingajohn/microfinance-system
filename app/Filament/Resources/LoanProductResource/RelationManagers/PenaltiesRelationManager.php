<?php

namespace App\Filament\Resources\LoanProductResource\RelationManagers;

use App\Filament\Resources\LoanProductResource;
use Filament\Tables\Table;
use Miguilim\FilamentAutoPanel\AutoRelationManager;

class PenaltiesRelationManager extends AutoRelationManager
{
    protected static string $relatedResource = LoanProductResource::class;

    protected static string $relationship = 'penalties';

    protected static ?string $recordTitleAttribute = 'label';

    protected static array $enumDictionary = [];

    protected static array $visibleColumns = [];

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
