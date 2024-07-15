<?php

namespace App\Livewire;

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
            ->modifyQueryUsing(fn (Builder $query) => $query->where('siswa_id', $this->siswa->id))
            ->emptyStateHeading('Siswa tidak Tagihan.')
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
                        'Belum' => 'danger',
                    }),
                TextColumn::make('keterangan'),
            ]);
    }
}
