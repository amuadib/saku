<?php

namespace App\Livewire;

use App\Models\Kas;
use App\Models\Tabungan;
use Livewire\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Qb;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

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
            ->headerActions([
                Action::make('input')
                    ->label('Setor Tabungan')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Radio::make('kas_id')
                            ->label('Tabungan')
                            ->options(
                                function (): array {
                                    $data = [];
                                    foreach (Kas::getDaftarTabungan($this->siswa->lembaga_id)->get() as $k) {
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
                    ->action(function (array $data) {
                        $tabungan = Tabungan::where('kas_id', $data['kas_id'])
                            ->where('siswa_id', $this->siswa->id);
                        if ($tabungan->exists()) {
                            $id = $tabungan->first()->id;
                            $tabungan->increment('saldo', $data['jumlah']);
                        } else {
                            $id = Tabungan::insertGetId([
                                'id' => Str::orderedUuid(),
                                'siswa_id' => $this->siswa->id,
                                'kas_id' => $data['kas_id'],
                                'saldo' => $data['jumlah'],
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now(),
                            ]);
                        }
                        Notification::make()
                            ->title('Setoran berhasil')
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                            ->send();

                        //Proses transaksi
                        \App\Traits\TransaksiTrait::prosesTransaksi(
                            kas_id: $data['kas_id'],
                            mutasi: 'm',
                            jenis: 'TB',
                            transable_id: $id,
                            jumlah: $data['jumlah'],
                            keterangan: 'Setoran ' . Kas::find($data['kas_id'])->nama . ' ' . $this->siswa->nama
                        );

                        redirect(url('/siswas/' . $this->siswa->id));
                    }),
            ])
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
                        \App\Traits\TransaksiTrait::prosesTransaksi(
                            kas_id: $record->kas->id,
                            mutasi: 'm',
                            jenis: 'TB',
                            transable_id: $record->id,
                            jumlah: $data['jumlah'],
                            keterangan: 'Setoran ' . $record->kas->nama . ' ' . $record->siswa->nama . '. ' . $data['keterangan']
                        );

                        redirect(url('/siswas/' . $this->siswa->id));
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
                            \App\Traits\TransaksiTrait::prosesTransaksi(
                                kas_id: $record->kas->id,
                                mutasi: 'k',
                                jenis: 'TB',
                                transable_id: $record->id,
                                jumlah: $data['jumlah'],
                                keterangan: 'Penarikan ' . $record->kas->nama . ' ' . $record->siswa->nama . '. ' . $data['keterangan']
                            );

                            redirect(url('/siswas/' . $this->siswa->id));
                        }
                    }),
            ]);
    }
    public function render(): View
    {
        return view('livewire.tabel-tabungan-siswa');
    }
}
