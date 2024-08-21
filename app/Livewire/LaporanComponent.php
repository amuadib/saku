<?php

namespace App\Livewire;

use App\Models\Kas;
use App\Models\RekapTransaksiHarian;
use Livewire\Component;

class LaporanComponent extends Component
{
    public $data = [];
    public $masuk, $keluar, $awal, $akhir;
    public $kas_list;
    public $kas_id = 'All';
    public $data_per_tanggal = [];

    public function mount()
    {
        $this->kas_list = Kas::when(
            !auth()->user()->isAdmin(),
            function ($w) {
                $w
                    ->where('lembaga_id', auth()->user()->authable->lembaga_id);
            }
        )
            ->pluck('nama', 'id')
            ->toArray();
        $this->getData();
    }

    public function render()
    {
        return view('livewire.laporan-component');
    }

    public function updateTable()
    {
        $this->reset([
            'data',
            'masuk',
            'keluar',
            'data_per_tanggal'
        ]);
        $this->getData();
    }

    private function getData()
    {
        $saldo = 0;
        foreach (RekapTransaksiHarian::rekapMingguan($this->kas_id, $this->awal, $this->akhir)->get() as $r) {

            if ($r->masuk > 0) {
                if (isset($this->data_per_tanggal[$r->tanggal])) {
                    $this->data_per_tanggal[$r->tanggal]++;
                } else {
                    $this->data_per_tanggal[$r->tanggal] = 1;
                }
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
                if (isset($this->data_per_tanggal[$r->tanggal])) {
                    $this->data_per_tanggal[$r->tanggal]++;
                } else {
                    $this->data_per_tanggal[$r->tanggal] = 1;
                }
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
