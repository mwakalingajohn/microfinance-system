<?php

namespace App\Filament\Resources;

use App\Library\Enums\MaritalStatus;
use App\Library\Enums\Occupation;
use App\Models\Borrower;
use Filament\Tables\Table;
use Miguilim\FilamentAutoPanel\AutoResource;

class BorrowerResource extends AutoResource
{
    protected static ?string $model = Borrower::class;

    protected static ?string $navigationGroup = 'Users';

    protected static array $enumDictionary = [
        "marital_status" => MaritalStatus::data,
        "occupation" => Occupation::data
    ];

    protected static array $visibleColumns = [
        // "user_id",
        "organization_id",
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'sex',
        "street_id",
        'birthdate',
        'occupation',
        'marital_status',
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
