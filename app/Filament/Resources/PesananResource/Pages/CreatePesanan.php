<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function getRedirectUrl(): string
    {
        return route('filament.app.resources.pesanans.buat-transaksi',['record' => $this->record->nomor_pesanan,]);
        
    }
}
