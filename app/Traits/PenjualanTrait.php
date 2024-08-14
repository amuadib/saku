<?php

namespace App\Traits;

use App\Models\Penjualan;

trait PenjualanTrait
{
    public static function getKode()
    {
        $jml = Penjualan::where('created_at', 'like', date('Y-m-d') . '%')->count() ?? 0;
        return 'PJ' . date('Ymd') . str_pad(($jml + 1), 4, '0', STR_PAD_LEFT);
    }
}
