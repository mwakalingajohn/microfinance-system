<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanApplicationResource\Pages;
use App\Filament\Resources\LoanApplicationResource\RelationManagers;
use App\Library\Enums\LoanDisbursementMethod;
use App\Library\Services\LoanService;
use App\Models\LoanApplication;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use EightyNine\Approvals\Tables\Columns\ApprovalStatusColumn;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanApplicationResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = LoanApplication::class;


    protected static ?string $navigationGroup = "Loans";

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'disburse',
            'repay',
            'recalculate',
            'cancel'
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("borrower_id")
                    ->relationship(name: "borrower", titleAttribute: "last_name")
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->preload(),
                Select::make("loan_product_id")
                    ->relationship(name: "loanProduct", titleAttribute: "label")
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->preload(),
                TextInput::make("amount")
                    ->numeric()
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("borrower.first_name"),
                TextColumn::make("borrower.last_name"),
                TextColumn::make("loanProduct.label"),
                TextColumn::make("amount")->money("Tsh"),
                ApprovalStatusColumn::make("approvalStatus.status")
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ...\EightyNine\Approvals\Tables\Actions\ApprovalActions::make(
                    // define your actions here that will appear once approval is completed
                    Action::make("Disburse")
                        ->fillForm(fn (LoanApplication $record): array => [
                            "amount" => $record->amount
                        ])
                        ->form([
                            TextInput::make("loan_application_id")
                                ->required()
                                ->hidden(true),
                            TextInput::make("amount")
                                ->numeric()
                                ->readOnly()
                                ->required(),
                            Select::make("method")
                                ->options(LoanDisbursementMethod::associativeValues())
                                ->native(false)
                                ->required()
                                ->default(LoanDisbursementMethod::Cash->value)
                        ])
                        ->action(function (LoanApplication $record, array $data): void {
                            $response = (new LoanService)->disburse($record, $data["method"]);
                            if ($response->success) {
                                Notification::make()
                                    ->title('Disbursement successful')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Disbursement failed')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->modalDescription("Desburse the amount to user")
                        ->slideOver()
                        ->modalWidth("md")
                ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoanApplications::route('/'),
            'create' => Pages\CreateLoanApplication::route('/create'),
            'edit' => Pages\EditLoanApplication::route('/{record}/edit'),
        ];
    }
}
