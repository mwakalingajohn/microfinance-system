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
}
