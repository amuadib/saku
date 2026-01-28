<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Helpers\Telegram;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class PengaduanComponent extends Component
{
    // #[Validate('required')]
    public $nik;

    #[Validate('required')]
    public $laporan;

    // 🪤 Honeypot fields
    public $website = '';     // hidden text
    public $lampiran = null;  // fake upload
    public $startedAt;

    public $terkirim = false;

    public function mount()
    {
        $this->startedAt = now()->timestamp;
    }

    public function render()
    {
        return view('livewire.pengaduan-component');
    }

    public function kirim()
    {
        // =============================
        // RATE LIMITER (ANTI BOT)
        // =============================
        $key = 'pengaduan:' . Request::ip();

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
            throw ValidationException::withMessages(['nik' => 'Data Siswa tidak ditemukan']);
        } else {
            Pengaduan::create([
                'siswa_id' => $siswa->id,
                'lembaga_id' => $siswa->lembaga_id,
                'laporan' => $this->laporan
            ]);

            $this->reset(['nik', 'laporan']);
            $this->terkirim = true;
        }
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
        Log::warning('HONEYPOT PENGADUAN', $data);

        // TELEGRAM ALERT
        if (!Cache::has('tg_' . $data['ip'])) {
            Telegram::send(
                "🚨 <b>HONEYPOT PENGADUAN</b>\n\n" .
                    "🧠 <b>Reason:</b> {$data['reason']}\n" .
                    "🌐 <b>IP:</b> {$data['ip']}\n" .
                    "🖥️ <b>UA:</b> {$data['ua']}\n" .
                    "⏰ <b>Time:</b> {$data['time']}"
            );
            Cache::put('tg_' . $data['ip'], true, 300); // 5 menit
        }

        // pura-pura sukses, jangan bocorkan ke bot
        usleep(300000);

        $this->reset(['nik', 'laporan']);
        $this->terkirim = true;
    }
}
