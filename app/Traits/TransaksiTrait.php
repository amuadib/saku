<?php

namespace App\Traits;

use App\Models\Transaksi;
use Illuminate\Support\Str;

trait TransaksiTrait
{
    public static function prosesTransaksi(
        string $kas_id,
        string $mutasi,
        string $jenis,
        string $transable_id,
        int $jumlah,
        string|null $keterangan = null,
    ): string {
        // Update Kas
        \App\Traits\KasTrait::updateSaldoKas(
            id: $kas_id,
            jumlah: $mutasi == 'm' ? $jumlah : -1 * abs($jumlah)    //negatif jika mutasi keluar
        );

        // Input transaksi
        return \App\Traits\TransaksiTrait::inputTransaksi(
            mutasi: $mutasi,
            jenis: $jenis,
            transable_id: $transable_id,
            jumlah: $jumlah,
            keterangan: $keterangan
        );
    }

    public static function getKodeTransaksi($prefix)
    {
        $jml = Transaksi::where('created_at', 'like', date('Y-m-d') . '%')->count() ?? 0;
        return $prefix . date('Ymd') . str_pad(($jml + 1), 4, '0', STR_PAD_LEFT);
    }

    public static function inputTransaksi(
        string $mutasi,
        string $jenis,
        string $transable_id,
        int $jumlah,
        string|null $keterangan = null,
    ): string {
        $transaksi = [
            'TG' => 'Tagihan',
            'TB' => 'Tabungan',
            'TX' => 'Transaksi',
            'PJ' => 'Penjualan',
        ];
        $kode = \App\Traits\TransaksiTrait::getKodeTransaksi(prefix: strtoupper($mutasi . $jenis));
        Transaksi::insert([
            'id' => Str::orderedUuid(),
            'kode' => $kode,
            // 'mutasi' => $mutasi,
            'jumlah' => $jumlah,
            'transable_type' => 'App\\Models\\' . $transaksi[$jenis],
            'transable_id' => $transable_id,
            'keterangan' => $keterangan,
            'user_id' => auth()->user()->id,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        return $kode;
    }
}
