<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as IS;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 3;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                IS::make()
                    ->schema([
                        TextEntry::make('kode')
                            ->label('Kode Transaksi')
                            ->weight('bold'),
                        TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d/m/Y H:i:s')
                            ->weight('bold'),
                        TextEntry::make('siswa.nama')
                            ->label('Siswa')
                            ->weight('bold'),
                        TextEntry::make('petugas.authable.nama')
                            ->label('Petugas')
                            ->weight('bold'),
                    ])
                    ->columns(2),
                IS::make()
                    ->schema([
                        Infolists\Components\ViewEntry::make('detail')
                            ->view('infolists.components.tabel-barang')
                            ->columnSpanFull(),
                    ]),
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
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('kode')
                    ->label('Kode'),
                TextColumn::make('siswa.nama')
                    ->searchable(),
                TextColumn::make('total')
                    ->prefix('Rp ')
                    ->numeric(0),
                TextColumn::make('pembayaran')
                    ->badge()
                    ->formatStateUsing(fn($state): string => config('custom.pembayaran')[$state])
                    ->color(fn(string $state): string => match ($state) {
                        'tun' => 'success',
                        'tag' => 'danger',
                        'tab' => 'info',
                    }),
                TextColumn::make('petugas.authable.nama')
                    ->label('Petugas')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('tagihan')
                    ->icon('heroicon-o-banknotes')
                    ->url(function (Penjualan $p) {
                        return TagihanResource::getUrl('view', [$p->tagihan->id]);
                    })
                    ->visible(fn(Penjualan $p): bool => $p->tagihan !== null),
                Tables\Actions\ViewAction::make(),
            ]);
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
