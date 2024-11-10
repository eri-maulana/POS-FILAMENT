<?php

namespace App\Filament\Exports;

use App\Models\Pesanan;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;

class PesananExporter extends Exporter
{
    protected static ?string $model = Pesanan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('pelanggan.nama'),
            ExportColumn::make('nomor_pesanan'),
            ExportColumn::make('nama_pesanan'),
            ExportColumn::make('diskon'),
            ExportColumn::make('total'),
            ExportColumn::make('metode_pembayaran')->formatStateUsing(fn($state) => $state->value),
            ExportColumn::make('status')->formatStateUsing(fn($state) => $state->value),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Pesanan kamu sudah berhasil di ekspor dan ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' Gagal di ekspor.';
        }

        return $body;
    }

    public function getFormats(): array
    {
        return [
            ExportFormat::Xlsx,
        ];
    }
}
