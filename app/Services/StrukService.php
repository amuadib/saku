<?php

namespace App\Services;

use App\Models\DataStruk;
use Carbon\Carbon;

class StrukService
{
    public static function simpanStruk(array $data): string
    {
        $tmp = array_merge($data, [
            'tanggal' => Carbon::now()->format('d-m-Y'),
            'waktu' => Carbon::now()->format('H:i:s'),
            'petugas' => auth()->user()->authable->nama,
        ]);
        DataStruk::create([
            'kode' => $tmp['transaksi_id'],
            'data' => $tmp
        ]);

        return base64_encode(json_encode($tmp));
    }
}
