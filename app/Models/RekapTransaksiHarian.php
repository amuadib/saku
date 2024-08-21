<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapTransaksiHarian extends Model
{
    use HasUuids;
    protected $table = 'rekap_transaksi_harian';

    public function scopeRekapMingguan($query, string $kas_id = 'All', string $start = null, string $finish = null)
    {
        if ($start == null) {
            $start = (date('D') != 'Sun') ? date('Y-m-d', strtotime('last Sunday')) . ' 00:00:01' : date('Y-m-d') . ' 00:00:01';
        }
        if ($finish == null) {
            $finish = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        }
        $query
            ->join('kas', 'kas_id', '=', 'kas.id')
            ->when(
                !auth()->user()->isAdmin(),
                function ($w) {
                    $w
                        ->where('kas.lembaga_id', auth()->user()->authable->lembaga_id);
                }
            )
            ->when(
                $kas_id !== 'All',
                function ($w) use ($kas_id) {
                    $w
                        ->where('kas.id', $kas_id);
                }
            )
            ->whereBetween('tanggal', [$start, $finish])
            ->orderBy('created_at')
            ->selectRaw('`kas_id`,`kas`.`nama` as `kas`, `tanggal`, `masuk`, `keluar`');
    }
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
}
