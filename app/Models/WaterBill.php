<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_id',
        'number',
        'type',
        'costs',
        'quantity',
        'step',
    ];

    protected $casts = [
        'step' => 'array',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
