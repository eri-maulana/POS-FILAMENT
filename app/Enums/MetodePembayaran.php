<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MetodePembayaran: string implements HasLabel
{
    case TUNAI = 'tunai';
    case BANK_TRANFER = 'bank_transfer';

    public function getLabel(): ?string
    {
        return str(
            str($this->value)->replace('_', ' ')
        )->title();
    }
}

?>