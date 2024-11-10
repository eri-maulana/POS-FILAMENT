<?php

namespace App\Observers;

use App\Models\PenyesuaianStok;
use Illuminate\Support\Facades\DB;

class PenyesuaianStokObserver
{
    public function created(PenyesuaianStok $penyesuaianStok){
        $produk = $penyesuaianStok->produk;
        $produk->jumlah_stok += $penyesuaianStok->kuantitas_disesuaikan;
        $produk->save();
    }

    public function deleting(PenyesuaianStok $penyesuaianStok): void
    {
        DB::transaction(function() use ($penyesuaianStok){
            $produk = $penyesuaianStok->produk;
            $produk->jumlah_stok -= $penyesuaianStok->kuantitas_disesuaikan;
            $produk->save();
        });
    } 
}
