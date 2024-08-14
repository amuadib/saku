<?php

namespace App\Services;

use Illuminate\Support\Arr;

class WhatsappService
{

    public static function prosesPesan(mixed $siswa, array $data, string $jenis): string
    {
        $template = config('custom.template');
        $awal = \App\Services\WhatsappService::prosesTemplate(
            ['siswa.nama' => $siswa->nama],
            $template['awal']
        );
        $isi = \App\Services\WhatsappService::prosesTemplate(
            $data,
            Arr::get($template, $jenis, 'Pesan WA')
        );
        $akhir = \App\Services\WhatsappService::prosesTemplate(
            [
                'kontak.nama' => config('custom.kontak_lembaga.' . $siswa->lembaga_id . '.kontak'),
                'kontak.telp' => config('custom.kontak_lembaga.' . $siswa->lembaga_id . '.telp'),
            ],
            $template['akhir']
        );
        return $awal . $isi . $akhir . $template['footer'];
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

    public static function kirimWa(string|null $nomor = '', string|null $pesan = '', array|null $kumpulan_pesan = [])
    {
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
                'data' => $kumpulan_pesan
            ]);
        } else {
            $body = array_merge($body, [
                'number' => $nomor,
                'message' => $pesan
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
            // Log::error('Gagal mengirim pesan ke ' . $nomor);
            return [
                'status' => 'failed',
                'message' => 'Gagal mengirim pesan ke ' . $nomor
            ];
        } else {
            // Log::info('Sukses mengirim pesan ke ' . $data['number']);
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
