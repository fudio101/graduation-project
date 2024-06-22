<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
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
