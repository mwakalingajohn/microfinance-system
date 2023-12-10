<?php

namespace App\Filament\Resources\LoanDisbursementResource\Pages;

use App\Filament\Resources\LoanDisbursementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanDisbursement extends EditRecord
{
    protected static string $resource = LoanDisbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
