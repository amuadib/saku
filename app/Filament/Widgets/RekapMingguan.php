<?php

namespace App\Filament\Widgets;

use App\Models\RekapTransaksiHarian;
use Filament\Widgets\Widget;

class RekapMingguan extends Widget
{
    protected static string $view = 'filament.widgets.rekap-mingguan';
    protected static ?int $sort = 4;
    public $data = [];
    public $masuk, $keluar;
    public function mount()
    {
        $saldo = 0;
        foreach (RekapTransaksiHarian::rekapMingguan()->get() as $r) {
            if ($r->masuk > 0) {
                $saldo += $r->masuk;
                $this->masuk += $r->masuk;
                $this->data[] = [
                    'tanggal' => $r->tanggal,
                    'kas' => $r->kas,
                    'masuk' => 'Rp ' . number_format(intval($r->masuk), thousands_separator: '.'),
                    'keluar' => '-',
                    'saldo' => 'Rp ' . number_format(intval($saldo), thousands_separator: '.'),
                ];
            }
            if ($r->keluar > 0) {
                $saldo -= $r->keluar;
                $this->keluar += $r->keluar;
                $this->data[] = [
                    'tanggal' => $r->tanggal,
                    'kas' => $r->kas,
                    'masuk' => '-',
                    'keluar' => 'Rp ' . number_format(intval($r->keluar), thousands_separator: '.'),
                    'saldo' => 'Rp ' . number_format(intval($saldo), thousands_separator: '.'),
                ];
            }
        }
    }
}
