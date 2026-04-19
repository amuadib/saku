<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapTransaksiHarian extends Model
{
    use HasUuids;
    protected $table = 'rekap_transaksi_harian';

    public function scopePeriode($query, string $kas_id = 'All', string $start = null, string $finish = null)
    {
        if (empty($start)) {
            $start = (date('D') != 'Sun') ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d');
        }
        if (empty($finish)) {
            $finish = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
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
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->selectRaw('`rekap_transaksi_harian`.`id`, `kas_id`, `kas`.`nama` as `kas`, `tanggal`, `masuk`, `keluar`');
    }

    public static function getSaldoAwal(string $kas_id = 'All', string $date = null)
    {
        if (empty($date)) {
            return 0;
        }

        return self::query()
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
            ->where('tanggal', '<', $date)
            ->selectRaw('SUM(masuk) - SUM(keluar) as saldo')
            ->value('saldo') ?? 0;
    }

    public function scopeRekapMingguan($query, string $kas_id = 'All', string $start = null, string $finish = null)
    {
        return $this->scopePeriode($query, $kas_id, $start, $finish);
    }
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
}
