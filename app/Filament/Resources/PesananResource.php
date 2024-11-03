<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\StatusPesanan;
use App\Enums\MetodePembayaran;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PesananResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PesananResource\RelationManagers;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pesanan')->schema([
                    TextInput::make('nomor_pesanan')
                        ->required()
                        ->default(generateSequentialNumber(Pesanan::class))
                        ->readOnly(),
                    TextInput::make('nama_pesanan')
                        ->maxLength(255)
                        ->placeholder('Tulis Nama Pesanan'),
                    TextInput::make('total')
                        ->readOnlyOn('create')
                        ->default(0)
                        ->numeric(),
                    Select::make('pelanggan_id')
                        ->relationship('pelanggan', 'nama')
                        ->searchable()
                        ->preload()
                        ->label('Pelanggan (optional)')
                        ->placeholder('Pilih Pelanggan'),
                    Group::make([
                        Select::make('metode_pembayaran')
                            ->enum(MetodePembayaran::class)
                            ->options(MetodePembayaran::class)
                            ->default(MetodePembayaran::TUNAI)
                            ->required(),
                        Select::make('status')
                            ->required()
                            ->enum(StatusPesanan::class)
                            ->options(StatusPesanan::class)
                            ->default(StatusPesanan::TERTUNDA),
                    ])->columnSpan(2)->columns(2),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pelanggan.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diskon')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keuntungan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
            'buat-transaksi' => Pages\BuatTransaksi::route('{record}'),
        ];
    }
}
