<?php

namespace App\Filament\Resources\PesananResource\Pages;

use Filament\Actions;
use App\Models\Pesanan;
use App\Enums\StatusPesanan;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PesananResource;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $statuses = collect([
            'semua' => ['label' => 'SEMUA', 'badgeColor' => 'primary', 'status' => null],
            StatusPesanan::TERTUNDA->name => ['label' => 'Tertunda', 'badgeColor' => 'warning', 'status' => StatusPesanan::TERTUNDA],
            StatusPesanan::SELESAI->name => ['label' => 'Selesai', 'badgeColor' => 'success', 'status' => StatusPesanan::SELESAI],
            StatusPesanan::DIBATALKAN->name => ['label' => 'Dibatalkan', 'badgeColor' => 'danger', 'status' => StatusPesanan::DIBATALKAN],
        ]);

        return $statuses->mapWithKeys(function ($data, $key){
            $badgeCount = is_null($data['status'])
                ? Pesanan::count()
                : Pesanan::where('status', $data['status'])->count();

            return [$key => Tab::make($data['label'])
                ->badge($badgeCount)
                ->modifyQueryUsing(fn ($query) => is_null($data['status']) ? $query : $query->where('status', $data['status']))
                ->badgeColor($data['badgeColor'])];
        })->toArray();
    }
}
