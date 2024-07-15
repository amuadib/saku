<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Kas;
use App\Models\Keranjang;
use App\Models\Penjualan;
use App\Models\Tabungan;
use App\Models\Tagihan;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action as FA;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Validate;

class Belanja extends Page implements
    HasInfolists,
    HasTable
{
    use
        InteractsWithRecord,
        InteractsWithInfolists,
        InteractsWithTable;
    protected static string $resource = SiswaResource::class;
    protected static string $view = 'filament.resources.siswa-resource.pages.belanja';
    public $total;

    #[Validate('required')]
    public $pembayaran;

    #[Validate('required_if:pembayaran,tun')]
    public $bayar;

    public $kembali = 0;
    public $jenis;
    public $tabungan;
    public $tabungan_id;
    public $saldoCukup = false;
    public $transaksi_selesai = false;
    public $id = null;
    public $views = ['tun' => 'struk', 'tab' => 'struk', 'tag' => 'invoice'];

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
    public function prosesPembayaran()
    {
        $this->validate();

        $kas = Kas::where('penjualan', true)
            ->where('lembaga_id', $this->record->lembaga_id)
            ->first();
        if (!$kas) {
            Notification::make()
                ->title('Transaksi gagal')
                ->body('Kas tidak ditemukan. Harap hubungi Admin')
                ->danger()
                ->color('danger')
                ->send();
            return;
        }

        $bayar = intval(str_replace('.', '', $this->bayar));
        if ($this->pembayaran == 'tun' && $bayar < $this->total) {
            throw \Illuminate\Validation\ValidationException::withMessages(['kurang' => 'Bayare kurang cah ;)']);
        }

        if ($this->pembayaran == 'tab') {
            $tabungan_siswa = Tabungan::find($this->tabungan_id);
            if (!$tabungan_siswa) {
                throw \Illuminate\Validation\ValidationException::withMessages(['tabungan_id' => 'Data Tabungan tidak ditemukan']);
            }

            if ($tabungan_siswa->saldo < $this->total) {
                throw \Illuminate\Validation\ValidationException::withMessages(['tabungan_id' => 'Saldo Tabungan tidak mencukupi']);
            }
        }

        $insert_detail = [];
        $daftar_barang = [];
        $stok_keluar = [];

        //Penjualan
        $penjualan = Penjualan::create([
            'siswa_id' => $this->record->id,
            'total' => $this->total,
            'pembayaran' => $this->pembayaran,
        ]);

        //Detail Penjualan
        foreach ($this->record->keranjang as $b) {
            $insert_detail[] = [
                'id' => Str::orderedUuid(),
                'penjualan_id' => $penjualan->id,
                'barang_id' => $b->barang_id,
                'jumlah' => $b->jumlah,
                'harga' => $b->harga,
                'total' => $b->total,
            ];
            $daftar_barang[] = [
                'nama' => $b->barang->nama,
                'satuan' => $b->barang->satuan,
                'jumlah' => $b->jumlah,
                'harga' => $b->harga,
                'total' => $b->total,
            ];
            $stok_keluar[$b->barang_id] = $b->jumlah;
        }
        if (count($insert_detail)) {
            DetailPenjualan::insert($insert_detail);
        }

        if (count($stok_keluar)) {
            foreach ($stok_keluar as $barang_id => $terjual) {
                Barang::find($barang_id)
                    ->decrement('stok', $terjual);
            }
        }

        if ($this->pembayaran == 'tun') {
            if ($bayar > $this->total) {
                $this->kembali = $bayar - $this->total;
            }
        }


        if ($this->pembayaran == 'tab') {
            //kurangi saldo
            $tabungan_siswa->decrement('saldo', $this->total);

            //Mutasi Tabungan
            \App\Traits\TransaksiTrait::prosesTransaksi(
                kas_id: $this->tabungan[$this->record->id][$this->tabungan_id]['kas_id'],
                mutasi: 'k',
                jenis: 'TB',
                transable_id: $penjualan->id,
                jumlah: $this->total,
                keterangan: 'Pembelian barang ' . $this->record->nama . '.'
            );
        }

        if ($this->pembayaran == 'tun' or $this->pembayaran == 'tab') {
            $id = \App\Traits\TransaksiTrait::prosesTransaksi(
                kas_id: $kas->id,
                mutasi: 'm',
                jenis: 'PJ',
                transable_id: $penjualan->id,
                jumlah: $this->total,
                keterangan: 'Pembelian barang ' . $this->record->nama . '.'
            );
            $this->id = $id;
        } else {
            //Tagihan
            $kode = \App\Traits\TagihanTrait::getKodeTagihan('MTG');
            Tagihan::insert([
                'id' => Str::orderedUuid(),
                'kode' => $kode,
                'siswa_id' => $this->record->id,
                'kas_id' => $kas->id,
                'jumlah' => $this->total,
                'keterangan' => 'Pembelian barang',
                'user_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $this->id = $kode;
        }

        Cache::put(
            $this->id,
            [
                'lembaga_id' => $this->record->lembaga_id,
                'transaksi_id' => $this->id,
                'tanggal' => Carbon::now()->format('d-m-Y'),
                'waktu' => Carbon::now()->format('H:i:s'),
                'petugas' => auth()->user()->authable->nama,
                'siswa' => $this->record->nama,
                'barang' => $daftar_barang,
                'total' => $this->total,
                'bayar' => $bayar,
                'kembali' => $this->kembali,
                'pembayaran' => $this->pembayaran,
            ],
            now()->addMinutes(150)
        );
        $this->transaksi_selesai = true;
        //Hapus keranjang
        Keranjang::where('siswa_id', $this->record->id)
            ->delete();
        $this->reset([
            'total',
            'bayar',
            'saldoCukup',
        ]);
    }

    public function cetak(string $id)
    {
        return redirect()->to(url('/cetak/' . $this->views[$this->pembayaran] . '/' . $id));
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama Siswa'),
                        TextEntry::make('tanggal')
                            ->state(fn (): string => date('d/m/Y')),
                        TextEntry::make('petugas')
                            ->state(fn (): string => auth()->user()->authable->nama)
                        // ...
                    ])
                    ->columns(3)
            ]);
    }


    public function table(Table $table): Table
    {
        $this->total = $this->record->keranjang->sum('total');
        $this->saldoCukup = false;

        if ($this->record->tabungan) {
            foreach ($this->record->tabungan as $t) {
                if ($t->saldo >= $this->total) {
                    $this->tabungan[$this->record->id][$t->id] = [
                        'nama' => $t->kas->nama,
                        'kas_id' => $t->kas_id,
                        'saldo' => $t->saldo
                    ];
                    $this->saldoCukup = true;
                }
            }
        }
        return $table
            ->query(Keranjang::query())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('siswa_id', $this->record->id))
            ->headerActions([
                Action::make('tambah_barang')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->button()
                    ->form([
                        Select::make('barang_id')
                            ->label('Barang')
                            ->options(
                                Barang::where('stok', '>', 0)
                                    ->orderBy('nama')
                                    ->pluck('nama', 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                $barang = Barang::find($state);
                                if ($barang) {
                                    $set('harga', $barang->harga);
                                    $set('stok', $barang->stok);
                                    $set('satuan', $barang->satuan);
                                    $set('total', $barang->harga * $get('jumlah'));
                                }
                            }),
                        TextInput::make('harga')
                            ->prefix('Rp ')
                            ->currencyMask('.', ',', 0)
                            ->readOnly(),
                        TextInput::make('stok')
                            ->readOnly(),
                        TextInput::make('satuan')
                            ->readOnly(),
                        TextInput::make('jumlah')
                            ->default(1)
                            ->minValue(1)
                            ->readOnly()
                            ->afterStateUpdated(function (Get $get, Set $set, int $state) {
                                $set('total', intval($get('harga')) * intval($state));
                            })
                            ->suffixActions([
                                FA::make('tambahBarang')
                                    ->icon('heroicon-o-plus')
                                    ->color('success')
                                    ->action(function (Get $get, Set $set, $state) {
                                        if ($state < $get('stok')) {
                                            $set('jumlah', $state + 1);
                                            $set('total', intval($get('harga')) * intval($state + 1));
                                        }
                                    }),
                                FA::make('kurangiBarang')
                                    ->icon('heroicon-o-minus')
                                    ->color('danger')
                                    ->action(function (Get $get, Set $set, $state) {
                                        if ($state > 1) {
                                            $set('jumlah', $state - 1);
                                            $set('total', intval($get('harga')) * intval($state - 1));
                                        }
                                    })
                            ]),
                        TextInput::make('total')
                            ->prefix('Rp ')
                            ->currencyMask('.', ',', 0)
                            ->readOnly(),
                    ])
                    ->action(function (array $data) {
                        Keranjang::insert([
                            'id' => Str::orderedUuid(),
                            'siswa_id' => $this->record->id,
                            'barang_id' => $data['barang_id'],
                            'jumlah' => $data['jumlah'],
                            'harga' => $data['harga'],
                            'total' => $data['total'],
                        ]);
                        $this->reset([
                            'transaksi_selesai',
                            'jenis', 'kembali',
                            'pembayaran',
                        ]);
                    })
            ])
            ->columns([
                TextColumn::make('no')
                    ->rowIndex(),
                TextColumn::make('barang.nama'),
                TextColumn::make('harga')
                    ->prefix('Rp ')
                    ->numeric(thousandsSeparator: '.'),
                TextColumn::make('')
                    ->state(fn (): string => 'X'),
                TextColumn::make('jumlah')
                    ->formatStateUsing(fn (Keranjang $k): string => $k->jumlah . ' ' . $k->barang->satuan),
                TextColumn::make('total')
                    ->prefix('Rp ')
                    ->numeric(thousandsSeparator: '.')
            ])
            ->actions([
                Actions\DeleteAction::make()
                    ->label('')
                    ->size('xl'),
            ])
            ->emptyStateHeading('Belum ada data.')
            ->paginated(false);
    }
}
