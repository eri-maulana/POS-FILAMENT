<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\PenyesuaianStok;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
        ]);

        Pelanggan::factory(50)->create();
        Produk::factory(50)->create();
        PenyesuaianStok::factory(50)->create();
        Pesanan::factory(50)->create();
    }
}
