<?php

namespace App\Models;

use App\Enums\HouseRoomStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'checked',
        'status',
        'manager_id',
        'electric_record',
        'water_record',
    ];

    public static $STATUS_ACTIVE   = 1;
    public static $STATUS_INACTIVE = 0;

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

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'room_services')->withPivot('quantity');
    }

    // Thiết lập mối quan hệ với Contract
    public function contracts()
    {
        return $this->hasOne(Contract::class);
    }

    public function isStatusActive()
    {
        return $this->checked;
    }
}
