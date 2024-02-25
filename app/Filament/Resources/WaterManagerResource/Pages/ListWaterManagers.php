<?php

namespace App\Filament\Resources\WaterManagerResource\Pages;

use App\Filament\Resources\WaterManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWaterManagers extends ListRecords
{
    protected static string $resource = WaterManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
