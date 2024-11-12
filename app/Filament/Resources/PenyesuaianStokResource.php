<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PenyesuaianStok;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\PenyesuaianStokResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers\PenyesuaianStoksRelationManager;
use App\Traits\HasNavigationBadge;

class PenyesuaianStokResource extends Resource
{
    use HasNavigationBadge;
    
    protected static ?string $model = PenyesuaianStok::class;

    protected static ?string $navigationLabel = 'Penyesuaian Stok';

    protected static ?string $navigationGroup = 'Stok';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produk_id')
                    ->relationship('produk', 'nama')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hiddenOn(PenyesuaianStoksRelationManager::class),
                Forms\Components\TextInput::make('kuantitas_disesuaikan')
                    ->required()
                    ->label('Ubah jumlah stok (dikurang / ditambah)')
                    ->numeric(),
                Forms\Components\Textarea::make('alasan')
                    ->default('Restock.')
                    ->maxLength(65535)
                    ->placeholder('Alasan menyesuaiakan stok')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama')
                    ->sortable()
                    ->hiddenOn(PenyesuaianStoksRelationManager::class),
                Tables\Columns\TextColumn::make('kuantitas_disesuaikan')
                    ->label('Disesuaikan')
                    ->suffix(' Kuantitas')
                    ->color('gray')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alasan')
                    ->limit(50)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('produk_id')
                    ->relationship('produk', 'nama')
                    ->searchable()
                    ->preload()
                    ->hiddenOn(PenyesuaianStoksRelationManager::class),
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
