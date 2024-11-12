<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Forms;
use Filament\Tables;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\StatusPesanan;
use App\Enums\MetodePembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use App\Filament\Exports\PesananExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\PesananResource\Pages;
use App\Traits\HasNavigationBadge;

class PesananResource extends Resource
{
    use HasNavigationBadge;
    
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

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
                            ->maxDate(fn(Forms\Get $get) => $get('end_date') ?: now())
                            ->native(false),
                        DatePicker::make('created_until')
                            ->native(false)
                            ->maxDate(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Action::make('print')
                    ->button()
                    ->color('gray')
                    ->icon('heroicon-o-printer')
                    ->action(function (Pesanan $record) {
                        $pdf = Pdf::loadView('pdf.print-pesanan', [
                            'pesanan' => $record,
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'receipt-' . $record->nomor_pesanan . '.pdf');
                    }),
                ActionGroup::make([
                    ViewAction::make()
                        ->color('gray'),
                    EditAction::make()
                        ->color('gray'),
                    Action::make('edit-transaksi')
                        ->visible(fn(Pesanan $record) => $record->status === StatusPesanan::TERTUNDA)
                        ->label('Edit Transaksi')
                        ->icon('heroicon-o-pencil')
                        ->url(fn($record) => "/pesanans/{$record->nomor_pesanan}"),
                    Action::make('tandai-telah-dibaca')
                        ->visible(fn(Pesanan $record) => $record->status === StatusPesanan::TERTUNDA)
                        ->requiresConfirmation()
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn(Pesanan $record) => $record->markAsComplete())
                        ->label('Tandai telah selesai'),
                    Action::make('divider')->label('')->disabled(),
                    DeleteAction::make()
                        ->before(function (Pesanan $pesanan) {
                            $pesanan->detailPesanans()->delete();
                            $pesanan->delete();
                        }),
                ])
                    ->color('gray'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (\Illuminate\Support\Collection $records) {
                            $records->each(fn(Pesanan $pesanan) => $pesanan->detailPesanans()->delete());
                        }),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Ekspor Excel')
                    ->fileDisk('public')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->exporter(PesananExporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PesananResource\RelationManagers\DetailPesanansRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
            'view' => Pages\TampilPesanan::route('/{record}/details'),
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
                ->color(fn($state) => $state->getColor()),
            TextColumn::make('user.name')
                ->numeric()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('pelanggan.nama')
                ->numeric()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->formatStateUsing(fn($state) => $state->format('d M Y H:i')),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->formatStateUsing(fn($state) => $state->format('d M Y H:i'))
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist->schema([
            TextEntry::make('nomor_pesanan')->color('gray'),
            TextEntry::make('pelanggan.nama')->placeholder('-'),
            TextEntry::make('diskon')->money('IDR')->color('gray'),
            TextEntry::make('total')->money('IDR')->color('gray'),
            TextEntry::make('metode_pembayaran')->badge()->color('gray'),
            TextEntry::make('status')->badge()->color(fn($state) => $state->getColor()),
            TextEntry::make('created_at')->dateTime()->formatStateUsing(fn($state) => $state->format('d M Y H:i'))->color('gray'),
        ]);
    }
}
