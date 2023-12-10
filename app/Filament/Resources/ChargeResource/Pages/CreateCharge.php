<?php

namespace App\Filament\Resources\ChargeResource\Pages;

use App\Filament\Resources\ChargeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCharge extends CreateRecord
{
    protected static string $resource = ChargeResource::class;

    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['value'] = sanitizeMoney($data["value"]);
        return $data;
    }
}
