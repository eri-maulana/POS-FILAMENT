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
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\PesananResource\Pages;
use Exception;

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
            ->defaultSort('created_at', 'desc')
            ->columns(self::getTableColumns())
            ->filters([
                SelectFilter::make('status')
                    ->options(StatusPesanan::class),
                SelectFilter::make('metode_pembayaran')
                    ->multiple()
                    ->options(MetodePembayaran::class),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->maxDate(fn (Forms\Get $get) => $get('end_date') ?: now())
                            ->native(false),
                        DatePicker::make('created_until')
                            ->native(false)
                            ->maxDate(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                EditAction::make(),
                Action::make('ubah-transaksi')
                    ->label('Edit Transaksi')
                    ->icon('heroicon-o-pencil')
                    ->url(fn($record) => "/pesanans/{record->nomor_pesanan}"),
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

    public static function getTableColumns(): array
    {
        return [
            TextColumn::make('nomor_pesanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_pesanan')
                    ->searchable(),
                TextColumn::make('diskon')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->alignEnd()
                    ->sortable()
                    ->summarize(
                        Sum::make('total')
                            ->money('IDR'),
                    ),
                TextColumn::make('keuntungan')
                    ->numeric()
                    ->alignEnd()
                    ->sortable()
                    ->summarize(
                        Sum::make('total')
                            ->money('IDR'),
                    )
                    ->sortable(),
                TextColumn::make('metode_pembayaran')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->getColor()),
                TextColumn::make('user.name')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('pelanggan.nama')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state->format('d M Y H:i')),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state->format('d M Y H:i'))
                    ->toggleable(isToggledHiddenByDefault:true),
                ];
    }
}
