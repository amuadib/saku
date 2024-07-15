<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\Penjualan;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as IS;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Section::make()
    //                 ->schema([
    //                     Select::make('siswa_id')
    //                         ->label('Nama Siswa')
    //                         ->options(
    //                             Siswa::when(
    //                                 !auth()->user()->isAdmin(),
    //                                 function ($w) {
    //                                     $w->where('lembaga_id', auth()->user()->authable->lembaga_id);
    //                                 }
    //                             )
    //                                 ->pluck('nama', 'id')
    //                         )
    //                         ->noSearchResultsMessage('Data siswa tidak ditemukan.')
    //                         ->searchable()
    //                         ->live(),
    //                     Forms\Components\DatePicker::make('tanggal')
    //                         ->label('Tanggal Transaksi')
    //                         ->native(false)
    //                         ->displayFormat('d/m/Y')
    //                         ->default(fn (): string => date('Y-m-d')),
    //                     TextInput::make('user_id')
    //                         ->label('Petugas')
    //                         ->default(fn (): string => auth()->user()->authable->nama)
    //                         ->readOnly(),
    //                 ])
    //                 ->columns(3),
    //             Grid::make()
    //                 ->visible(fn (Get $get): bool => $get('siswa_id') != null)
    //                 ->schema([
    //                     Section::make()
    //                         ->schema([
    //                             Select::make('barang_id')
    //                                 ->relationship('barang', 'nama')
    //                                 ->searchable()
    //                                 ->live()
    //                                 ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
    //                                     $barang = Barang::find($state);
    //                                     if ($barang) {
    //                                         $set('harga', $barang->harga);
    //                                         $set('stok', $barang->stok . ' ' . $barang->satuan);
    //                                         $set('total', $barang->harga * $get('jumlah'));
    //                                     }
    //                                 }),
    //                             TextInput::make('harga')
    //                                 ->prefix('Rp ')
    //                                 ->currencyMask('.', ',', 0)
    //                                 ->readOnly(),
    //                             TextInput::make('stok')
    //                                 ->readOnly(),
    //                             TextInput::make('jumlah')
    //                                 ->default(1)
    //                                 ->minValue(1)
    //                                 ->readOnly()
    //                                 ->afterStateUpdated(function (Get $get, Set $set, int $state) {
    //                                     $set('total', intval($get('harga')) * intval($state));
    //                                 })
    //                                 ->suffixActions([
    //                                     Action::make('tambahBarang')
    //                                         ->icon('heroicon-o-plus')
    //                                         ->color('success')
    //                                         ->action(function (Get $get, Set $set, $state) {
    //                                             $set('jumlah', $state + 1);
    //                                             $set('total', intval($get('harga')) * intval($state + 1));
    //                                         }),
    //                                     Action::make('kurangiBarang')
    //                                         ->icon('heroicon-o-minus')
    //                                         ->color('warning')
    //                                         ->action(function (Get $get, Set $set, $state) {
    //                                             if ($state > 1) {
    //                                                 $set('jumlah', $state - 1);
    //                                                 $set('total', intval($get('harga')) * intval($state - 1));
    //                                             }
    //                                         })
    //                                 ]),
    //                             TextInput::make('total')
    //                                 ->prefix('Rp ')
    //                                 ->currencyMask('.', ',', 0)
    //                                 ->readOnly(),
    //                             Actions::make([
    //                                 Action::make('tambahBarang')
    //                                     ->label('Tambah Barang ke Keranjang')
    //                                     ->color('info')
    //                                     ->action(function (Get $get, Set $set) {
    //                                         Keranjang::insert([
    //                                             'id' => Str::orderedUuid(),
    //                                             'siswa_id' => $get('siswa_id'),
    //                                             'barang_id' => $get('barang_id'),
    //                                             'jumlah' => $get('jumlah'),
    //                                             'harga' => $get('harga'),
    //                                             'total' => $get('total'),
    //                                             'created_at' => Carbon::now(),
    //                                             'updated_at' => Carbon::now()
    //                                         ]);
    //                                         $set('barang_id', null);
    //                                         $set('jumlah', null);
    //                                         $set('stok', null);
    //                                         $set('harga', null);
    //                                         $set('total', null);
    //                                     })
    //                                 // ->dispatch('barang-added')
    //                                 ,
    //                             ]),

    //                         ])
    //                         ->columnSpan(1),
    //                     Section::make()
    //                         ->schema([
    //                             Forms\Components\ViewField::make('keranjang')
    //                                 ->view('filament.forms.components.keranjang'),
    //                         ])
    //                         ->columnSpan(3)
    //                 ])
    //                 ->columns(4),
    //             // Forms\Components\Select::make('siswa_id')
    //             //     ->relationship('siswa', 'id')
    //             //     ->required(),
    //         ]);
    // }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                IS::make()
                    ->schema([
                        TextEntry::make('transaksi.kode')
                            ->label('Kode Transaksi')
                            ->weight('bold'),
                        TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d/m/Y H:i:s')
                            ->weight('bold'),
                        TextEntry::make('siswa.nama')
                            ->label('Siswa')
                            ->weight('bold'),
                        TextEntry::make('transaksi.petugas.authable.nama')
                            ->label('Petugas')
                            ->weight('bold'),
                    ])
                    ->columns(2),
                IS::make()
                    ->schema([
                        Infolists\Components\ViewEntry::make('detail')
                            // ->schema([
                            //     TextEntry::make('barang.nama'),
                            //     TextEntry::make('jumlah'),
                            //     TextEntry::make('harga'),
                            //     TextEntry::make('total'),
                            // ])
                            // ->columns(4)
                            ->view('infolists.components.tabel-barang')
                            ->columnSpanFull(),
                    ]),
                // IS::make()
                //     ->schema([
                //         Infolists\Components\ViewEntry::make('total_belanja')
                //             ->view('infolists.components.tabel-total', ['data' => $this->record])
                //             ->columnSpanFull(),
                //     ])
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('transaksi.kode')
                    ->label('Kode'),
                TextColumn::make('siswa.nama')
                    ->searchable(),
                TextColumn::make('total')
                    ->prefix('Rp ')
                    ->numeric(0),
                TextColumn::make('pembayaran')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => config('custom.pembayaran')[$state])
                    ->color(fn (string $state): string => match ($state) {
                        'tun' => 'success',
                        'tag' => 'danger',
                        'tab' => 'info',
                    }),
                Tables\Columns\TextColumn::make('petugas.authable.nama')
                    ->label('Admin')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'view' => Pages\ViewPenjualan::route('/{record}'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
