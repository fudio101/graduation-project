<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Room;
use App\Models\Service;

class RoomService extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'service_id',
        'quantity',
        'price',
    ];
  
}
