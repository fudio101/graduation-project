<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role'              => UserRole::class,
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // enable this for production
        // return str_ends_with($this->email, '@gmail.com') && $this->hasVerifiedEmail();
        return str_ends_with($this->email, '@gmail.com');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (!$this->avatar_url) {
            return null;
        }
        if (str_starts_with($this->avatar_url, 'http')) {
            return $this->avatar_url;
        }
        return Storage::url($this->avatar_url);
    }

    public function houses(): HasMany
    {
        return $this->hasMany(House::class, 'owner_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'manager_id');
    }
}
