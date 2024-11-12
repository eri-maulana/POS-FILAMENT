<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Actions;
use App\Filament\Imports\ProdukImporter;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProdukResource;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(ProdukImporter::class)
                ->icon('heroicon-o-document-arrow-down'),
        ];
    }
}
