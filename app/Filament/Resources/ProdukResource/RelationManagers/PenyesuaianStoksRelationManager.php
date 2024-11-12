<?php

namespace App\Filament\Resources\ProdukResource\RelationManagers;

use App\Filament\Resources\PenyesuaianStokResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PenyesuaianStoksRelationManager extends RelationManager
{
    protected static string $relationship = 'penyesuaianStoks';

    public function form(Form $form): Form
    {
        return PenyesuaianStokResource::form($form);
    }

    public function table(Table $table): Table
    {
        return PenyesuaianStokResource::table($table);
    }
}
