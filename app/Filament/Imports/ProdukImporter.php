<?php

namespace App\Filament\Imports;

use App\Models\Produk;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProdukImporter extends Importer
{
    protected static ?string $model = Produk::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('gambar')
                ->rules(['max:255']),
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('sku')
                ->label('SKU')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('deskripsi')
                ->rules(['max:65535']),
            ImportColumn::make('jumlah_stok')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('harga')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('harga_modal')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

        ];
    }

    public function resolveRecord(): ?Produk
    {
        return Produk::firstOrNew([
            'sku' => $this->data['sku'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Kamu telah berhasil import data dan ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
