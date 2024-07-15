<?php

namespace App\Livewire;

use App\Models\Keranjang;
use Livewire\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class KeranjangComponent extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function render()
    {
        return view('livewire.keranjang-component');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Keranjang::query())
            ->columns([
                TextColumn::make('kode'),
                TextColumn::make('barang.nama'),
                TextColumn::make('harga'),
                TextColumn::make('jumlah'),
                TextColumn::make('total'),
            ])
            ->emptyStateIcon('heroicon-o-shopping-cart')
            ->emptyStateHeading('Keranjang masih kosong.')
            ->paginated(false)
            // ->poll('5s')
        ;
    }
}
