<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    // use HasFactory;
    use HasUuids;
    protected $table = 'tagihan';

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
}
