<?php

namespace App\Livewire;

use App\Models\Siswa;
use Livewire\Component;

class TagihanKeuangan extends Component
{
    public $siswa = null;
    public $kode = null;
    public $kode_akses = null;
    public $error = null;

    public function mount($kode)
    {
        if ($kode != null) {
            $this->kode_akses = $kode;
        }
    }
    public function render()
    {
        if ($this->kode_akses) {
            $siswa = Siswa::where('kode_akses', $this->kode_akses)->first();
            if ($siswa) {
                session(['siswa' => $siswa]);
                $this->siswa = $siswa;
            } else {
                $this->error = 'Kode Akses tidak ditemukan. Silakan coba lagi.';
            }
        }
        if (session()->has('siswa')) {
            $this->siswa = session()->get('siswa');
            return view('livewire.tagihan-keuangan', ['siswa' => $this->siswa]);
        } else {
            return view('livewire.input-kode-siswa');
        }
    }

    public function verifikasiKodeSiswa()
    {
        if ($this->kode_akses == null || $this->kode_akses == '') {
            $this->error = 'Kode Akses tidak ditemukan.';
            return;
        }
        $siswa = Siswa::where('kode_akses', $this->kode_akses)->first();
        if ($siswa) {
            session(['siswa' => $siswa]);
        } else {
            $this->error = 'Kode Akses tidak ditemukan. Silakan coba lagi.';
        }
    }
}
