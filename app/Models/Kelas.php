<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasUuids;
    protected $table = 'kelas';
    public function scopeGetDaftarKelas($query, $lembaga_id, $operation)
    {
        $query
            ->join('periode', 'periode.id', '=', 'periode_id')
            ->when($operation == 'create', function ($w) {
                $w->where('periode.aktif', true);
            })
            ->when($lembaga_id != 99, function ($w) use ($lembaga_id) {
                $w->where('lembaga_id', $lembaga_id);
            })
            ->select('kelas.id', 'kelas.nama', 'kelas.lembaga_id', 'periode.nama as nama_periode');
    }
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
}
