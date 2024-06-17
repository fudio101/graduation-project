<?php

namespace App\Filament\Resources\HouseResource\Pages;

use App\Filament\Resources\HouseResource;
use App\Models\ElectricManager;
use App\Models\WaterManager;
use Filament\Resources\Pages\CreateRecord;

class CreateHouse extends CreateRecord
{
    protected static string $resource = HouseResource::class;


    protected function afterCreate(): void
    {
        // Add data water
        $json_data_water_step = '{
                "10": "0",
                "20": "0",
                "30": "0",
                "99": "0"
            }';
        $water_manager = new WaterManager();
        $water_manager->house_id = $this->record->id;
        $water_manager->step = json_decode($json_data_water_step);
        $water_manager->save();

        // Add data electric
        $json_data_electric_step = '{
                "50": "0",
                "100": "0",
                "200": "0",
                "300": "0",
                "400": "0",
                "999": "0"
            }';
        $electric_manager = new ElectricManager();
        $electric_manager->house_id = $this->record->id;
        $electric_manager->step = json_decode($json_data_electric_step);
        $electric_manager->save();

    }

}
