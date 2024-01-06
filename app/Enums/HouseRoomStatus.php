<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum HouseRoomStatus: int implements HasLabel, HasColor, HasIcon
{
    case Inactive   = 0;
    case Active     = 1;
    case Pending    = 2;
    case Registered = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Inactive => 'Inactive',
            self::Active => 'Active',
            self::Pending => 'Pending',
            self::Registered => 'Registered',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Inactive => 'danger',
            self::Active => 'success',
            self::Pending => 'gray',
            self::Registered => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Inactive => 'heroicon-s-exclamation-circle',
            self::Active => 'heroicon-s-check-circle',
            self::Pending => 'heroicon-s-wrench-screwdriver',
            self::Registered => 'heroicon-s-pencil',
        };
    }
}
