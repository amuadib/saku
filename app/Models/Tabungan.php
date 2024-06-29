<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tabungan extends Model
{
    use HasUuids;
    protected $table = 'tabungan';

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
}
