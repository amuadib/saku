<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tagihan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateTagihan extends CreateRecord
{
    protected static string $resource = TagihanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $lembaga_id = $user->isAdmin() ? $data['lembaga_id'] : $user->authable->lembaga_id;

        if ($data['peserta'] < 2) {
            $siswa = null;
            $insert = [];
            if ($data['peserta'] == 0) { //semua siswa
                $siswa = Siswa::where('status', 1)
                    ->where('lembaga_id', $lembaga_id)
                    ->get();
            } elseif ($data['peserta'] == 1) {
                $siswa = Kelas::find($data['kelas_id'])->siswa;
            }

            if (!$siswa->count()) {
                Notification::make()
                    ->warning()
                    ->title('Terjadi kesalahan')
                    ->body('Data siswa tidak ditemukan. Silahkan pilih siswa terlebih dahulu.')
                    ->send();

                $this->halt();
            }
            $kode = \App\Traits\TagihanTrait::getKodeTagihan('MTG');
            $prefix = substr($kode, 0, 11);
            $urut = intval(substr($kode, -4));
            foreach ($siswa as $s) {

                //Tagihan Uang Makan untuk Siswa Keluarga Pegawai Yayasan Max. Rp. 50.000
                $uang_makan = '9c30886f-ecce-4436-a81c-ff406f5675cd';
                $keluarga_pegawai = 21;
                $maksimal = 50000;

                $jumlah = $data['jumlah'];
                if (
                    $data['kas_id'] == $uang_makan &&
                    in_array($keluarga_pegawai, $s->label) &&
                    $data['jumlah'] > $maksimal
                ) {
                    $jumlah = $maksimal;
                }

                $insert[] = [
                    'id' => Str::orderedUuid(),
                    'kode' => $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT),
                    'siswa_id' => $s->id,
                    'kas_id' => $data['kas_id'],
                    'jumlah' => $jumlah,
                    'keterangan' => $data['keterangan'],
                    'user_id' => $data['user_id'],
                    'created_at' => \Carbon\Carbon::now()
                ];
                $urut++;
            }

            $siswa_first = Arr::pull($insert, 0);
            $data['siswa_id'] = $siswa_first['siswa_id'];
            $data['kode'] = $prefix . str_pad($urut++, 4, '0', STR_PAD_LEFT);

            if (count($insert) > 0) {
                Tagihan::insert($insert);
            }
        }

        unset($data['lembaga_id']);
        unset($data['kelas_id']);
        unset($data['peserta']);

        return $data;
    }
}
