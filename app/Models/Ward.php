<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;

class Ward extends Model
{
    use Sushi;

    protected array $schema = [
        'id' => 'integer',
        'code' => 'integer',
        'name' => 'string',
        'division_type' => 'string',
        'code_name' => 'string',
        'district_id' => 'integer',
    ];

    /**
     * Model Rows
     *
     * @return array
     */
    public function getRows(): array
    {
        //API
        $provinces = Http::get('https://provinces.open-api.vn/api/w/')->json();

        //filtering some attributes
        return Arr::map($provinces, function ($item) {
            $item['id'] = $item['code'];
            $item['code_name'] = $item['codename'];
            $item['district_id'] = $item['district_code'];

            return Arr::only($item,
                [
                    'id',
                    'code',
                    'name',
                    'division_type',
                    'code_name',
                    'district_id',
                ]
            );
        });
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    protected function sushiShouldCache(): bool
    {
        return true;
    }
}
