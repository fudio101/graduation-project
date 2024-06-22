<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_id',
        'service_id',
        'quantity',
        'price',
    ];

}
