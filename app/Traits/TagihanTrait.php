<?php

namespace App\Traits;

use App\Models\Tagihan;

trait TagihanTrait
{
    public static function getKodeTagihan($prefix)
    {
        $jml = Tagihan::where('created_at', 'like', date('Y-m-d') . '%')->count() ?? 0;
        return $prefix . date('Ymd') . str_pad(($jml + 1), 4, '0', STR_PAD_LEFT);
    }
}
