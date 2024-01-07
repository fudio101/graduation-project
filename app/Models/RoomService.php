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

    /**
     * The RoomService model represents the relationship between a room and a service.
     *
     * @property int $room_id The ID of the room.
     * @property int $service_id The ID of the service.
     * @property int $quantity The quantity of the service requested.
     * @property float $price The price of the service.
     *
     * @property \App\Models\Room $room The room associated with the room service.
     * @property \App\Models\Service $service The service associated with the room service.
     */
    protected $fillable = [
        'room_id',
        'service_id',
        'quantity',
        'price',
    ];

    /**
     * Get the room associated with the room service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the service associated with the room service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
