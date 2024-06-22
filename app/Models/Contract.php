<?php

namespace App\Models;

use App\Enums\HouseRoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'room_id',
        'member_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'status' => HouseRoomStatus::class,
    ];

    public static $ACTIVE   = 1;
    public static $INACTIVE = 0;

    // Thiết lập mối quan hệ với User
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    // Thiết lập mối quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
