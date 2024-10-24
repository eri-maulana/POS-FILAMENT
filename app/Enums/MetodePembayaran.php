<?php

namespace App\Enums;

enum MetodePembayaran: string
{
    case TUNAI = 'tunai';
    case BANK_TRANFER = 'bank_transfer';

    public function label(): ?string
    {
        return str(
            str($this->value)->replace('_', ' ')
        )->title();
    }
}

?>