<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Enums\StatusPesanan;
use App\Models\DetailPesanan;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use App\Filament\Resources\PesananResource;

class BuatTransaksi extends Page implements HasForms
{
    protected static string $resource = PesananResource::class;

    protected static string $view = 'filament.resources.pesanan-resource.pages.buat-transaksi';
    public Pesanan $record;


    public function getTitle(): string
    {
        return "Pesanan: {$this->record->nomor_pesanan}";
    }

    public mixed $produkDipilih;
    public int $nilaiKuantitas = 1;
    public int $diskon = 0;

    protected function getFormSchema(): array
    {
        return [
            Select::make('produkDipilih')
                ->label('Pilih Produk')
                ->searchable()
                ->preload()
                ->options(Produk::pluck('nama', 'id')->toArray())
                ->live()
                ->afterStateUpdated(function ($state){
                    $produk = Produk::find($state);
                    $this->record->detailPesanans()->updateOrCreate([
                        'pesanan_id' => $this->record->id,
                        'produk_id' => $state,
                    ],
                    [
                        'produk_id' => $state,
                        'kuantitas' => $this->nilaiKuantitas,
                        'harga' => $produk->harga,
                        'subtotal' => $produk->harga * $this->nilaiKuantitas,
                    ]);
                }),
            ];
    }

    // update kuantitas
    public function updateQuantity(DetailPesanan $detailPesanan, $kuantitas): void
    {
        if($kuantitas > 0){
            $detailPesanan->update([
                'kuantitas' => $kuantitas,
                'subtotal' => $detailPesanan->harga * $kuantitas,
            ]);
        }
    }

    // hapus Produk
    public function removeProduct(DetailPesanan $detailPesanan): void
    {
        $detailPesanan->delete();

        $this->dispatch('produkDihapus');
    }

    // Update order
    public function updateOrder(): void
    {
        $subtotal = $this->record->detailPesanan->sum('subtotal');

        $this->record->update([
            'diskon' => $this->diskon,
            'total' => $subtotal - $this->diskon,
        ]);
    }

    // Finalize Order
    public function finalizeOrder(): void
    {
        $this->updateOrder();
        $this->record->update(['status' => StatusPesanan::SELESAI]);
        $this->redirect('/pesanans');
    }

    // draft
    public function saveAsDraft(): void
    {
        $this->updateOrder();
        $this->redirect('/pesanans');
    }
    
}
