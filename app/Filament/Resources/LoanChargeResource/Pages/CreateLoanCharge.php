<?php

namespace App\Filament\Resources\LoanChargeResource\Pages;

use App\Filament\Resources\LoanChargeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLoanCharge extends CreateRecord
{
    protected static string $resource = LoanChargeResource::class;

    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['value'] = sanitizeMoney($data["value"]);
        return $data;
    }
}
