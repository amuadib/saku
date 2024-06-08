<?php

namespace App\Filament\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Radio;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;
    public static function getOptionsFormComponents(): array
    {
        return [
            Radio::make('lembaga')
                ->label('Pilih Lembaga')
                ->options(config('custom.lembaga')),
        ];
    }
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->rules(['required', 'max:255']),
            ImportColumn::make('kelas')
                ->requiredMapping()
                // ->relationship(resolveUsing: 'nama')
                ->relationship(resolveUsing: function (string $state): ?Kelas {
                    return Kelas::query()
                        ->join('periode', 'periode.id', '=', 'kelas.periode_id')
                        ->where('periode.aktif', 'y')
                        ->where('kelas.nama', $state)
                        ->first('kelas.id');
                })
                ->rules(['required']),
            ImportColumn::make('nik')
                ->label('NIK')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('tempat_lahir')
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->rules(['max:255']),
            ImportColumn::make('tanggal_lahir')
                ->rules(['date']),
            ImportColumn::make('jenis_kelamin')
                ->castStateUsing(function (string $state): string {
                    return strtolower($state);
                })
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('alamat')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('nama_ayah')
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->rules(['max:255']),
            ImportColumn::make('nama_ibu')
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->rules(['max:255']),
            ImportColumn::make('telepon')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        $this->data['lembaga_id'] = $this->options['lembaga'];
        return Siswa::firstOrNew([
            'nik' => $this->data['nik'],
            'lembaga_id' => $this->data['lembaga_id'],
        ]);

        // return new Siswa();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor Siswa berhasil, ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' data telah disimpan.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal.';
        }

        return $body;
    }
}
