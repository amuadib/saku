<?php

namespace App\Services;

class WhatsappService
{
    public static function kirimWa(string|null $nomor = '', string|null $pesan = '', array|null $kumpulan_pesan = [])
    {
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
                'message' => $pesan . config('custom.template.footer')
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
