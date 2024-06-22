<?php

namespace App\Filament\Resources\ElectricRecordResource\Pages;

use App\Filament\Resources\ElectricRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListElectricRecords extends ListRecords
{
    protected static string $resource = ElectricRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    
}
