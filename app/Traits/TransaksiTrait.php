<?php

namespace App\Traits;

use App\Models\Transaksi;

trait TransaksiTrait
{
    public static function prosesTransaksi(
        string $kas_id,
        string $mutasi,
        string $jenis,
        string $transable_id,
        int $jumlah,
        string|null $keterangan = null,
    ) {
        // Update Kas
        \App\Traits\KasTrait::updateSaldoKas(
            id: $kas_id,
            jumlah: $mutasi == 'm' ? $jumlah : -1 * abs($jumlah)    //negatif jika mutasi keluar
        );

        // Input transaksi
        \App\Traits\TransaksiTrait::inputTransaksi(
            mutasi: $mutasi,
            jenis: $jenis,
            id: $transable_id,
            jumlah: $jumlah,
            keterangan: $keterangan
        );
    }

    public static function getIdTransaksi($kode)
    {
        $jml = Transaksi::where('created_at', 'like', date('Y-m-d') . '%')->count() ?? 0;
        return strtoupper($kode) . date('Ymd') . str_pad(($jml + 1), 4, '0', STR_PAD_LEFT);
    }

    public static function inputTransaksi(
        string $mutasi,
        string $jenis,
        string $id,
        int $jumlah,
        string|null $keterangan = null,
    ): void {
        $transaksi = [
            'TG' => 'Tagihan',
            'TB' => 'Tabungan',
            'TX' => 'Transaksi'
        ];
        Transaksi::insert([
            'id' => \App\Traits\TransaksiTrait::getIdTransaksi(strtoupper($mutasi . $jenis)),
            'mutasi' => $mutasi,
            'jumlah' => $jumlah,
            'transable_type' => 'App\\Models\\' . $transaksi[$jenis],
            'transable_id' => $id,
            'keterangan' => $keterangan,
            'user_id' => \Auth::user()->id,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
