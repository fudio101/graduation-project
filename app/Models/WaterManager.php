<?php

namespace App\Models;

use App\Enums\WaterElectricStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'house_id',
        'status',
        'quantity',
        'step',
    ];

    protected $casts = [
        'step'   => 'array',
        'status' => WaterElectricStatus::class,

    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
