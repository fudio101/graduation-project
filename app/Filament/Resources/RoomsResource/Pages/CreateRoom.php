<?php

namespace App\Filament\Resources\RoomsResource\Pages;

use App\Filament\Resources\RoomResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Contract;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function afterCreate(): void
    {
        $contract = new Contract();
        $contract->room_id = $this->record->id;
        $contract->save();
    }
}
