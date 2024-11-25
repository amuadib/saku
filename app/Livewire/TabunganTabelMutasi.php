<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Livewire\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TabunganTabelMutasi extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $tabungan_id;

    public function render()
    {
        return view('livewire.tabungan-tabel-mutasi');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaksi::query()->where('transable_id', $this->tabungan_id)->where('transable_type', 'App\Models\Tabungan'))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d F Y H:i:s'),
                TextColumn::make('kode'),
                TextColumn::make('jumlah')
                    ->color(function (Transaksi $record): string {
                        return $record->kode[0] == 'M' ? 'success' : 'danger';
                    })
                    ->icon(function (Transaksi $record): string {
                        return $record->kode[0] == 'M' ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down';
                    })
                    ->prefix('Rp ')
                    ->numeric(0),
                TextColumn::make('keterangan'),
            ])
            ->paginated(false);
    }
}
