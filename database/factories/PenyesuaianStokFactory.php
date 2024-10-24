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
        $produkId = Produk::query()->inRandomOrder()->value('id');
        $kuantitas_disesuikan = $this->faker->numberBetween(-50, 50);
    
        return [
            'produk_id' => $produkId,
            'kuantitas_disesuaikan' => $kuantitas_disesuikan,
            'alasan' => $this->faker->sentence,
        ];
    }
    
    public function configure(): PenyesuaianStokFactory
    {
        return $this->afterCreating(function (PenyesuaianStok $penyesuaian) {
            $produk = $penyesuaian->produk;
            $produk->stock_quantity += $penyesuaian->kuantitas_disesuaikan;
            $produk->save();
        });
    }
}