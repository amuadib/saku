<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembelian extends Model
{
    use HasUuids;
    protected $table = 'detail_pembelian';
    public $timestamps = false;

    public function barang(): BelongsTo
    {
        return $this->BelongsTo(Barang::class, 'barang_id');
    }
}
