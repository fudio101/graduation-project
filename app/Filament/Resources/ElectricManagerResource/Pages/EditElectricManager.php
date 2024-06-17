<?php

namespace App\Filament\Resources\ElectricManagerResource\Pages;

use App\Filament\Resources\ElectricManagerResource;
use Filament\Resources\Pages\EditRecord;

class EditElectricManager extends EditRecord
{
    protected static string $resource = ElectricManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
