<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;

class DetailPesananFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetailPesanan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pesanan_id' => Pesanan::factory(),
            'produk_id' => Produk::factory(),
            'kuantitas' => $this->faker->numberBetween(-10000, 10000),
            'harga' => $this->faker->numberBetween(-10000, 10000),
            'subtotal' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
