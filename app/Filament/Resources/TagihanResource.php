<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Filament\Resources\TagihanResource\RelationManagers;
use App\Models\Kas;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Transaksi;
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
use Filament\Infolists\Components\Fieldset;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;
    // protected static ?string $recordTitleAttribute = 'siswa.nama';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
                    ->live()
                    ->visible(fn (): bool => (auth()->user()->isAdmin())),
                Select::make('kas_id')
                    ->label('Jenis Tagihan')
                    ->options(
                        function (Get $get) use ($lembaga) {
                            $data = [];
                            $lembaga_id = auth()->user()->isAdmin() ? $get('lembaga_id') : auth()->user()->authable->lembaga_id;
                            foreach (Kas::getDaftarTagihan($lembaga_id)->get() as $k) {
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
                    ->prefix('Rp ')
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
                Fieldset::make('Detail Tagihan')
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
                    ]),
                Fieldset::make('Pembayaran')
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->state(function (Tagihan $record): string {
                                if ($record->isLunas()) {
                                    return 'Lunas';
                                }
                                return 'Belum dibayar';
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'Lunas' => 'success',
                                'Belum dibayar' => 'danger',
                            }),
                        TextEntry::make('transaksi.id')
                            ->visible(fn (Tagihan $record): bool => $record->isLunas()),
                        TextEntry::make('transaksi.created_at')
                            ->label('Tanggal Bayar')
                            ->date('d/m/Y')
                            ->visible(fn (Tagihan $record): bool => $record->isLunas()),
                        TextEntry::make('transaksi.petugas.authable.nama')
                            ->label('Petugas')
                            ->visible(fn (Tagihan $record): bool => $record->isLunas()),
                        Actions::make([
                            Action::make('bayar_tagihan')
                                ->icon('heroicon-o-banknotes')
                                ->color('success')
                                // ->requiresConfirmation()
                                ->form([
                                    Radio::make('pembayaran')
                                        ->options(function (Tagihan $record) {
                                            $data = ['tun' => 'Tunai'];
                                            if ($record->siswa->tabungan) {
                                                foreach ($record->siswa->tabungan as $t) {
                                                    $data[$t->id] = $t->kas->nama;
                                                }
                                            }
                                            return $data;
                                        })
                                        ->inline()
                                        ->inlineLabel(false)
                                        ->required(),
                                ])
                                ->action(function (Tagihan $record) {
                                    $jumlah = $record->jumlah;
                                    $record->update(['bayar' => $jumlah]);

                                    //update Kas
                                    $record->kas->increment('saldo', $jumlah);

                                    //Input Transaksi
                                    \App\Traits\TransaksiTrait::inputTransaksi(
                                        mutasi: 'm',
                                        jenis: 'TG',
                                        id: $record->id,
                                        jumlah: $jumlah,
                                        keterangan: $record->keterangan != '' ? 'Pembayaran tagihan ' . $record->kas->nama . ' '  . $record->keterangan . ' ' . $record->siswa->nama : ''
                                    );
                                    // Transaksi::insert([
                                    //     // 'id' => Str::orderedUuid(),
                                    //     'id' => \App\Traits\TransaksiTrait::getIdTransaksi('MTG'),
                                    //     'mutasi' => 'm',
                                    //     'jumlah' => $jumlah,
                                    //     'transable_type' => 'App\\Models\\Tagihan',
                                    //     'transable_id' => $record->id,
                                    //     'keterangan' => ,
                                    //     'user_id' => \Auth::user()->id,
                                    //     'created_at' => \Carbon\Carbon::now(),
                                    //     'updated_at' => \Carbon\Carbon::now(),
                                    // ]);
                                })
                                ->before(function (Action $action, Tagihan $tagihan, array $data) {
                                    if ($data['pembayaran'] == 'tab') {
                                        dd($tagihan->siswa->tabungan);
                                    }
                                })
                                ->successNotificationTitle('Pembayaran berhasil!')
                        ])
                            ->hidden(fn (Tagihan $record): bool => $record->isLunas())
                    ])
                    ->columns(3),
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
