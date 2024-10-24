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
            'kategori_id' => Kategori::factory(),
            'gambar' => $this->faker->word(),
            'nama' => $this->faker->word(),
            'sku' => $this->faker->word(),
            'deskripsi' => $this->faker->text(),
            'jumlah_stok' => $this->faker->numberBetween(-10000, 10000),
            'harga' => $this->faker->numberBetween(-10000, 10000),
            'harga_modal' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
