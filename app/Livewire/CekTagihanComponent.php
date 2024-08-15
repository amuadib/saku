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

    public $pesan = '';
    public $send = false;
    public $success = false;
    public $tagihan = false;

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
                if (!$siswa->tagihan->count()) {
                } else {
                    $nomor = $siswa->telepon;

                    $this->tagihan = true;
                    $this->send = true;
                    $rincian = '';
                    $no = 1;
                    $total = 0;
                    foreach ($siswa->tagihan as $t) {
                        $rincian .= $no . '. ' . $t->kas->nama . ' ' . $t->keterangan . ' Rp ' . number_format($t->jumlah, thousands_separator: '.') . PHP_EOL;
                        $total += $t->jumlah;
                        $no++;
                    }

                    $pesan = \App\Services\WhatsappService::prosesPesan(
                        $siswa,
                        [
                            'lembaga' => config('custom.lembaga.' . $siswa->lembaga_id),
                            'kontak.nama' => config('custom.kontak_lembaga.' . $siswa->lembaga_id . '.kontak'),
                            'tagihan.rincian' => $rincian,
                            'tagihan.total' => 'Rp ' . number_format($total, thousands_separator: '.'),
                        ],
                        'tagihan.daftar'
                    );
                    $response = \App\Services\WhatsappService::kirimWa($nomor, $pesan);
                    if ($response['status'] == 'success') {
                        $this->success = true;
                        $this->pesan = 'Tagihan telah dikirimkan ke ' . substr($nomor, 0, 4) . '*****' . substr($nomor, -3);
                    } else {
                        $this->success = false;
                        $this->pesan = $response['message'];
                    }
                }
            } else {
                throw ValidationException::withMessages(['nik' => 'Maaf, Nomor WhatsApp Siswa belum diisi.  Harap hubungi Petugas di Kantor ' . config('custom.lembaga')[$siswa->lembaga_id] . ' pada jam kerja']);
            }
        }
    }

    public function resetForm()
    {
        $this->reset([
            'nik',
            'send'
        ]);
    }
}
