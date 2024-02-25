<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\WaterElectricStatus;

class ElectricManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id',
        'status',
        'quantity',
        'step',
    ];

    protected $casts = [
        'step' => 'array',
        'status' => WaterElectricStatus::class,
    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
