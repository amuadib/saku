<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    use HasUuids;
    protected $table = 'periode';
    protected $casts = [
        'aktif' => 'boolean',
    ];
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }
}
