<?php

namespace App\Livewire;

use App\Models\Siswa;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CekTagihanComponent extends Component
{
    // #[Validate('required')]
    public $nik;

    public $nomor = null;

    public function render()
    {
        return view('livewire.cek-tagihan-component');
    }

    public function cek()
    {
        $this->validate(
            [
                'nik' => 'required'
            ],
            [
                'nik.required' => 'Mohon mengisi NIK / NISN terlebih dahulu'
            ]
        );
        $siswa = Siswa::where('nik', trim($this->nik))
            ->orWhere('nisn', trim($this->nik))
            ->first();
        if (!$siswa) {
            throw ValidationException::withMessages(['nik' => 'Maaf, Data Siswa tidak ditemukan']);
        } else {
            if ($siswa->telepon != '') {
                $this->nomor = substr($siswa->telepon, 0, 4) . '*****' . substr($siswa->telepon, -3);
            } else {
                throw ValidationException::withMessages(['nik' => 'Maaf, Nomor WhatsApp Siswa belum diisi.  Harap hubungi Petugas di Kantor ' . config('custom.lembaga')[$siswa->lembaga_id] . ' pada jam kerja']);
            }
        }
    }

    public function resetForm()
    {
        $this->reset([
            'nik',
            'nomor'
        ]);
    }
}
