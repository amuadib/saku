<?php

namespace App\Livewire;

use App\Models\Siswa;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Helpers\Telegram;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class CekTagihanComponent extends Component
{
    public $nik;
    public $pesan = '';
    public $cek = false;
    public $success = false;
    public $punya_tagihan = true;

    // 🪤 Honeypot fields
    public $website = '';     // hidden text
    public $lampiran = null;  // fake upload
    public $startedAt;

    public function render()
    {
        return view('livewire.cek-tagihan-component');
    }

    public function cekTagihan()
    {
        // =============================
        // RATE LIMITER (ANTI BOT)
        // =============================
        $key = 'tagihan:' . Request::ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Terlalu banyak percobaan, silakan coba lagi nanti.');
        }

        RateLimiter::hit($key, 60); // 5 percobaan per 60 det
        // =============================
        // HONEYPOT CHECK
        // =============================

        // 1. Hidden field diisi
        if (!empty($this->website)) {
            return $this->trap('hidden_field');
        }

        // 2. Submit terlalu cepat (< 3 detik)
        if (now()->timestamp - $this->startedAt < 3) {
            return $this->trap('too_fast');
        }

        // 3. Fake upload diisi
        if (!empty($this->lampiran)) {
            return $this->trap('fake_upload');
        }
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
                if (!$siswa->tagihan->count()) {
                    $this->pesan = 'Data tagihan siswa <span class="font-bold">' . $siswa->nama . '</span> Kelas ' . $siswa->kelas->nama . ' tidak ditemukan. Harap hubungi bagian Keuangan ' . config('custom.lembaga.' . $siswa->lembaga_id) . ' jika terdapat kesalahan.';
                    $this->punya_tagihan = false;
                } else {
                    $nomor = $siswa->telepon;
                    $rincian = '';
                    $no = 1;
                    $total = 0;
                    foreach ($siswa->tagihan as $t) {
                        if (!$t->isLunas()) {
                            $rincian .= $no . '. ' . $t->kas->nama . ' ' . $t->keterangan . ' Rp ' . number_format($t->jumlah, thousands_separator: '.') . PHP_EOL;
                            $total += $t->jumlah;
                            $no++;
                        }
                    }
                    if ($total > 0) {
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
                        $response = \App\Services\WhatsappService::kirimWa(
                            nama: $siswa->nama,
                            nomor: $nomor,
                            pesan: $pesan,
                            sessionId: \App\Services\WhatsappService::getSessionId($siswa)
                        );
                        if ($response['status'] == 'success') {
                            $this->success = true;
                            $this->pesan = 'Tagihan siswa <span class="font-bold">' . $siswa->nama . '</span> Kelas ' . $siswa->kelas->nama . ' telah dikirimkan ke ' . substr($nomor, 0, 4) . '*****' . substr($nomor, -3);
                        } else {
                            $this->success = false;
                            $this->pesan = $response['message'];
                        }
                    } else {
                        $this->pesan = 'Semua tagihan siswa <span class="font-bold">' . $siswa->nama . '</span> Kelas ' . $siswa->kelas->nama . ' sudah LUNAS. Terima kasih';
                        $this->punya_tagihan = false;
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
            'punya_tagihan',
            'pesan'
        ]);
    }

    protected function trap(string $reason)
    {
        $data = [
            'reason' => $reason,
            'ip'     => Request::ip(),
            'ua'     => Request::userAgent(),
            'time'   => now()->toDateTimeString(),
        ];

        // LOG FILE
        Log::warning('HONEYPOT TAGIHAN', $data);

        // TELEGRAM ALERT
        if (!Cache::has('tg_' . $data['ip'])) {
            Telegram::send(
                "🚨 <b>HONEYPOT TAGIHAN</b>\n\n" .
                    "🧠 <b>Reason:</b> {$data['reason']}\n" .
                    "🌐 <b>IP:</b> {$data['ip']}\n" .
                    "🖥️ <b>UA:</b> {$data['ua']}\n" .
                    "⏰ <b>Time:</b> {$data['time']}"
            );
            Cache::put('tg_' . $data['ip'], true, 300); // 5 menit
        }

        // pura-pura sukses, jangan bocorkan ke bot
        usleep(300000);
        $this->success = true;
        $this->pesan = 'Semua tagihan siswa sudah LUNAS. Terima kasih';
        $this->punya_tagihan = false;
        $this->resetForm();
    }
}
