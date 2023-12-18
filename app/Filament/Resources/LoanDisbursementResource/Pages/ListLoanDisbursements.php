<?php

namespace App\Filament\Resources\LoanDisbursementResource\Pages;

use App\Filament\Resources\LoanDisbursementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanDisbursements extends ListRecords
{
    protected static string $resource = LoanDisbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
