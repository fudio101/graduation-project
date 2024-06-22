<?php

namespace App\Models;

use App\Enums\HouseRoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bills';

    protected $fillable = [
        'room_id',
        'total_money',
        'month',
        'status',
        'paid_date',
        'note',
        'start_date',
        'end_date',
    ];

    public function rooms()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    protected $casts = [
        'status' => HouseRoomStatus::class,
    ];

}
