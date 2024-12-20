<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Kategori;
use App\Models\Produk;

class ProdukFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Produk::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'kategori_id' => rand(1, 8),
            'nama' => $this->faker->sentence(),
            'sku' => $this->faker->unique()->bothify('SKU########'),
            'deskripsi' => $this->faker->paragraph(true),
            'jumlah_stok' => 1000,
            'harga_modal' => $harga_modal = $this->faker->numberBetween(10000, 100000),
            'harga' => $harga_modal + ($harga_modal * (20 / 100)),
        ];
    }
}
