<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasUuids;
    protected $table = 'kas';
    public $timestamps = false;
    protected $casts = [
        'jenis_transaksi' => 'array',
        'ada_tagihan' => 'boolean',
    ];
    public function scopeGetDaftarKas($query, $lembaga_id = null)
    {
        $lembaga_id == null ? auth()->user()->authable->lembaga_id : $lembaga_id;
        $query
            ->when($lembaga_id != 99, function ($w) use ($lembaga_id) {
                $w->where('lembaga_id', $lembaga_id);
            })
            ->where('ada_tagihan', true)
            ->select('id', 'nama', 'lembaga_id', 'jenis_transaksi');
    }
}
