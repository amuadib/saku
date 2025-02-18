<?php

namespace App\Livewire;

use App\Models\Tabungan;
use App\Models\Tagihan;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class TabelTagihanSiswa extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $siswa;
    public $tagihan;

    public function mount($tagihan, $siswa): void
    {
        $this->siswa = $siswa;
        $this->tagihan = $tagihan;
    }
    public function render(): View
    {
        return view('livewire.tabel-tagihan-siswa');
    }
    public function table(Table $table): Table
    {
        return $table->query(Tagihan::query())
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->where('siswa_id', $this->siswa->id)
                    ->where(function ($w) {
                        $w->whereNull('bayar')
                            ->orWhere('bayar', 0);
                    })
            )
            ->emptyStateHeading('Siswa tidak mempunyai Tagihan.')
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')
                    ->date('d/m/Y')
                    ->label('Tanggal'),
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
                    ->color(fn(string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Belum' => 'warning',
                    }),
                TextColumn::make('keterangan'),
            ])
            ->bulkActions([
                BulkAction::make('bayar_tagihan_terpilih')
                    ->requiresConfirmation()
                    ->color('warning')
                    ->size('xs')
                    ->action(function (Collection $records) {
                        $total_tagihan = 0;
                        $tagihan = [];
                        $rincian = '' . PHP_EOL;
                        $no = 1;
                        foreach ($records as $t) {
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
                                keterangan: $t['keterangan'] != '' ? 'Pembayaran tagihan ' . $t['kas'] . ' '  . $t['keterangan'] . ' ' . $this->siswa->nama : ''
                            );
                        }
                        $transaksi_id = 'MTG' . $this->siswa->lembaga_id .  'T' . Carbon::now()->format('YmdHis');
                        $raw_data = \App\Services\StrukService::simpanStruk(
                            [
                                'lembaga_id' => $this->siswa->lembaga_id,
                                'transaksi_id' => $transaksi_id,
                                'siswa' => $this->siswa->nama,
                                'tagihan' => $tagihan,
                                'total' => $total_tagihan,
                                'jumlah' => $total_tagihan,
                            ]
                        );

                        if (env('WHATSAPP_NOTIFICATION')) {
                            if ($this->siswa->telepon != '') {
                                $pesan = \App\Services\WhatsappService::prosesPesan(
                                    $this->siswa,
                                    [
                                        'tagihan.rincian' => $rincian,
                                        'tagihan.total' => 'Rp ' . number_format($total_tagihan, thousands_separator: '.'),
                                    ],
                                    'tagihan.bayar_banyak'
                                );
                                \App\Services\WhatsappService::kirimWa(
                                    nama: $this->siswa->nama,
                                    nomor: $this->siswa->telepon,
                                    pesan: $pesan,
                                    sessionId: \App\Services\WhatsappService::getSessionId($this->siswa)
                                );
                            }
                        }
                        redirect(url('/cetak/struk-pembayaran-tagihan/' . $transaksi_id . '/raw?data=' . $raw_data));
                    })
            ])
            ->actions([
                Action::make('bayar')
                    ->button()
                    ->size('xs')
                    ->color('warning')
                    ->requiresConfirmation()
                    // ->form([
                    //     \Filament\Forms\Components\Radio::make('pembayaran')
                    //         ->options(function (Tagihan $record) {
                    //             $data = ['tun' => 'Tunai'];
                    //             if ($record->siswa->tabungan) {
                    //                 foreach ($record->siswa->tabungan as $t) {
                    //                     if ($t->saldo >= $record->jumlah) {
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
                    ->action(function (Tagihan $record, array $data) {
                        $jumlah = $record->jumlah;
                        //tabungan
                        // if ($data['pembayaran'] != 'tun') {
                        //     Tabungan::find($data['pembayaran'])
                        //         ->decrement('saldo', $jumlah);
                        // }
                        $record->update(['bayar' => $jumlah]);

                        $keterangan = $record->keterangan != '' ? 'Pembayaran tagihan ' . $record->kas->nama . ' '  . $record->keterangan . ' ' . $record->siswa->nama : '';
                        $transaksi_id = \App\Traits\TransaksiTrait::prosesTransaksi(
                            kas_id: $record->kas->id,
                            mutasi: 'm',
                            jenis: 'TG',
                            transable_id: $record->id,
                            jumlah: $jumlah,
                            keterangan: $keterangan
                        );

                        $raw_data = \App\Services\StrukService::simpanStruk(
                            [
                                'lembaga_id' => $this->siswa->lembaga_id,
                                'transaksi_id' => $transaksi_id,
                                'siswa' => $this->siswa->nama,
                                'keterangan' => $keterangan,
                                'jumlah' => $jumlah,
                            ]
                        );
                        redirect(url('/cetak/struk-pembayaran-tagihan/' . $transaksi_id . '/raw?data=' . $raw_data));
                    })
                    ->successNotificationTitle('Pembayaran berhasil!'),
            ]);
    }
}
