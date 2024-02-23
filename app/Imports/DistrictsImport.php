<?php

namespace App\Imports;

use App\Models\District;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DistrictsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            District::create([
                'id'              => $row[0],
                'name'            => $row[1],
                'division_type'   => $row[2],
                'code_name'       => $row[3],
                'short_code_name' => $row[4],
                'province_id'     => $row[5],
            ]);
        }
    }
}
