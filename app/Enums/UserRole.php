<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: int implements HasLabel, HasColor
{
    case Admin      = 1;
    case Owner      = 2;
    case Manager    = 3;
    case NormalUser = 4;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin      => 'Admin',
            self::Owner      => 'Owner',
            self::Manager    => 'Manager',
            self::NormalUser => 'Normal User',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Admin      => 'warning',
            self::Owner      => 'gray',
            self::Manager    => 'success',
            self::NormalUser => 'info',
        };
    }
}
