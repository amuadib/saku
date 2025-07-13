<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Siswa;

class KelasService
{
    public static function prosesKenaikanKelas(string $periode_id, array $kelas, array $data_siswa): int
    {
        //Input Kelas
        $kelas_lanjutan = [];
        foreach ($kelas as $key => $k) {
            $lanjutan = $k['tingkat'] + 1;
            $kelas_lanjutan[$key] = Kelas::firstOrCreate(
                [
                    'periode_id' => $periode_id,
                    'lembaga_id' => $k['lembaga_id'],
                    'tingkat' => $lanjutan,
                    'nama' => str_replace($k['tingkat'], $lanjutan, $k['nama'])
                ]
            );
        }
        $update = [];
        foreach ($data_siswa as $siswa) {
            $update[$kelas_lanjutan[$siswa['kelas_id']]->id][] = $siswa['id'];
        }

        if (count($update) > 0) {
            foreach ($update as $kelas_id => $siswa_id) {
                Siswa::whereIn('id', $siswa_id)
                    ->update(['kelas_id' => $kelas_id]);
            }
            return count($update);
        }
        return 0;
    }

    // public static function buatKelasSelanjutnya(string $periode_id, array $kelas): void
    // {
    //     foreach ($kelas as $k) {
    //         $lanjutan = $k['tingkat'] + 1;
    //         Kelas::firstOrCreate(
    //             [
    //                 'periode_id' => $periode_id,
    //                 'lembaga_id' => $k['lembaga_id'],
    //                 'tingkat' => $lanjutan,
    //                 'nama' => str_replace($k['tingkat'], $lanjutan, $k['nama'])
    //             ]
    //         );
    //     }
    // }
}
