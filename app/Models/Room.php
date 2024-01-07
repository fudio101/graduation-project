<?php

namespace App\Models;

use App\Enums\HouseRoomStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'room_type_id',
        'house_id',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => HouseRoomStatus::class,
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'room_services')->withPivot('quantity');
    }
}
