<?php

namespace App\Filament\Resources\PenyesuaianStokResource\Pages;

use App\Filament\Resources\PenyesuaianStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePenyesuaianStoks extends ManageRecords
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
