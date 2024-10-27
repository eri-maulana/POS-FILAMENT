<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenyesuaianStokResource\Pages;
use App\Filament\Resources\PenyesuaianStokResource\RelationManagers;
use App\Models\PenyesuaianStok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenyesuaianStokResource extends Resource
{
    protected static ?string $model = PenyesuaianStok::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produk_id')
                    ->relationship('produk', 'id')
                    ->required(),
                Forms\Components\TextInput::make('kuantitas_disesuaikan')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('alasan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuantitas_disesuaikan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePenyesuaianStoks::route('/'),
        ];
    }
}
