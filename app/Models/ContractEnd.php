<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractEnd extends Model
{
    use HasFactory;
    protected $table = 'contracts_end';

    protected $fillable = [
        'room_id',
        'member_id',
        'start_date',
        'end_date',
        'description',
        'termination_date',
    ];

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
