<?php

namespace App\Filament\Resources\ContractExpireResource\Pages;

use App\Filament\Resources\ContractExpireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContractExpire extends EditRecord
{
    protected static string $resource = ContractExpireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
