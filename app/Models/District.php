<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;

class District extends Model
{
    use Sushi;

    protected array $schema = [
        'id' => 'integer',
        'code' => 'integer',
        'name' => 'string',
        'division_type' => 'string',
        'code_name' => 'string',
        'province_id' => 'integer',
    ];

    /**
     * Model Rows
     *
     * @return array
     */
    public function getRows(): array
    {
        //API
        $provinces = Http::get('https://provinces.open-api.vn/api/d/')->json();

        //filtering some attributes
        return Arr::map($provinces, function ($item) {
            $item['id'] = $item['code'];
            $item['code_name'] = $item['codename'];
            $item['province_id'] = $item['province_code'];

            return Arr::only($item,
                [
                    'id',
                    'code',
                    'name',
                    'division_type',
                    'code_name',
                    'province_id',
                ]
            );
        });
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }

    protected function sushiShouldCache(): bool
    {
        return true;
    }
}
