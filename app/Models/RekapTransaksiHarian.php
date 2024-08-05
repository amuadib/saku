<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapTransaksiHarian extends Model
{
    use HasUuids;
    protected $table = 'rekap_transaksi_harian';

    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
}
