<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Ward;

class WardsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Ward::create([
                'id'              => $row[0],
                'name'            => $row[1],
                'division_type'   => $row[2],
                'code_name'       => $row[3],
                'short_code_name' => $row[4],
                'district_id'     => $row[5],
            ]);
        }
    }
}
