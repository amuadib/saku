<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Kas;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Filament\Forms\Get;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';

    public static function form(Form $form): Form
    {
        $lembaga = config('custom.lembaga');
        return $form
            ->schema([
                Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->inlineLabel(false)
                    ->options(Arr::except($lembaga, [99]))
                    ->visible(fn (): bool => (auth()->user()->isAdmin()))
                    ->live(),
                Radio::make('kas_id')
                    ->label('Kas')
                    ->options(
                        function (Get $get) use ($lembaga) {
                            $data = [];
                            $lembaga_id = auth()->user()->isAdmin() ? $get('lembaga_id') : auth()->user()->authable->lembaga_id;
                            foreach (Kas::getDaftarKas($lembaga_id)->get() as $k) {
                                $data[$k->id] = $k->nama . ' - ' . $lembaga[$k->lembaga_id];
                                if (is_array($k->jenis_transaksi)) {
                                    foreach ($k->jenis_transaksi as $j) {
                                        $jenis_transaksi[] = $j;
                                    }
                                }
                            }
                            return $data;
                        }
                    )
                    ->required(),
                Radio::make('mutasi')
                    ->options(['m' => 'Uang Masuk', 'k' => 'Uang Keluar'])
                    ->inline()
                    ->inlineLabel(false)
                    ->required(),
                Forms\Components\TextInput::make('jumlah')
                    ->prefix('Rp')
                    ->required()
                    ->currencyMask('.', ',', 0),
                Forms\Components\Textarea::make('keterangan'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d/m/Y H:i'),
                TextColumn::make('kode')
                    ->label('Kode Transaksi'),
                TextColumn::make('keterangan')
                    ->searchable()
                    ->lineClamp(2),
                TextColumn::make('jumlah')
                    ->color(function (Transaksi $record): string {
                        return $record->kode[0] == 'M' ? 'success' : 'danger';
                    })
                    ->icon(function (Transaksi $record): string {
                        return $record->kode[0] == 'M' ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down';
                    })
                    ->prefix('Rp ')
                    ->numeric(0),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Transaksi $record) {

                        if ($record->jenis() == 'Tagihan') { //TG
                            $tagihan = $record->transable;
                            $kas = $tagihan->kas;
                            $tagihan->update(['bayar' => 0]);
                        } elseif ($record->jenis() == 'Tabungan') { //TB
                            $tabungan = $record->transable;
                            $kas = $tabungan->kas;
                            if ($record->kode[0] == 'K') {
                                $tabungan->increment('saldo', $record->jumlah);
                            } else {
                                $tabungan->decrement('saldo', $record->jumlah);
                            }
                        } else {
                            $kas = $record->transable;
                        }

                        //Kas

                        if ($record->kode[0] == 'K') {
                            $kas->increment('saldo', $record->jumlah);
                        } else {
                            $kas->decrement('saldo', $record->jumlah);
                        }
                    }),
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'view' => Pages\ViewTransaksi::route('/{record}'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
