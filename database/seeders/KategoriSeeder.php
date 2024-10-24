<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            'Laptop',
            'PC',
            'Komponen PC',
            'Aksesoris PC & Laptop',
            'Perangkat Penyimpanan',
            'Jaringan & Konektivitas',
            'Perangkat Lunak',
            'Lainnya'
        ])->each(fn($kategori)=> Kategori::query()->create(['nama' => $kategori]));
    }
}
