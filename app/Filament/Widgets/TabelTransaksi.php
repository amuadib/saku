<?php

// namespace App\Filament\Widgets;

// use App\Models\RekapTransaksiHarian;
// use Filament\Tables\Table;
// use Filament\Widgets\TableWidget as BaseWidget;
// use Filament\Tables\Columns\TextColumn;

// class TabelTransaksi extends BaseWidget
// {
//     protected static ?string $heading = 'Rekap Transaksi Harian';
//     protected static ?string $pollingInterval = '30s';
//     protected static ?int $sort = 0;

//     public function table(Table $table): Table
//     {
//         return $table
//             ->query(
//                 RekapTransaksiHarian::when(
//                     !auth()->user()->isAdmin(),
//                     function ($w) {
//                         $w
//                             ->join('kas', 'kas_id', '=', 'kas.id')
//                             ->where('kas.lembaga_id', auth()->user()->authable->lembaga_id);
//                     }
//                 )
//                     ->orderBy('tanggal', 'desc')
//                     ->limit(7)
//             )
//             ->columns([
//                 TextColumn::make('tanggal')
//                     ->date('d F Y'),
//                 TextColumn::make('kas.nama'),
//                 TextColumn::make('masuk')
//                     ->prefix('Rp ')
//                     ->numeric(thousandsSeparator: '.'),
//                 TextColumn::make('keluar')
//                     ->prefix('Rp ')
//                     ->numeric(thousandsSeparator: '.'),
//                 TextColumn::make('saldo')
//                     ->prefix('Rp ')
//                     ->state(fn (RekapTransaksiHarian $rekap): int => $rekap->masuk - $rekap->keluar)
//                     ->numeric(thousandsSeparator: '.'),
//             ])
//             ->emptyStateHeading('Belum ada data')
//             ->paginated(false);
//     }
// }
