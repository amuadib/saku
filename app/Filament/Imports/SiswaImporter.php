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
        $gender = rand(1, 999) % 2 == 0 ? ['l', 'male'] : ['p', 'female'];
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->rules(['required', 'max:255'])
                ->example(fake('id_ID')->name($gender[1]))
                ->exampleHeader('Nama'),
            ImportColumn::make('kelas')
                ->requiredMapping()
                ->relationship(resolveUsing: function (string $state): ?Kelas {
                    return Kelas::query()
                        ->join('periode', 'periode.id', '=', 'kelas.periode_id')
                        ->where('periode.aktif', 1)
                        ->where('kelas.nama', $state)
                        ->first('kelas.id');
                })
                ->rules(['required'])
                ->example(rand(1, 6))
                ->exampleHeader('Kelas'),
            ImportColumn::make('nik')
                ->label('NIK')
                ->requiredMapping()
                ->rules(['required'])
                ->example("'" . fake('id_ID')->nik())
                ->exampleHeader('NIK'),
            ImportColumn::make('tempat_lahir')
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->example(fake('id_ID')->city())
                ->exampleHeader('Tempat Lahir'),
            ImportColumn::make('tanggal_lahir')
                ->rules(['date'])
                ->example(fake('id_ID')->date(max: '6 years ago'))
                ->exampleHeader('Tanggal Lahir'),
            ImportColumn::make('jenis_kelamin')
                ->castStateUsing(function (string $state): string {
                    return strtolower($state);
                })
                ->requiredMapping()
                ->example($gender[0])
                ->exampleHeader('Jenis Kelamin')
                ->rules(['required']),
            ImportColumn::make('alamat')
                ->requiredMapping()
                ->rules(['required'])
                ->example(fake('id_ID')->address())
                ->exampleHeader('Alamat'),
            ImportColumn::make('nama_ayah')
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->example(fake('id_ID')->name('male'))
                ->exampleHeader('Nama Ayah'),
            ImportColumn::make('nama_ibu')
                ->castStateUsing(function (string $state): string {
                    return strtoupper($state);
                })
                ->requiredMapping()
                ->rules(['required'])
                ->example(fake('id_ID')->name('female'))
                ->exampleHeader('Nama Ibu'),
            ImportColumn::make('telepon')
                ->example(fake('id_ID')->phoneNumber())
                ->exampleHeader('Telepon'),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        return Siswa::firstOrNew(
            [
                'nik' => $this->data['nik'],
                // 'lembaga_id' => $this->data['lembaga_id'],
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
