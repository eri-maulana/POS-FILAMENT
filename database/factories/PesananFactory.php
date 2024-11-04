<?php

namespace Database\Factories;

use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Enums\MetodePembayaran;
use App\Enums\StatusPesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesananFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pesanan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        Pesanan::unsetEventDispatcher();
        return [
            'user_id' => 1,
            'pelanggan_id' => rand(1,50),
            'nomor_pesanan' => $this->faker->unique()->bothify('ORD########'),
            'nama_pesanan' => ucfirst($this->faker->word()),
            'diskon' => $this->faker->numberBetween(5000, 10000),
            'total' => 0,
            'metode_pembayaran' => collect(MetodePembayaran::cases())->random(),
            'status' => collect(StatusPesanan::cases())->random(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Pesanan $pesanan){
        })->afterCreating(function (Pesanan $pesanan){
            $produkId = Produk::query()->inRandomOrder()->take(rand(1,5))->pluck('id');
            $detailPesanan = $produkId->map(function($produkId) use ($pesanan){
                $kuantitas = rand(1,10);
                $harga = Produk::find($produkId)->harga;
                $subtotal = $kuantitas * $harga;

                return [
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $produkId,
                    'kuantitas' => $kuantitas,
                    'harga' => $harga,
                   'subtotal' => $subtotal,
                ];
            });

            DetailPesanan::insert($detailPesanan->toArray());
            $total = $detailPesanan->sum('subtotal') - $pesanan->diskon;
            $pesanan->total = $total;
            $pesanan->keuntungan = $total * 0.1;
            $pesanan->save();
        });
    }
}
