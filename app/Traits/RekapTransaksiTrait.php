<?php

namespace App\Traits;

use App\Models\RekapTransaksiHarian;

trait RekapTransaksiTrait
{
    public static function updateRekapTransaksi($kas_id, $mutasi, $jumlah)
    {
        $rekap = RekapTransaksiHarian::where('tanggal', date('Y-m-d'))
            ->where('kas_id', $kas_id);
        if ($rekap->exists()) {
            $mutasi == 'm' ? $rekap->increment('masuk', $jumlah) : $rekap->increment('keluar', $jumlah);
        } else {
            $masuk = $mutasi == 'm' ? $jumlah : 0;
            $keluar = $mutasi == 'k' ? $jumlah : 0;
            RekapTransaksiHarian::create([
                'tanggal' => date('Y-m-d'),
                'kas_id' => $kas_id,
                'masuk' => $masuk,
                'keluar' => $keluar,
            ]);
        }
    }
}
