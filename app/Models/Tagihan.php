<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Tagihan extends Model
{
    // use HasFactory;
    use HasUuids;
    protected $table = 'tagihan';

    public function transaksi(): MorphOne
    {
        return $this->morphOne(Transaksi::class, 'transable');
    }
    public function tagihanable()
    {
        return $this->morphTo();
    }
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isLunas(): bool
    {
        return $this->bayar > 0 and $this->bayar == $this->jumlah;
    }
}
