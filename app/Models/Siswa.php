<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Siswa extends Model
{
    use HasUuids, LogsActivity;

    protected $table = 'siswa';
    protected $casts = [
        'label' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logExcept([
                'id',
                'created_at',
                'updated_at',
            ]);
    }

    public function getNamaSiswaAttribute()
    {
        return ucwords(strtolower($this->nama));
    }
    public function scopeGetDaftarSiswa($query, $lembaga_id = null)
    {
        $lembaga_id == null ? auth()->user()->authable->lembaga_id : $lembaga_id;
        $query
            ->when($lembaga_id != 99, function ($w) use ($lembaga_id) {
                $w->where('lembaga_id', $lembaga_id);
            })
            ->where('status', 1)
            ->select('id', 'nama');
    }
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class);
    }
    public function tabungan(): HasMany
    {
        return $this->hasMany(Tabungan::class);
    }
    public function keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class);
    }
}
