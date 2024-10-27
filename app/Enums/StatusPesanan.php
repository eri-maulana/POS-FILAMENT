<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StatusPesanan: string implements HasLabel
{
    case TERTUNDA = 'tertunda';
    case SELESAI = 'selesai';
    case DIBATALKAN = 'dibatalkan';

    public function getLabel(): ?string
    {
        return str($this->value)->title();
    }

    public function warna(): string
    {
        return match ($this) {
            self::TERTUNDA => 'warning',
            self::SELESAI =>'success',
            self::DIBATALKAN => 'danger',
        };
    }
}
