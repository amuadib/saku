<?php

namespace App\Livewire;

use App\Models\Tabungan;
use Livewire\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TabelTabunganSiswa extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $ids = [];
    public $siswa;

    public function mount($tabungan, $siswa): void
    {
        $this->siswa = $siswa;
        if ($tabungan) {
            foreach ($tabungan as $d) {
                $this->ids[] = $d->id;
            }
        }
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Tabungan::query())
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('id', $this->ids))
            ->columns([
                TextColumn::make('kas.nama')
                    ->label('Tabungan'),
                TextColumn::make('saldo')
                    ->prefix('Rp ')
                    ->numeric(thousandsSeparator: '.')
                    ->alignEnd()
            ])
            ->emptyStateHeading('Siswa tidak mempunyai Tabungan.')
            ->paginated(false)
            ->actions([
                Action::make('setor')
                    ->button()
                    ->size('xs')
                    ->color('success')
                    ->form([
                        TextInput::make('jumlah')
                            ->prefix('Rp')
                            ->required()
                            ->currencyMask('.', ',', 0),
                        Textarea::make('keterangan')
                    ])
                    ->action(function (Tabungan $record, array $data) {
                        if ($record->increment('saldo', $data['jumlah'])) {
                            Notification::make()
                                ->title('Setoran berhasil')
                                ->icon('heroicon-o-check-circle')
                                ->iconColor('success')
                                ->send();
                        }

                        //Proses transaksi
                        $keterangan = 'Setoran ' . $record->kas->nama . ' ' . $record->siswa->nama . '. ' . $data['keterangan'];
                        $transaksi_id = \App\Traits\TransaksiTrait::prosesTransaksi(
                            kas_id: $record->kas->id,
                            mutasi: 'm',
                            jenis: 'TB',
                            transable_id: $record->id,
                            jumlah: $data['jumlah'],
                            keterangan: $keterangan
                        );
                        Cache::put(
                            $transaksi_id,
                            [
                                'lembaga_id' => $record->siswa->lembaga_id,
                                'transaksi_id' => $transaksi_id,
                                'tanggal' => Carbon::now()->format('d-m-Y'),
                                'waktu' => Carbon::now()->format('H:i:s'),
                                'petugas' => auth()->user()->authable->nama,
                                'siswa' => $record->siswa->nama,
                                'keterangan' => $keterangan,
                                'jumlah' => $data['jumlah'],
                            ],
                            now()->addMinutes(150)
                        );
                        redirect()->to(url('/cetak/struk-setoran-tabungan/' . $transaksi_id));
                    }),
                Action::make('tarik')
                    ->button()
                    ->size('xs')
                    ->color('warning')
                    ->form([
                        TextInput::make('jumlah')
                            ->prefix('Rp')
                            ->required()
                            ->currencyMask('.', ',', 0),
                        Textarea::make('keterangan')
                    ])
                    ->action(function (Tabungan $record, array $data) {
                        if ($record->saldo < $data['jumlah']) {
                            Notification::make()
                                ->title('Penarikan melebihi jumlah saldo')
                                ->icon('heroicon-o-x-circle')
                                ->iconColor('danger')
                                ->send();
                        } else {
                            if ($record->decrement('saldo', $data['jumlah'])) {
                                Notification::make()
                                    ->title('Penarikan berhasil')
                                    ->icon('heroicon-o-check-circle')
                                    ->iconColor('success')
                                    ->send();
                            }

                            //Proses transaksi
                            $keterangan = 'Penarikan ' . $record->kas->nama . ' ' . $record->siswa->nama . '. ' . $data['keterangan'];
                            $transaksi_id = \App\Traits\TransaksiTrait::prosesTransaksi(
                                kas_id: $record->kas->id,
                                mutasi: 'k',
                                jenis: 'TB',
                                transable_id: $record->id,
                                jumlah: $data['jumlah'],
                                keterangan: $keterangan
                            );
                            Cache::put(
                                $transaksi_id,
                                [
                                    'lembaga_id' => $record->siswa->lembaga_id,
                                    'transaksi_id' => $transaksi_id,
                                    'tanggal' => Carbon::now()->format('d-m-Y'),
                                    'waktu' => Carbon::now()->format('H:i:s'),
                                    'petugas' => auth()->user()->authable->nama,
                                    'siswa' => $record->siswa->nama,
                                    'keterangan' => $keterangan,
                                    'jumlah' => $data['jumlah'],
                                ],
                                now()->addMinutes(150)
                            );
                            redirect()->to(url('/cetak/struk-penarikan-tabungan/' . $transaksi_id));
                        }
                    }),
            ]);
    }
    public function render(): View
    {
        return view('livewire.tabel-tabungan-siswa');
    }
}
