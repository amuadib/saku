<?php

namespace App\Services;

use Illuminate\Support\Arr;

class WhatsappService
{
    public static function getSessionId($siswa): string
    {
        switch (substr($siswa->kelas->nama, 0, 1)) {
            case '1':
            case '2':
                $sessionId = 'MU1';
                break;
            case '5':
            case '6':
                $sessionId = 'MU3';
                break;
            default:
                $sessionId = 'MU2';
        }

        return $sessionId;
    }
    public static function prosesPesan(mixed $siswa, array $data, string $jenis): string
    {
        $test_message = env('APP_ENV') == 'local' ? '---TES PENGIRIMAN WHATSAPP MOHON DIABAIKAN JIKA DITERIMA---' . PHP_EOL : '';
        $template = config('custom.template');
        $tabungan = '';
        $t3 = $template['akhir'];

        $awal = \App\Services\WhatsappService::prosesTemplate(
            [
                'siswa.nama' => $siswa->nama,
                'lembaga' => config('custom.lembaga.' . $siswa->lembaga_id)
            ],
            $siswa->status == 3 ? $template['awal_alumni'] : $template['awal']
        );
        $isi = \App\Services\WhatsappService::prosesTemplate(
            $data,
            Arr::get($template, $jenis, 'Pesan WA')
        );

        if ($jenis == 'tagihan.daftar') {
            $total_tabungan = 0;
            $rincian = '';
            if ($siswa->tabungan->count()) {
                $no = 1;
                foreach ($siswa->tabungan as $t) {
                    $rincian .= $no . '. ' . $t->kas->nama . '. Saldo Rp ' . number_format($t->saldo, thousands_separator: '.') . PHP_EOL;
                    $total_tabungan += $t->saldo;
                }
            }

            // if ($total_tabungan > 0) {
            $tabungan = \App\Services\WhatsappService::prosesTemplate(
                [
                    'tabungan.rincian' => $rincian,
                    'tabungan.total' => 'Rp ' . number_format($total_tabungan, thousands_separator: '.')
                ],
                $template['tabungan']['daftar']
            );
            // }
        }

        if ($jenis == 'tagihan.daftar') {
            $t3 = $template['akhir_daftar'];
        } elseif ($jenis == 'tagihan.bayar') {
            $t3 = $template['akhir_bayar'];
        } elseif ($jenis == 'tagihan.daftar_alumni') {
            $t3 = $template['akhir_alumni'];
        }

        $akhir = \App\Services\WhatsappService::prosesTemplate(
            [
                'kontak.nama' => config('custom.kontak_lembaga.' . $siswa->lembaga_id . '.kontak'),
                'kontak.telp' => config('custom.kontak_lembaga.' . $siswa->lembaga_id . '.telp'),
                'lembaga' => config('custom.lembaga.' . $siswa->lembaga_id)
            ],
            $t3
        );

        $nb = $template['nb'] ?? '';
        return $test_message . $awal . $isi . $tabungan . $akhir . $nb . $template['footer'];
    }

    public static function prosesTemplate(array $data, string $template): string
    {
        // https://stackoverflow.com/a/48981341
        if (preg_match_all("/{(.*?)}/", $template, $m)) {
            foreach ($m[1] as $i => $varname) {
                $template = str_replace($m[0][$i], sprintf('%s', $data[$varname]), $template);
            }
        }
        return $template;
    }

    public static function kirimWa(
        string|null $nomor = '',
        string|null $pesan = '',
        array|null $kumpulan_pesan = [],
        string|null $sessionId = null,
        string|null $nama = null
    ) {
        if (env('WA_API_URL') == '') {
            return [
                'status' => 'failed',
                'message' => 'API Whatsapp belum diaktifkan'
            ];
        }
        $nomor = env('APP_ENV') == 'local' ? env('WHATSAPP_TEST_NUMBER') : $nomor;
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('WA_API_URL'),
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
        $body = [
            'token' => env('WA_API_TOKEN'),
        ];
        if (count($kumpulan_pesan) > 0) {
            $body = array_merge($body, [
                'data' => json_encode($kumpulan_pesan)
            ]);
        } else {
            $body = array_merge($body, [
                'name' => $nama,
                'number' => $nomor,
                'message' => $pesan,
                'sessionId' => $sessionId,
            ]);
        }

        try {
            $response = $client->post('/api/message/send', ['body' => json_encode($body)]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return [
                'status' => 'failed',
                'message' => 'Gangguan koneksi ke WA API'
            ];
        }

        $response_json = $response->getBody()->getContents();
        $response_arr = json_decode($response_json, true);

        if ($response_arr == null) {
            \Log::error('[' . date('Y-m-d H:i:s') . '] Gagal mengirim pesan');
            return [
                'status' => 'failed',
                'message' => 'Gagal mengirim pesan'
            ];
        } else {
            // \Log::info('Sukses mengirim pesan ke ' . $nomor);
            if ($response_arr['error']) {
                return [
                    'status' => 'failed',
                    'message' => $response_arr['message']
                ];
            } else {
                return [
                    'status' => 'success',
                    'message' => 'Sukses mengirim pesan ke ' . $nomor
                ];
            }
        }
    }
}
