<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Kas;
use App\Models\Kelas;
use App\Models\Periode;
use App\Models\Siswa;
use App\Models\Tabungan;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Arr;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Actions\Action;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    protected static ?string $recordTitleAttribute = 'nama_siswa';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->nullable()
                    ->image()
                    ->imageEditor()
                    ->directory('foto'),
                Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->inlineLabel(false)
                    ->options(Arr::except(config('custom.lembaga'), [99]))
                    ->required()
                    ->live()
                    ->visible(fn(): bool => (auth()->user()->isAdmin())),

                Forms\Components\ViewField::make('lembaga_id')
                    ->view('filament.forms.components.view_only', [
                        'label' => 'Lembaga',
                        'value' => config('custom.lembaga')[auth()->user()->authable->lembaga_id],
                    ])
                    ->hidden(fn(): bool => (auth()->user()->isAdmin())),

                Forms\Components\Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(
                        function (Get $get, string $operation): array {
                            $data = [];
                            $lembaga_id = auth()->user()->isAdmin() ? $get('lembaga_id') : auth()->user()->authable->lembaga_id;
                            foreach (Kelas::getDaftarKelas($lembaga_id, $operation)->get() as $k) {
                                $data[$k->id] = $k->nama . ' - ' . $k->nama_periode;
                            }
                            return $data;
                        }
                    )
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                Radio::make('jenis_kelamin')
                    ->options(['l' => 'Laki-laki', 'p' => 'Perempuan'])
                    ->inline()
                    ->inlineLabel(false)
                    ->required(),
                TextInput::make('nis')
                    ->label('NIS')
                    ->placeholder('Nomor Induk Siswa'),
                TextInput::make('nisn')
                    ->label('NISN')
                    ->placeholder('Nomor Induk Siswa Nasional'),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required(),
                TextInput::make('tempat_lahir'),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('nama_ayah'),
                TextInput::make('nama_ibu'),
                TextInput::make('telepon')
                    ->helperText(new HtmlString('Nomor Whatsapp. Gunakan Format: <strong>081234567891</strong>. Untuk nomor luar negeri, tambahkan <strong><i>+[Kode Negara]</i></strong>: <strong>+8581234567891</strong>')),
                TextInput::make('email')
                    ->email(),
                Radio::make('status')
                    ->options(config('custom.siswa.status'))
                    ->inline()
                    ->inlineLabel(false)
                    ->default(99),
                Forms\Components\CheckboxList::make('label')
                    ->options(config('custom.siswa.label'))
                    ->columns(2)
                    ->gridDirection('row'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->modifyQueryUsing(function (Builder $query) {
                // $query = $query->with('kelas', 'periode');
                if (!auth()->user()->isAdmin()) {
                    $query = $query->where('siswa.lembaga_id', auth()->user()->authable->lembaga_id);
                }
                // $query = $query
                // ->join('kelas', 'kelas.id', '=', 'kelas_id')
                // ->join('periode', 'periode.id', '=', 'periode_id')
                // ->where('periode.aktif', 'y');
                return $query;
            })
            ->defaultSort('siswa.nama')
            ->columns([
                TextColumn::make('kelas.nama')
                    ->label('Kelas'),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('nama_ibu')
                    ->label('Ibu'),
                TextColumn::make('alamat')
                    ->wrap(),
                TextColumn::make('telepon')
                    ->hidden(fn(): bool => auth()->user()->isAdmin()),
                Tables\Columns\TextInputColumn::make('telepon')
                    ->visible(fn(): bool => auth()->user()->isAdmin()),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => config('custom.siswa.status')[$state])
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                        '3' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('label_filter')
                    ->form([
                        Forms\Components\CheckboxList::make('label')
                            ->columns(2)
                            ->gridDirection('row')
                            ->options(config('custom.siswa.label'))
                    ])
                    ->query(fn(Builder $query, array $data): Builder => $query->whereJsonContains('label', $data['label']))
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['label']) {
                            return null;
                        }

                        return 'Siswa termasuk ' . config('custom.siswa.label')[$data['label'][0]];
                    }),
                SelectFilter::make('lembaga_id')
                    ->label('Lembaga')
                    ->options(Arr::except(config('custom.lembaga'), [99]))
                    ->visible(auth()->user()->isAdmin()),
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->multiple()
                    ->preload()
                    ->options(
                        function (): array {
                            $data = [];
                            $lembaga = Arr::except(config('custom.lembaga'), [99]);
                            $periode = Periode::where('aktif', 1)->first();
                            $lembaga_id = auth()->user()->isAdmin() ? null : auth()->user()->authable->lembaga_id;

                            if ($periode and $periode->kelas->count() > 0) {
                                foreach ($periode->kelas as $k) {
                                    if ($lembaga_id !== null and $lembaga_id != $k->lembaga_id) {
                                        continue;
                                    }
                                    $data[$k->id] = $k->nama . ' - ' . $periode->nama . ' - ' . explode(' ', $lembaga[$k->lembaga_id])[0];
                                }
                            }
                            return $data;
                        }
                    ),
                SelectFilter::make('status')
                    ->default('1')
                    ->options(config('custom.siswa.status')),
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\Action::make('belanja')
                    ->label('')
                    ->url(fn(Siswa $s): string => SiswaResource::getUrl('belanja', ['record' => $s]))
                    ->color('success')
                    ->icon('heroicon-o-shopping-cart')
                    ->visible(fn(): bool => auth()->user()->isTataUsaha() or auth()->user()->isAdmin()),
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->color('warning'),
            ])
            ->bulkActions([
                BulkAction::make('kirim_tagihan')
                    ->color('info')
                    ->icon('heroicon-o-banknotes')
                    ->action(
                        function (Collection $records) {
                            $pesan = [];
                            foreach ($records as $s) {
                                if ($s->telepon == '') {
                                    continue;
                                }
                                $nomor = env('APP_ENV') == 'local' ? env('WHATSAPP_TEST_NUMBER') : '' . $s->telepon;
                                $rincian = '';
                                $no = 1;
                                $total = 0;
                                foreach ($s->tagihan as $t) {
                                    if (!$t->isLunas()) {
                                        $tgl = $t->updated_at == null ? $t->created_at->format('d-m-Y') : $t->updated_at->format('d-m-Y');
                                        // $rincian .= $no . '. ' . $t->kas->nama . ' ' . $t->keterangan . ' Rp ' . number_format($t->jumlah, thousands_separator: '.') . PHP_EOL;
                                        $rincian .= $no . '. ' . ucfirst($t->keterangan) . ' (' . $tgl . '): Rp ' . number_format($t->jumlah, thousands_separator: '.') . PHP_EOL;
                                        $total += $t->jumlah;
                                        $no++;
                                    }
                                }

                                //Lewati untuk tagihan sudah lunas / belum ada tagihan
                                if ($total == 0) {
                                    continue;
                                }

                                $pesan[] = [
                                    'name' => $s->nama,
                                    'number' => $nomor,
                                    'message' => \App\Services\WhatsappService::prosesPesan(
                                        siswa: $s,
                                        data: [
                                            'lembaga' => config('custom.lembaga.' . $s->lembaga_id),
                                            'kontak.nama' => config('custom.kontak_lembaga.' . $s->lembaga_id . '.kontak'),
                                            'tagihan.rincian' => $rincian,
                                            'tagihan.total' => 'Rp ' . number_format($total, thousands_separator: '.'),
                                        ],
                                        jenis: $s->status == 3 ? 'tagihan.daftar_alumni' : 'tagihan.daftar'
                                    ),
                                    'sessionId' => \App\Services\WhatsappService::getSessionId($s)
                                ];
                            }

                            if (count($pesan) > 0) {
                                \App\Services\WhatsappService::kirimWa(
                                    kumpulan_pesan: $pesan
                                );
                                Notification::make()
                                    ->title('Data Tagihan siswa terpilih telah dikirimkan')
                                    ->icon('heroicon-o-check-circle')
                                    ->iconColor('success')
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Tagihan untuk siswa tidak ditemukan')
                                    ->icon('heroicon-o-check-circle')
                                    ->iconColor('warning')
                                    ->send();
                            }
                        }
                    )
                    ->visible(fn(): bool => env('WHATSAPP_NOTIFICATION')),
                Tables\Actions\ExportBulkAction::make()
                    ->label('Ekspor')
                    ->exporter(\App\Filament\Exports\SiswaExporter::class)
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-up'),
                BulkAction::make('ubah_data_siswa')
                    ->label('Ubah Data')
                    ->color('warning')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(
                                function (): array {
                                    $data = [];
                                    foreach (Kelas::getDaftarKelas(auth()->user()->authable->lembaga_id)->get() as $k) {
                                        $data[$k->id] = $k->nama . ' - ' . $k->nama_periode;
                                    }
                                    return $data;
                                }
                            )
                    ])
                    ->action(function (Collection $records) use ($table) {
                        $data = $table->getLivewire()->getMountedTableBulkActionForm()->getState();
                        $siswa_ids = [];
                        foreach ($records as $s) {
                            $siswa_ids[] = $s->id;
                        }
                        Siswa::whereIn('id', $siswa_ids)
                            ->update([
                                'kelas_id' => $data['kelas_id']
                            ]);
                        Notification::make()
                            ->title('Data siswa terpilih berhasil diperbarui')
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                            ->send();
                    }),
                BulkAction::make('kenaikan_kelas')
                    ->color('danger')
                    ->icon('heroicon-o-arrow-turn-right-up')
                    ->action(function (Collection $siswa) {
                        $data = [];
                        $kelas = [];
                        $lulus = [];
                        $aktif = Periode::where('aktif', true)->first();
                        if ($aktif) {
                            $periode_aktif = substr($aktif->nama, 0, 4);
                        } else {
                            Notification::make()
                                ->title('Periode Aktif belum diatur')
                                ->icon('heroicon-o-exclamation-triangle')
                                ->iconColor('warning')
                                ->send();
                            return;
                        }

                        foreach ($siswa as $s) {
                            if ($s->status > 1) { // if status != aktif, lewati
                                continue;
                            }
                            //CEK LOGIKA LULUS
                            if (
                                $s->kelas->tingkat == max(config('custom.tingkat')[$s->lembaga_id]) and
                                $s->status != 3 and
                                $s->kelas->periode->id != $aktif->id
                            ) { //if Siswa mempunyai kelas tertinggi dan status belum lulus, luluskan
                                $lulus[] = $s->id;
                            } else {
                                if ($periode_aktif - substr($s->kelas->periode->nama, 0, 4) == 1) { // TA kelas Siswa adalah TA kemarin
                                    $id_kelas = $s->lembaga_id . '-' . $s->kelas->tingkat . '-' . $s->kelas->nama;
                                    $data[] = ['id' => $s->id, 'kelas_id' => $id_kelas];
                                    $kelas[$id_kelas] = [
                                        'lembaga_id' => $s->lembaga_id,
                                        'tingkat' => $s->kelas->tingkat,
                                        'nama' => $s->kelas->nama,
                                    ];
                                }
                            }
                        }

                        if (count($lulus)) { //luluskan siswa
                            Siswa::whereIn('id', $lulus)
                                ->update(['status' => 3]);
                        }

                        if (!count($data)) {
                            Notification::make()
                                ->title('Data Siswa terpilih tidak memenuhi syarat untuk Naik Kelas')
                                ->icon('heroicon-o-exclamation-triangle')
                                ->iconColor('warning')
                                ->send();
                            return;
                        } else {
                            $result = \App\Services\KelasService::prosesKenaikanKelas($aktif->id, $kelas, $data);
                            if ($result > 0) {
                                Notification::make()
                                    ->title($result . 'Siswa berhasil Naik Kelas')
                                    ->icon('heroicon-o-check-circle')
                                    ->iconColor('success')
                                    ->send();
                            }
                        }
                    }),
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Hapus'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $lembaga = config('custom.lembaga');
        return $infolist
            ->schema([
                Grid::make(4)
                    ->schema([
                        Grid::make()
                            ->schema([
                                Infolists\Components\ImageEntry::make('foto')
                                    ->label('')
                                    ->width(170)
                                    ->height(230)
                                    ->defaultImageUrl(url('/storage/no_photo.jpg')),
                            ])->columnSpan([
                                'sm' => 4,
                                'md' => 1
                            ]),
                        Section::make()
                            ->schema([
                                TextEntry::make('nama')
                                    ->weight('bold'),
                                TextEntry::make('kelas.nama')
                                    ->label('Kelas')
                                    ->state(fn(Siswa $record): string => $record->kelas->nama . ' ' . config('custom.lembaga')[$record->lembaga_id])
                                    ->weight('bold'),
                                TextEntry::make('nis')
                                    ->label('NIS')
                                    ->weight('bold')
                                    ->placeholder('NIS belum diisi'),
                                TextEntry::make('nisn')
                                    ->label('NISN')
                                    ->weight('bold')
                                    ->placeholder('NISN belum diisi'),
                                TextEntry::make('jenis_kelamin')
                                    ->formatStateUsing(fn(string $state): string => ['l' => 'Laki-laki', 'p' => 'Perempuan'][$state])
                                    ->weight('bold'),
                                TextEntry::make('nik')
                                    ->label('NIK')
                                    ->weight('bold'),
                                TextEntry::make('tempat_lahir')
                                    ->weight('bold'),
                                TextEntry::make('tanggal_lahir')
                                    ->date('d F Y')
                                    ->weight('bold'),
                                TextEntry::make('alamat')
                                    ->columnSpanFull()
                                    ->weight('bold'),
                                TextEntry::make('nama_ayah')
                                    ->placeholder('Nama Ayah belum diisi')
                                    ->weight('bold'),
                                TextEntry::make('nama_ibu')
                                    ->weight('bold'),
                                TextEntry::make('telepon')
                                    ->weight('bold'),
                                TextEntry::make('email')
                                    ->placeholder('Email belum diisi')
                                    ->weight('bold'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => config('custom.siswa.status')[$state])
                                    ->color(fn(string $state): string => match ($state) {
                                        '1' => 'success',
                                        '2' => 'warning',
                                        '3' => 'info',
                                        default => 'gray',
                                    }),
                                TextEntry::make('label')
                                    ->placeholder('Belum ada Label')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => config('custom.siswa.label')[$state])
                                    ->color(fn(string $state): string => match ($state) {
                                        '1' => 'warning',
                                        '2' => 'warning',
                                        '3' => 'danger',
                                        '11' => 'info',
                                        default => 'gray'
                                    }),
                            ])
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 4,
                                'md' => 3
                            ]),
                    ]),
                Grid::make(2)
                    ->schema([
                        Section::make('Tagihan')
                            ->schema([
                                Infolists\Components\ViewEntry::make('tagihan')
                                    ->label('')
                                    ->view('infolists.components.tabel-tagihan')
                            ])
                            ->headerActions([
                                Action::make('input_tagihan')
                                    ->label('Input Tagihan')
                                    ->color('info')
                                    ->icon('heroicon-o-plus')
                                    ->size('xs')
                                    ->form([
                                        Radio::make('kas_id')
                                            ->label('Kas')
                                            ->options(
                                                function (Siswa $siswa) use ($lembaga) {
                                                    $data = [];
                                                    foreach (Kas::getDaftarTagihan($siswa->lembaga_id)->get() as $k) {
                                                        $data[$k->id] = $k->nama . ' - ' . $lembaga[$k->lembaga_id];
                                                    }
                                                    return $data;
                                                }
                                            )
                                            ->inline()
                                            ->inlineLabel(false)
                                            ->required(),
                                        TextInput::make('jumlah')
                                            ->prefix('Rp')
                                            ->required()
                                            ->currencyMask('.', ',', 0),
                                        Forms\Components\Textarea::make('keterangan'),
                                    ])
                                    ->action(function (Siswa $siswa, array $data) {

                                        $kode = \App\Traits\TagihanTrait::getKodeTagihan('MTG');
                                        $prefix = substr($kode, 0, 11);
                                        $urut = intval(substr($kode, -4));
                                        Tagihan::insert([
                                            'id' => Str::orderedUuid(),
                                            'kode' => $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT),
                                            'siswa_id' => $siswa->id,
                                            'kas_id' => $data['kas_id'],
                                            'jumlah' => $data['jumlah'],
                                            'keterangan' => $data['keterangan'],
                                            'user_id' => auth()->user()->id,
                                            'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now(),
                                        ]);

                                        Notification::make()
                                            ->title('Tagihan  berhasil dimasukkan')
                                            ->icon('heroicon-o-check-circle')
                                            ->iconColor('success')
                                            ->send();

                                        redirect(SiswaResource::getUrl('view', ['record' => $siswa]));
                                    }),
                                Action::make('bayar_tagihan')
                                    ->label('Bayar Semua Tagihan')
                                    ->color('warning')
                                    ->icon('heroicon-o-check')
                                    ->size('xs')
                                    ->requiresConfirmation()
                                    // ->form([
                                    //     \Filament\Forms\Components\Radio::make('pembayaran')
                                    //         ->options(function (Siswa $siswa) {
                                    //             $total_tagihan = 0;
                                    //             foreach ($siswa->tagihan as $t) {
                                    //                 if (!$t->isLunas()) {
                                    //                     $total_tagihan += $t->jumlah;
                                    //                 }
                                    //             }
                                    //             $data = ['tun' => 'Tunai'];

                                    //             if ($siswa->tabungan) {
                                    //                 foreach ($siswa->tabungan as $t) {
                                    //                     if ($t->saldo >= $total_tagihan) {
                                    //                         $data[$t->id] = $t->kas->nama;
                                    //                     }
                                    //                 }
                                    //             }
                                    //             return $data;
                                    //         })
                                    //         ->inline()
                                    //         ->inlineLabel(false)
                                    //         ->required(),
                                    // ])
                                    ->action(function (Siswa $siswa, array $data) {
                                        $total_tagihan = 0;
                                        $tagihan = [];
                                        $rincian = '' . PHP_EOL;
                                        $no = 1;
                                        foreach ($siswa->tagihan as $t) {
                                            if (!$t->isLunas()) {
                                                $total_tagihan += $t->jumlah;
                                                $tagihan[] = [
                                                    'id' => $t->id,
                                                    'kas_id' => $t->kas_id,
                                                    'kas' => $t->kas->nama,
                                                    'jumlah' => $t->jumlah,
                                                    'keterangan' => $t->keterangan,
                                                ];
                                                $rincian .= $no . '. ' . $t->kas->nama . ' ' . $t->keterangan . ' Rp ' . number_format($t->jumlah, thousands_separator: '.') . PHP_EOL;
                                                $no++;
                                            }
                                        }
                                        //tabungan
                                        // if ($data['pembayaran'] != 'tun') {
                                        //     Tabungan::find($data['pembayaran'])
                                        //         ->decrement('saldo', $total_tagihan);
                                        // }

                                        Tagihan::whereIn('id', Arr::pluck($tagihan, 'id'))
                                            ->update([
                                                'bayar' => \DB::raw('jumlah')
                                            ]);

                                        foreach ($tagihan as $t) {
                                            \App\Traits\TransaksiTrait::prosesTransaksi(
                                                kas_id: $t['kas_id'],
                                                mutasi: 'm',
                                                jenis: 'TG',
                                                transable_id: $t['id'],
                                                jumlah: $t['jumlah'],
                                                keterangan: $t['keterangan'] != '' ? 'Pembayaran tagihan ' . $t['kas'] . ' '  . $t['keterangan'] . ' ' . $siswa->nama : ''
                                            );
                                        }
                                        $transaksi_id = 'MTG' . $siswa->lembaga_id . 'S' . Carbon::now()->format('YmdHis');
                                        $raw_data = \App\Services\StrukService::simpanStruk(
                                            [
                                                'lembaga_id' => $siswa->lembaga_id,
                                                'transaksi_id' => $transaksi_id,
                                                'siswa' => $siswa->nama,
                                                'tagihan' => $tagihan,
                                                'total' => $total_tagihan,
                                                'jumlah' => $total_tagihan,
                                            ]
                                        );

                                        if (env('WHATSAPP_NOTIFICATION')) {
                                            if ($siswa->telepon != '') {
                                                $pesan = \App\Services\WhatsappService::prosesPesan(
                                                    $siswa,
                                                    [
                                                        'tagihan.rincian' => $rincian,
                                                        'tagihan.total' => 'Rp ' . number_format($total_tagihan, thousands_separator: '.'),
                                                    ],
                                                    'tagihan.bayar_banyak'
                                                );
                                                \App\Services\WhatsappService::kirimWa(
                                                    nama: $siswa->nama,
                                                    nomor: $siswa->telepon,
                                                    pesan: $pesan,
                                                    sessionId: \App\Services\WhatsappService::getSessionId($siswa)
                                                );
                                            }
                                        }
                                        redirect(url('/cetak/struk-pembayaran-tagihan/' . $transaksi_id . '/raw?data=' . $raw_data));
                                    }),
                                Action::make('cetak_tagihan')
                                    ->label('Cetak Tagihan')
                                    ->color('success')
                                    ->icon('heroicon-o-printer')
                                    ->size('xs')
                                    ->action(
                                        function (Siswa $siswa) {
                                            $total_tagihan = 0;
                                            foreach ($siswa->tagihan as $t) {
                                                $sisa = $t->jumlah - intval($t->bayar);
                                                if ($sisa > 0) {
                                                    $tagihan[] = [
                                                        'keterangan' => $t->keterangan,
                                                        'tanggal' => substr($t->created_at, 0, 10),
                                                        'jumlah' => $t->jumlah,
                                                        'bayar' => intval($t->bayar),
                                                        'sisa' => $sisa,
                                                        'petugas' => $t->petugas->authable->nama,
                                                    ];
                                                    $total_tagihan += $sisa;
                                                }
                                            }

                                            if ($total_tagihan == 0) {
                                                Notification::make()
                                                    ->title('Siswa tidak mempunyai Tagihan')->success()
                                                    ->send();
                                                return;
                                            }

                                            $raw_data = \App\Services\StrukService::simpanStruk(
                                                [
                                                    'lembaga_id' => $siswa->lembaga_id,
                                                    'transaksi_id' => 'CTG' . $siswa->lembaga_id . Carbon::now()->format('Ymd'),
                                                    'siswa' => $siswa->nama,
                                                    'tagihan' => $tagihan,
                                                    'total' => $total_tagihan,
                                                ]
                                            );
                                            redirect()->to(url('/cetak/tagihan/' . $siswa->id . '/raw?data=' . $raw_data));
                                        }
                                    ),
                            ])
                            ->columnSpan(1),
                        Section::make('Tabungan')
                            ->schema([
                                Infolists\Components\ViewEntry::make('tabungan')
                                    ->label('')
                                    ->view('infolists.components.tabel-tabungan')
                            ])
                            ->headerActions([
                                Action::make('input')
                                    ->label('Setor Tabungan')
                                    ->color('info')
                                    ->icon('heroicon-o-plus')
                                    ->size('xs')
                                    ->form([
                                        Radio::make('kas_id')
                                            ->label('Tabungan')
                                            ->options(
                                                function (Siswa $siswa): array {
                                                    $data = [];
                                                    foreach (Kas::getDaftarTabungan($siswa->lembaga_id)->get() as $k) {
                                                        $data[$k->id] = $k->nama;
                                                    }
                                                    return $data;
                                                }
                                            )
                                            ->inline()
                                            ->inlineLabel(false)
                                            ->required(),
                                        TextInput::make('jumlah')
                                            ->prefix('Rp')
                                            ->required()
                                            ->currencyMask('.', ',', 0)
                                    ])
                                    ->action(function (Siswa $siswa, array $data) {
                                        $tabungan = Tabungan::where('kas_id', $data['kas_id'])
                                            ->where('siswa_id', $siswa->id);
                                        if ($tabungan->exists()) {
                                            $id = $tabungan->first()->id;
                                            $tabungan->increment('saldo', $data['jumlah']);
                                        } else {
                                            $tabungan = Tabungan::create([
                                                'siswa_id' => $siswa->id,
                                                'kas_id' => $data['kas_id'],
                                                'saldo' => $data['jumlah'],
                                            ]);
                                            $id = $tabungan->id;
                                        }
                                        Notification::make()
                                            ->title('Setoran berhasil')
                                            ->icon('heroicon-o-check-circle')
                                            ->iconColor('success')
                                            ->send();

                                        //Proses transaksi
                                        $keterangan = 'Setoran ' . Kas::find($data['kas_id'])->nama . ' ' . $siswa->nama;
                                        $transaksi_id = \App\Traits\TransaksiTrait::prosesTransaksi(
                                            kas_id: $data['kas_id'],
                                            mutasi: 'm',
                                            jenis: 'TB',
                                            transable_id: $id,
                                            jumlah: $data['jumlah'],
                                            keterangan: $keterangan
                                        );

                                        $raw_data = \App\Services\StrukService::simpanStruk(
                                            [
                                                'lembaga_id' => $siswa->lembaga_id,
                                                'transaksi_id' => $transaksi_id,
                                                'siswa' => $siswa->nama,
                                                'keterangan' => $keterangan,
                                                'jumlah' => $data['jumlah'],
                                            ]
                                        );
                                        redirect()->to(url('/cetak/struk-setoran-tabungan/' . $transaksi_id . '/raw?data=' . $raw_data));
                                    }),
                            ])
                            ->columnSpan(1),
                    ])
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'view' => Pages\ViewSiswa::route('/{record}'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
            'belanja' => Pages\Belanja::route('/{record}/belanja'),
        ];
    }
}
