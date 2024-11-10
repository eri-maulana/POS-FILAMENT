<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\PenyesuaianStokObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(PenyesuaianStokObserver::class)]
class PenyesuaianStok extends Model
{
    use HasFactory;

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
