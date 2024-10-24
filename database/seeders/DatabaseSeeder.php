<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\PenyesuaianStok;
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
    }
}
