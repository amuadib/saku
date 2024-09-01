<?php

namespace App\Filament\Exports;

use App\Models\Tagihan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TagihanExporter extends Exporter
{
    protected static ?string $model = Tagihan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('siswa.nama')
                ->label('Nama'),
            ExportColumn::make('kas.nama')
                ->label('Tagihan'),
            ExportColumn::make('jumlah'),
            ExportColumn::make('lunas')
                ->state(function (Tagihan $record): string {
                    return $record->jumlah - intval($record->bayar) == 0 ? 'Sudah' : 'Belum';
                }),
            ExportColumn::make('keterangan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your tagihan export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
