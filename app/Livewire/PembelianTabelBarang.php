<?php

namespace App\Livewire;

use App\Models\DetailPembelian;
use Livewire\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PembelianTabelBarang extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $pembelian_id;

    public function render()
    {
        return view('livewire.pembelian-tabel-barang');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(DetailPembelian::query()->where('pembelian_id', $this->pembelian_id))
            ->columns([
                TextColumn::make('barang.nama'),
                TextColumn::make('jumlah'),
                TextColumn::make('barang.harga_beli')
                    ->label('Harga Beli')
                    ->prefix('Rp ')
                    ->numeric(0),
                // TextColumn::make('barang.harga')
                //     ->label('Harga Jual')
                //     ->prefix('Rp ')
                //     ->numeric(0),
                TextColumn::make('total')
                    ->prefix('Rp ')
                    ->numeric(0),
            ])
            ->paginated(false);
    }
}
