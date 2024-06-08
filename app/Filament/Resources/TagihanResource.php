<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Filament\Resources\TagihanResource\RelationManagers;
use App\Models\Kas;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Illuminate\Support\Arr;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;
    // protected static ?string $recordTitleAttribute = 'siswa.nama';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        $lembaga = config('custom.lembaga');
        return $form
            ->schema([
                Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->inlineLabel(false)
                    ->options(Arr::except(config('custom.lembaga'), [99]))
                    ->live()
                    ->visible(fn (): bool => (auth()->user()->isAdmin())),
                Select::make('kas_id')
                    ->label('Jenis Tagihan')
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
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->currencyMask('.', ',', 0),
                Forms\Components\Textarea::make('keterangan'),
                Radio::make('peserta')
                    ->options([
                        'Semua siswa', 'Kelas', 'Hanya Siswa'
                    ])
                    ->inline()
                    ->inlineLabel(false)
                    ->live(),
                Select::make('siswa_id')
                    ->label('Nama Siswa')
                    ->options(
                        function (Get $get): array {
                            $data = [];
                            $lembaga_id = auth()->user()->isAdmin() ? $get('lembaga_id') : auth()->user()->authable->lembaga_id;
                            foreach (Siswa::getDaftarSiswa($lembaga_id)->get() as $k) {
                                $data[$k->id] = $k->nama;
                            }
                            return $data;
                        }
                    )
                    ->noSearchResultsMessage('Data siswa tidak ditemukan.')
                    ->searchable()
                    ->visible(fn (Get $get): bool => ($get('peserta') == 2)),
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(
                        function (Get $get) use ($lembaga): array {
                            $data = [];
                            $lembaga_id = auth()->user()->isAdmin() ? $get('lembaga_id') : auth()->user()->authable->lembaga_id;
                            foreach (Kelas::getDaftarKelas($lembaga_id)->get() as $k) {
                                $data[$k->id] = $k->nama . ' - ' . $k->nama_periode . ' - ' . $lembaga[$k->lembaga_id];
                            }
                            return $data;
                        }
                    )
                    ->visible(fn (Get $get): bool => ($get('peserta') == 1)),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->isAdmin()) {
                    return $query
                        ->join('siswa', 'siswa.id', '=', 'siswa_id')
                        ->where('siswa.lembaga_id', auth()->user()->authable->lembaga_id)
                        ->select('tagihan.*');
                }
            })
            ->defaultSort('tagihan.created_at', 'desc')
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d/m/Y'),
                TextColumn::make('siswa.nama')
                    ->searchable(),
                TextColumn::make('siswa.kelas.nama')
                    ->label('Kelas'),
                TextColumn::make('kas.nama')
                    ->label('Tagihan'),
                TextColumn::make('jumlah')
                    ->prefix('Rp ')
                    ->numeric(0),
                TextColumn::make('lunas')
                    ->badge()
                    ->state(function (Tagihan $record): string {
                        if ($record->bayar > 0 and $record->bayar == $record->jumlah) {
                            return 'Lunas';
                        }
                        return 'Belum';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Belum' => 'danger',
                    }),
                TextColumn::make('keterangan'),
                TextColumn::make('petugas.authable.nama')
                    ->label('Petugas'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('siswa.nama')
                    ->label('Nama'),
                TextEntry::make('kelas')
                    ->state(fn (Tagihan $record): string => "{$record->siswa->kelas->nama} " . config('custom.lembaga')[$record->siswa->lembaga_id]),
                TextEntry::make('created_at')
                    ->label('Tanggal')
                    ->date('d/m/Y'),
                TextEntry::make('kas.nama')
                    ->label('Tagihan'),
                TextEntry::make('keterangan'),
                TextEntry::make('jumlah')
                    ->money('IDR'),
                TextEntry::make('status')
                    ->badge()
                    ->state(function (Tagihan $record): string {
                        if ($record->bayar > 0 and $record->bayar == $record->jumlah) {
                            return 'Lunas';
                        }
                        return 'Belum dibayar';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Belum dibayar' => 'danger',
                    }),
                Actions::make([
                    Action::make('bayar_tagihan')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Tagihan $record) {
                            $jumlah = $record->jumlah;
                            $record->update(['bayar' => $jumlah]);
                            $record->kas->increment('saldo', $jumlah);
                        })
                ])
                    ->hidden(fn (Tagihan $record): bool => $record->bayar > 0 and $record->bayar == $record->jumlah)
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
            'index' => Pages\ListTagihans::route('/'),
            'create' => Pages\CreateTagihan::route('/create'),
            'view' => Pages\ViewTagihan::route('/{record}'),
            // 'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }
}
