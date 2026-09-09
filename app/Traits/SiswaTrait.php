<?php

namespace App\Traits;

use App\Models\Siswa;

trait SiswaTrait
{
    public static function generateUniqueAccessCode($length = 4)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
        $code = '';
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[mt_rand(0, strlen($characters) - 1)];
            }
        } while (Siswa::where('kode_akses', $code)->exists());

        return $code;
    }
}
