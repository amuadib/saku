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

            // Ambil data detail Kas untuk membaca aturan tagihan khusus (gratis/maksimal)
            $kas = \App\Models\Kas::find($data['kas_id']);
            $aturanKas = is_array($kas?->aturan_tagihan) ? $kas->aturan_tagihan : [];
            foreach ($siswa as $s) {
                $label_siswa = $s->label ?? [];
                
                $isFree = false;
                $jumlah = $data['jumlah'];

                // Terapkan semua aturan khusus dari database
                foreach ($aturanKas as $aturan) {
                    if (in_array((int)$aturan['label_id'], $label_siswa)) {
                        if ($aturan['jenis'] === 'gratis') {
                            $isFree = true;
                            break; // Siswa ini gratis, berhentikan pengecekan!
                        } elseif ($aturan['jenis'] === 'maksimal') {
                            $batas = (int) $aturan['nominal'];
                            if ($jumlah > $batas) {
                                $jumlah = $batas;
                            }
                        }
                    }
                }

                if ($isFree) {
                    continue; // Lewati pembuatan tagihan karena digratiskan
                }

                $insert[] = [
                    'id' => Str::orderedUuid(),
                    'kode' => $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT),
                    'siswa_id' => $s->id,
                    'kas_id' => $data['kas_id'],
                    'jumlah' => $jumlah,
                    'keterangan' => $data['keterangan'],
                    'user_id' => $data['user_id'],
                    'tanggal_kadaluarsa' => $data['tanggal_kadaluarsa'],
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
