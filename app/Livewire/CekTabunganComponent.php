<?php

namespace App\Livewire;

use App\Models\Siswa;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CekTabunganComponent extends Component
{
    public $nik;
    public $pesan = '';
    public $cek = false;
    public $success = false;
    public $punya_tabungan = false;
    public function render()
    {
        return view('livewire.cek-tabungan-component');
    }
    public function cekTabungan()
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
            $this->cek = true;
            if ($siswa->telepon != '') {
                if (!$siswa->tabungan->count()) {
                    $this->pesan = 'Data tabungan siswa <span class="font-bold">' . $siswa->nama . '</span> Kelas ' . $siswa->kelas->nama . ' tidak ditemukan. Harap hubungi bagian Keuangan ' . config('custom.lembaga.' . $siswa->lembaga_id) . ' jika terdapat kesalahan.';
                } else {
                    $nomor = $siswa->telepon;
                    $rincian = '';
                    $no = 1;
                    $total = 0;
                    foreach ($siswa->tabungan as $t) {
                        $rincian .= $no . '. ' . $t->kas->nama . '. Saldo Rp ' . number_format($t->saldo, thousands_separator: '.') . PHP_EOL;
                        $total += $t->saldo;
                        $no++;
                    }
                    if ($total > 0) {
                        $this->punya_tabungan = true;
                        $pesan = \App\Services\WhatsappService::prosesPesan(
                            $siswa,
                            [
                                'lembaga' => config('custom.lembaga.' . $siswa->lembaga_id),
                                'kontak.nama' => config('custom.kontak_lembaga.' . $siswa->lembaga_id . '.kontak'),
                                'tabungan.rincian' => $rincian,
                                'tabungan.total' => 'Rp ' . number_format($total, thousands_separator: '.'),
                            ],
                            'tabungan.daftar'
                        );
                        $response = \App\Services\WhatsappService::kirimWa(
                            nama: $siswa->nama,
                            nomor: $nomor,
                            pesan: $pesan,
                            sessionId: \App\Services\WhatsappService::getSessionId($siswa)
                        );
                        if ($response['status'] == 'success') {
                            $this->success = true;
                            $this->pesan = 'Rincian Tabungan siswa <span class="font-bold">' . $siswa->nama . '</span> Kelas ' . $siswa->kelas->nama . ' telah dikirimkan ke ' . substr($nomor, 0, 4) . '*****' . substr($nomor, -3);
                        } else {
                            $this->success = false;
                            $this->pesan = $response['message'];
                        }
                    } else {
                        $this->pesan = 'Saldo Tabungan siswa <span class="font-bold">' . $siswa->nama . '</span> Kelas ' . $siswa->kelas->nama . ' saat ini adalah <span class="font-bold">Rp. 0</span>. Terima kasih';
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
            'cek',
            'punya_tabungan',
            'pesan'
        ]);
    }
}
