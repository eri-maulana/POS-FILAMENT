<?php

namespace App\Enums;

enum StatusPesanan: string
{
    case TERTUNDA = 'tertunda';
    case SELESAI = 'selesai';
    case DIBATALKAN = 'dibatalkan';

    public function label(): ?string
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
