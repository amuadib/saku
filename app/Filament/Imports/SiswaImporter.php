<?php

namespace App\Filament\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Arr;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;
    public static function getOptionsFormComponents(): array
    {
        return [
            Radio::make('lembaga')
                ->label('Pilih Lembaga')
                ->options(Arr::except(config('custom.lembaga'), [99])),
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
                ->relationship(resolveUsing: function (string $state): ?Kelas {
                    return Kelas::query()
                        ->join('periode', 'periode.id', '=', 'kelas.periode_id')
                        ->where('periode.aktif', 1)
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
                }),
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
                }),
            ImportColumn::make('nama_ibu')
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('telepon'),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        return Siswa::firstOrNew(
            [
                'nik' => $this->data['nik'],
            ],
            [
                'lembaga_id' => $this->options['lembaga'],
                'status' => 1,
            ]
        );

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
