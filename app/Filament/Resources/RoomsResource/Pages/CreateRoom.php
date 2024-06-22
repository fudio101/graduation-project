<?php

namespace App\Filament\Resources\RoomsResource\Pages;

use App\Filament\Resources\RoomResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Contract;
use App\Models\ElectricRecord;
use App\Models\WaterRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function afterCreate(): void
    {
        $contract = new Contract();
        $contract->room_id = $this->record->id;
        $contract->save();

        $electric_record = new ElectricRecord();
        $electric_record->room_id = $this->record->id;
        $electric_record->save();

        $water_record = new WaterRecord();
        $water_record->room_id = $this->record->id;
        $water_record->save();
    }
}
