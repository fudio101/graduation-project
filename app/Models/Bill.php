<?php

namespace App\Models;

use App\Enums\HouseRoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ElectricBill;


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

    public function electricBill()
    {
        return $this->hasOne(ElectricBill::class, 'bill_id');
    }

    public function waterBill()
    {
        return $this->hasOne(WaterBill::class, 'bill_id');
    }

    public function serviceBill()
    {
        return $this->hasMany(ServiceBill::class);
    }
}
