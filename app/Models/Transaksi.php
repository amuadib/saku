<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasUuids;
    protected $table = 'transaksi';

    public function transable()
    {
        return $this->morphTo();
    }
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenis()
    {
        return substr($this->transable_type, 11);
    }
    // public function kas(): BelongsTo
    // {
    //     return $this->belongsTo(Kas::class);
    // }
    // public function tagihan(): BelongsTo
    // {
    //     return $this->belongsTo(Tagihan::class);
    // }
    // public function tabungan(): BelongsTo
    // {
    //     return $this->belongsTo(Tabungan::class);
    // }
}
