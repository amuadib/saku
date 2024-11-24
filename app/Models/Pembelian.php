<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    use HasUuids;
    protected $table = 'pembelian';
    public function supplier(): BelongsTo
    {
        return $this->BelongsTo(Supplier::class, 'supplier_id');
    }
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function detail(): HasMany
    {
        return $this->HasMany(DetailPembelian::class, 'pembelian_id');
    }
}
