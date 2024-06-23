<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Filament\Resources\BillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\TextInput;

class ListBills extends ListRecords
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('Billing')
            ->label('Billing')
            ->form([
                TextInput::make('billing_date')
                    ->type('month')
                    ->default(date('Y-m'))
                    ->required()
            ])
            ->action(function (array $data) {
                return redirect()->route('bill', ['date' => $data['billing_date']]);
            }),
        ];
    }
}
