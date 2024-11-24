<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Filament\Resources\PembelianResource\RelationManagers;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Supplier;
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
use Illuminate\Support\Arr;
use Filament\Forms\Get;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?int $navigationSort = 4;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('kode')
                    ->label('Kode Transaksi'),
                TextEntry::make('created_at')
                    ->label('Tanggal')
                    ->date('d F Y H:i:s'),
                TextEntry::make('supplier.nama'),
                TextEntry::make('petugas.authable.nama'),
                \Filament\Infolists\Components\ViewEntry::make('barang')
                    ->state(fn(Pembelian $p) => $p->id)
                    ->view('filament.infolists.entries.daftar-barang')
                    ->columnSpanFull(),
                TextEntry::make('total')
                    ->prefix('Rp ')
                    ->numeric(0),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->live()
                    ->inlineLabel(false)
                    ->options(Arr::except(config('custom.lembaga'), [99]))
                    ->columnSpanFull()
                    ->visible(fn(): bool => (auth()->user()->isAdmin())),
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->relationship(name: 'supplier', titleAttribute: 'nama')
                    ->createOptionForm([
                        TextInput::make('nama')
                            ->required(),
                        TextInput::make('hp'),
                        Forms\Components\Textarea::make('alamat')
                            ->columnSpanFull(),
                    ])
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('barang')
                    ->schema([
                        Select::make('barang_id')
                            ->label('Nama Barang')
                            ->options(
                                function (Get $get) {
                                    $options = [];
                                    $lembaga_id = auth()->user()->isAdmin() ? $get('../../lembaga_id') : auth()->user()->authable->lembaga_id;
                                    foreach (
                                        Barang::where('lembaga_id', $lembaga_id)
                                            ->orderBy('nama')
                                            ->get() as $b
                                    ) {
                                        $options[$b->id] = $b->nama . ' - ' . $b->stok . ' ' . $b->satuan . ' - Rp ' . number_format($b->harga, 0, ',', '.');
                                    }
                                    return $options;
                                }
                            )
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('nama')
                                    ->required(),
                                TextInput::make('harga')
                                    ->label('Harga Jual')
                                    ->required()
                                    ->prefix('Rp ')
                                    ->currencyMask('.', ',', 0),
                            ])->createOptionUsing(function (Get $get, array $data) {
                                $data['lembaga_id'] = auth()->user()->isAdmin() ? $get('../../lembaga_id') : auth()->user()->authable->lembaga_id;
                                return Barang::create($data)->getKey();
                            }),
                        TextInput::make('jumlah')
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('harga_beli')
                            ->prefix('Rp')
                            ->currencyMask('.', ',', 0),
                        TextInput::make('harga_jual')
                            ->prefix('Rp')
                            ->currencyMask('.', ',', 0),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->isAdmin()) {
                    return $query->whereRaw('substr(kode,4,1) =' . auth()->user()->authable->lembaga_id);
                }
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->date('d/m/Y H:i')
                    ->label('Tanggal'),
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('supplier.nama')
                    ->searchable(),
                TextColumn::make('total')
                    ->prefix('Rp ')
                    ->numeric(0),
                TextColumn::make('petugas.authable.nama')
                    ->label('Petugas'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-magnifying-glass'),
                // Tables\Actions\EditAction::make()
                //     ->color('warning')
                //     ->label(''),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
            'view' => Pages\ViewPembelian::route('/{record}'),
        ];
    }
}
