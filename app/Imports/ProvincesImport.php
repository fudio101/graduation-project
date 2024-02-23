<?php

namespace App\Imports;

use App\Models\Province;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProvincesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Province::create([
                'id'            => $row[0],
                'name'          => $row[1],
                'division_type' => $row[2],
                'code_name'     => $row[3],
                'phone_code'    => $row[4],
            ]);
        }
    }
}
