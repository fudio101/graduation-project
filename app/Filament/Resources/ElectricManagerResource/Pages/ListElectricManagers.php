<?php

namespace App\Filament\Resources\ElectricManagerResource\Pages;

use App\Filament\Resources\ElectricManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListElectricManagers extends ListRecords
{
    protected static string $resource = ElectricManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
