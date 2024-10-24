<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\User;

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
        return [
            'user_id' => User::factory(),
            'pelanggan_id' => Pelanggan::factory(),
            'nomor_pesanan' => $this->faker->word(),
            'nama_pesanan' => $this->faker->word(),
            'diskon' => $this->faker->numberBetween(-10000, 10000),
            'total' => $this->faker->numberBetween(-10000, 10000),
            'keuntungan' => $this->faker->numberBetween(-10000, 10000),
            'metode_pembayaran' => $this->faker->word(),
            'status' => $this->faker->word(),
        ];
    }
}
