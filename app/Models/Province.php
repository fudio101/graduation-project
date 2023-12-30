<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;

class Province extends Model
{
    use Sushi;

    protected array $schema = [
        'id' => 'integer',
        'code' => 'integer',
        'name' => 'string',
        'division_type' => 'string',
        'code_name' => 'string',
        'phone_code' => 'integer',
    ];

    /**
     * Model Rows
     *
     * @return array
     */
    public function getRows(): array
    {
        //API
        $provinces = Http::get('https://provinces.open-api.vn/api/p/')->json();

        //filtering some attributes
        return Arr::map($provinces, function ($item) {
            $item['id'] = $item['code'];
            $item['code_name'] = $item['codename'];

            return Arr::only($item,
                [
                    'id',
                    'code',
                    'name',
                    'division_type',
                    'code_name',
                    'phone_code',
                ]
            );
        });
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    protected function sushiShouldCache(): bool
    {
        return true;
    }
}
