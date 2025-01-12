<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Kas;
use App\Models\Siswa;

class ProsesPotonganTabungan implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new job instance.
     */
    public function __construct(public $user_id) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $kas_potongan_id = config('custom.tabungan.potongan.kas_admin_id');
        $potongan = config('custom.tabungan.potongan.jumlah_per_tahun');
        $saldo_minimal = config('custom.tabungan.potongan.min_saldo');

        if (null === $kas_potongan_id or $kas_potongan_id == '') {
            $kas_potongan_id = Kas::first()->id;
        }

        // $run = 0;
        // $limit = 1;
        foreach (Siswa::where('status', 1)->whereIn('lembaga_id', config('custom.tabungan.potongan.lembaga'))->get() as $s) { // SD
            $total_tabungan = 0;
            $rincian = '';
            $tabungan_tidak_kena_admin = 0;
            if ($s->tabungan->count()) {
                $no = 0;
                foreach ($s->tabungan as $t) {
                    $no++;
                    $keterangan_potongan = '';
                    if ($t->saldo > $saldo_minimal) { //cek saldo apakah lebih dari saldo minimal
                        $saldo_akhir = ($t->saldo - $potongan);

                        $this->prosesTransaksi($kas_potongan_id, $t, $potongan);
                    } else {
                        $saldo_akhir = $t->saldo;
                        $tabungan_tidak_kena_admin++;
                        $keterangan_potongan = ' (tidak dikenakan potongan)';
                    }
                    $rincian .= $no . '. ' . $t->kas->nama . '. Saldo Rp ' . number_format($saldo_akhir, thousands_separator: '.') . ' ' . $keterangan_potongan . PHP_EOL;
                    $total_tabungan += $saldo_akhir;
                }

                //Kirim pesan HANYA jika ada tabungan yang kena potongan
                if ($tabungan_tidak_kena_admin != $no) {
                    $pesan[] = [
                        'name' => $s->nama,
                        'number' => env('APP_ENV') == 'local' ? env('WHATSAPP_TEST_NUMBER') : '' . $s->telepon,
                        'message' => $this->generatePesan($s, $rincian, $total_tabungan,  $potongan),
                        'sessionId' => \App\Services\WhatsappService::getSessionId($s)
                    ];
                }
            }

            //debug
            // $run++;
            // if ($run == $limit) {
            //     continue;
            // }
        }

        // Tandai tanggal pemotongan
        $this->tandaiTanggalPemotongan();

        //KIRIM WA
        \App\Services\WhatsappService::kirimWa(
            kumpulan_pesan: $pesan
        );
    }

    private function tandaiTanggalPemotongan(): void
    {
        $cfg = config('custom');
        $cfg['tabungan']['potongan']['tanggal'] = date('Y-m-d');
        $str = '<?php ' . PHP_EOL . '$local_config =' . var_export($cfg, true) . ';';
        file_put_contents(base_path('storage/app/local_config.php'), $str);
    }
    private function prosesTransaksi(string $kas_potongan_id, mixed $tabungan, int $jumlah): void
    {
        //TRANSAKSI Keluar
        \App\Traits\TransaksiTrait::prosesTransaksi(
            kas_id: $tabungan->kas_id,
            mutasi: 'k',
            jenis: 'TB',
            transable_id: $tabungan->id,
            jumlah: $jumlah,
            keterangan: 'Administrasi ' . $tabungan->kas->nama . ' ' . $tabungan->siswa->nama,
            user_id: $this->user_id
        );

        //TRANSAKSI Masuk
        \App\Traits\TransaksiTrait::prosesTransaksi(
            kas_id: $kas_potongan_id,
            mutasi: 'm',
            jenis: 'TX',
            transable_id: $kas_potongan_id,
            jumlah: $jumlah,
            keterangan: 'Masuk Administrasi ' . $tabungan->kas->nama . ' ' . $tabungan->siswa->nama,
            user_id: $this->user_id
        );

        $tabungan->decrement('saldo', $jumlah);
    }

    private function generatePesan($s, $rincian_tabungan, $total_tabungan, $jumlah_potongan): string
    {
        return \App\Services\WhatsappService::prosesPesan(
            siswa: $s,
            data: [
                'lembaga' => config('custom.lembaga.' . $s->lembaga_id),
                'kontak.nama' => config('custom.kontak_lembaga.' . $s->lembaga_id . '.kontak'),
                'tabungan.potongan' => 'Rp ' . number_format($jumlah_potongan, thousands_separator: '.'),
                'tabungan.rincian' => $rincian_tabungan,
                'tabungan.total' => 'Rp ' . number_format($total_tabungan, thousands_separator: '.'),
            ],
            jenis: 'tabungan.potongan'
        );
    }
}
