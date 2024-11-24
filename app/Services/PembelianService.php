<?php

namespace App\Services;

use App\Models\Pembelian;

class PembelianService
{
    public static function getKode(int $lembaga_id)
    {
        $jml = Pembelian::where('created_at', 'like', date('Y-m-d') . '%')->count() ?? 0;
        return 'PB' . $lembaga_id . date('ymd') . str_pad(($jml + 1), 4, '0', STR_PAD_LEFT);
    }
}
