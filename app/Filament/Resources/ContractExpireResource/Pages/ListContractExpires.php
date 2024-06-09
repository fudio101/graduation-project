<?php

namespace App\Filament\Resources\ContractExpireResource\Pages;

use App\Filament\Resources\ContractExpireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContractExpires extends ListRecords
{
    protected static string $resource = ContractExpireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
