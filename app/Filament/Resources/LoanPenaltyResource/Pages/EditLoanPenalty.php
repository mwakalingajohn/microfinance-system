<?php

namespace App\Filament\Resources\LoanPenaltyResource\Pages;

use App\Filament\Resources\LoanPenaltyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanPenalty extends EditRecord
{
    protected static string $resource = LoanPenaltyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
