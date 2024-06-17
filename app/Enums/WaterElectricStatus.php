<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum WaterElectricStatus: int implements HasLabel, HasColor
{
    case Quantity = 0;
    case Step     = 1;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Step => 'Quantity',
            self::Quantity => 'Step',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Quantity => 'info',
            self::Step => 'gray',
        };
    }
}
