<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;

class PengaduanComponent extends Component
{
    // #[Validate('required')]
    public $nik;

    #[Validate('required')]
    public $laporan;

    public $terkirim = false;

    public function render()
    {
        return view('livewire.pengaduan-component');
    }

    public function kirim()
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
            throw ValidationException::withMessages(['nik' => 'Data Siswa tidak ditemukan']);
        } else {
            Pengaduan::create([
                'siswa_id' => $siswa->id,
                'laporan' => $this->laporan
            ]);

            $this->reset(['nik', 'laporan']);
            $this->terkirim = true;
        }
    }
}
