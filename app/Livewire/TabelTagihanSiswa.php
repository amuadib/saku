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
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

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
                fn (Builder $query) => $query
                    ->where('siswa_id', $this->siswa->id)
                    ->whereNull('bayar')
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
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Belum' => 'warning',
                    }),
                TextColumn::make('keterangan'),
            ])
            // ->bulkActions([
            //     BulkAction::make('bayar_tagihan_terpilih')
            //         ->requiresConfirmation()
            //         ->color('warning')
            //         ->size('xs')
            //         ->action(function (Collection $records) {
            //             dd($records);
            //         })
            // ])
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
                        Cache::put(
                            $transaksi_id,
                            [
                                'lembaga_id' => $this->siswa->lembaga_id,
                                'transaksi_id' => $transaksi_id,
                                'tanggal' => Carbon::now()->format('d-m-Y'),
                                'waktu' => Carbon::now()->format('H:i:s'),
                                'petugas' => auth()->user()->authable->nama,
                                'siswa' => $this->siswa->nama,
                                'keterangan' => $keterangan,
                                'jumlah' => $jumlah,
                            ],
                            now()->addMinutes(150)
                        );

                        redirect(url('/cetak/struk-pembayaran-tagihan/' . $transaksi_id));
                    })
                    ->successNotificationTitle('Pembayaran berhasil!'),
            ]);
    }
}
