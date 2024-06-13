<?php

namespace App\Filament\Resources\RoomsResource\Pages;

use App\Filament\Resources\RoomResource;
use App\Models\Contract;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRooms extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function afterCreate(): void
    {
        $contract = new Contract();
        $contract->room_id = $this->record->id;
        $contract->save();
    }
}
