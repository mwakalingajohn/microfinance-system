<?php

namespace App\Filament\Resources\LoanChargeResource\Pages;

use App\Filament\Resources\LoanChargeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanCharge extends EditRecord
{
    protected static string $resource = LoanChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['value'] = sanitizeMoney($data["value"]);
        return $data;
    }
}
