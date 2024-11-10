<?php

namespace App\Filament\Resources\PesananResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DetailPesanan;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailPesanansRelationManager extends RelationManager
{
    protected static string $relationship = 'detailPesanans';


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_pesanan')
            ->columns([
                TextColumn::make('produk.nama')
                    ->searchable(),
                TextColumn::make('harga')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix(fn(DetailPesanan $record) => $record->kuantitas . ' x ')
                    ->alignEnd(),
                TextColumn::make('subtotal')
                    ->numeric()
                    ->alignEnd(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
