<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'division_type',
        'code_name',
        'phone_code',
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
