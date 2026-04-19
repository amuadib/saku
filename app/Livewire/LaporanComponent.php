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

        if (empty($this->awal)) {
            $this->awal = (date('D') != 'Sun') ? date('Y-m-d', strtotime('last Sunday')) : date('Y-m-d');
        }
        if (empty($this->akhir)) {
            $this->akhir = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
        }

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
        $this->masuk = 0;
        $this->keluar = 0;
        $this->data = [];
        $this->data_per_tanggal = [];

        // 1. Hitung Saldo Awal (sebelum tanggal awal)
        $saldo = RekapTransaksiHarian::getSaldoAwal($this->kas_id, $this->awal);
        
        // 2. Tambahkan baris Saldo Awal ke data jika ada saldo awal
        $this->data[] = [
            'tanggal' => date('Y-m-d', strtotime($this->awal . ' -1 day')),
            'kas' => 'Saldo Awal',
            'masuk' => 0,
            'keluar' => 0,
            'saldo' => number_format(intval($saldo), 0, ',', '.'),
        ];
        
        // Tandai baris saldo awal agar dihitung di rowspan jika tanggalnya sama (meskipun pakai label 'Saldo Awal')
        $prev_date = date('Y-m-d', strtotime($this->awal . ' -1 day'));
        $this->data_per_tanggal[$prev_date] = 1;

        // 3. Ambil data transaksi periode terpilih
        $transactions = RekapTransaksiHarian::periode($this->kas_id, $this->awal, $this->akhir)->get();

        foreach ($transactions as $r) {
            if ($r->masuk > 0) {
                $saldo += $r->masuk;
                $this->masuk += $r->masuk;
                $this->data[] = [
                    'tanggal' => $r->tanggal,
                    'kas' => $r->kas,
                    'masuk' => number_format(intval($r->masuk), 0, ',', '.'),
                    'keluar' => 0,
                    'saldo' => number_format(intval($saldo), 0, ',', '.'),
                ];
                
                // Hitung rowspan per tanggal
                if (isset($this->data_per_tanggal[$r->tanggal])) {
                    $this->data_per_tanggal[$r->tanggal]++;
                } else {
                    $this->data_per_tanggal[$r->tanggal] = 1;
                }
            }
            if ($r->keluar > 0) {
                $saldo -= $r->keluar;
                $this->keluar += $r->keluar;
                $this->data[] = [
                    'tanggal' => $r->tanggal,
                    'kas' => $r->kas,
                    'masuk' => 0,
                    'keluar' => number_format(intval($r->keluar), 0, ',', '.'),
                    'saldo' => number_format(intval($saldo), 0, ',', '.'),
                ];

                // Hitung rowspan per tanggal
                if (isset($this->data_per_tanggal[$r->tanggal])) {
                    $this->data_per_tanggal[$r->tanggal]++;
                } else {
                    $this->data_per_tanggal[$r->tanggal] = 1;
                }
            }
        }
    }
}
