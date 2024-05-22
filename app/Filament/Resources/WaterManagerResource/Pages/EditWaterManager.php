<?php

namespace App\Filament\Resources\WaterManagerResource\Pages;

use App\Filament\Resources\WaterManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWaterManager extends EditRecord
{
    protected static string $resource = WaterManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
