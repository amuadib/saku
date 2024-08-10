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
    public $data_per_tanggal = [];
    public function mount()
    {
        $saldo = 0;
        foreach (RekapTransaksiHarian::rekapMingguan()->get() as $r) {
            if (isset($this->data_per_tanggal[$r->tanggal])) {
                $this->data_per_tanggal[$r->tanggal]++;
            } else {
                $this->data_per_tanggal[$r->tanggal] = 1;
            }
            if ($r->masuk > 0) {
                $saldo += $r->masuk;
                $this->masuk += $r->masuk;
                $this->data[] = [
                    'tanggal' => $r->tanggal,
                    'kas' => $r->kas,
                    'masuk' => number_format(intval($r->masuk), thousands_separator: '.'),
                    'keluar' => 0,
                    'saldo' => number_format(intval($saldo), thousands_separator: '.'),
                ];
            }
            if ($r->keluar > 0) {
                $saldo -= $r->keluar;
                $this->keluar += $r->keluar;
                $this->data[] = [
                    'tanggal' => $r->tanggal,
                    'kas' => $r->kas,
                    'masuk' => 0,
                    'keluar' => number_format(intval($r->keluar), thousands_separator: '.'),
                    'saldo' => number_format(intval($saldo), thousands_separator: '.'),
                ];
            }
        }
    }
}
