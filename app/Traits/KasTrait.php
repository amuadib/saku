<?php

namespace App\Traits;

use App\Models\Kas;

trait KasTrait
{
    public static function updateSaldoKas(string $id, int $jumlah): int
    {
        $kas = Kas::find($id);
        if ($kas) {
            if ($jumlah > 0) {
                $kas->increment('saldo', $jumlah);
            } else {
                $kas->decrement('saldo', abs($jumlah));
            };
            return $kas->lembaga_id;
        }
        return 99;
    }
}
