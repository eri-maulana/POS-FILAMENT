<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\PenyesuaianStok;
use App\Models\Produk;

class PenyesuaianStokFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PenyesuaianStok::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'produk_id' => Produk::factory(),
            'kuantitas_disesuaikan' => $this->faker->numberBetween(-10000, 10000),
            'alasan' => $this->faker->text(),
        ];
    }
}
