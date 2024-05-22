<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportDataAddressByCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-data-address-by-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data address by csv file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePathProvinces = storage_path('/data/csv/provinces.csv');
        $filePathDistricts = storage_path('/data/csv/districts.csv');
        $filePathWards     = storage_path('/data/csv/wards.csv');

        if (file_exists($filePathProvinces) && file_exists($filePathDistricts) && file_exists($filePathWards)){
            $this->info('Importing...');
            Excel::import(new \App\Imports\ProvincesImport, $filePathProvinces);
            Excel::import(new \App\Imports\DistrictsImport, $filePathDistricts);
            Excel::import(new \App\Imports\WardsImport, $filePathWards);
            $this->info('Imported!');
            
        } else {
            $this->error('File does not exist!');
        }
    }
}
