<?php

namespace App\Models;

use App\Enums\MetodePembayaran;
use App\Enums\StatusPesanan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => StatusPesanan::class,
        'metode_pembayaran' => MetodePembayaran::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $pesanan) {
            $pesanan->user_id = auth()->id();
            $pesanan->total = 0;
        });

        static::saving(function ($pesanan) {
            if ($pesanan->isDirty('total')) {
                $pesanan->loadMissing('detailPesanans.produk');

                $perhitunganKeuntungan = $pesanan->detailPesanans->reduce(function ($carry, $detail) {
                    $produkKeuntunan = ($detail->harga - $detail->produk->harga_modal) * $detail->kuantitas;
                    return $carry   + $produkKeuntunan;
                }, 0);

                $pesanan->attributes['keuntungan'] = $perhitunganKeuntungan;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'nomor_pesanan';
    }

    public function markAsComplete(): void
    {
        $this->status = StatusPesanan::SELESAI;
        $this->save();
    }

    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
