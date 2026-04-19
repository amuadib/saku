<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class MasterDataService
{
    /**
     * Get API Token from cache or login
     * 
     * @param bool $forceRefresh
     * @return string
     * @throws Exception
     */
    private function getToken(bool $forceRefresh = false): string
    {
        $cacheKey = 'master_data_api_token';

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, 3600, function () {
            $url = config('services.master_data.url');
            $user = config('services.master_data.user');
            $password = config('services.master_data.password');

            if (!$url || !$user || !$password) {
                throw new Exception('Konfigurasi API Master Data tidak lengkap di .env');
            }

            $request = Http::asJson();
            if (app()->environment('local')) {
                $request->withoutVerifying();
            }

            $response = $request->post($url . '/api/login', [
                'email' => $user,
                'password' => $password,
            ]);

            if ($response->failed()) {
                $status = $response->status();
                $body = $response->body();
                throw new Exception("Gagal login ke API Master Data (Status: $status). Error: " . ($response->json('message') ?? substr($body, 0, 100)));
            }

            return $response->json('access_token');
        });
    }

    /**
     * Update data siswa dari Master API
     * 
     * @param Siswa $record
     * @param bool $isRetry
     * @return array
     * @throws Exception
     */
    public function updateSiswaFromApi(Siswa $record, bool $isRetry = false): array
    {
        $url = config('services.master_data.url');
        $token = $this->getToken();

        // Fetch Siswa Data
        $siswaRequest = Http::withToken($token);
        if (app()->environment('local')) {
            $siswaRequest->withoutVerifying();
        }

        $siswaResponse = $siswaRequest->get($url . '/api/siswa/' . $record->id);

        // Handle Token Expiration (401 Unauthorized)
        if ($siswaResponse->status() === 401 && !$isRetry) {
            // Refresh token and retry once
            $this->getToken(true);
            return $this->updateSiswaFromApi($record, true);
        }

        if ($siswaResponse->failed()) {
            throw new Exception('Data tidak ditemukan di Master API atau terjadi kesalahan jaringan.');
        }

        $responseJson = $siswaResponse->json();
        
        // Ambil data inti dari key 'data' (sesuai structure SiswaController di miftahululum)
        $data = $responseJson['data'] ?? $responseJson;

        // Update Record
        $fields = [
            'nama',
            'nisn',
            'nik',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'nama_ayah',
            'nama_ibu',
            'telepon',
            'foto',
        ];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $record->$field = $data[$field];
            }
        }
        $record->last_sync_at = now();
        $record->save();

        return [
            'success' => true,
            'message' => 'Data Siswa ' . $record->nama . ' berhasil diperbarui.',
            'data' => $data
        ];
    }

    /**
     * Sinkronisasi massal data siswa dari Master API
     * 
     * @param bool $isRetry
     * @return array
     * @throws Exception
     */
    public function syncSiswaFromApi(bool $isRetry = false): array
    {
        $url = config('services.master_data.url');
        $token = $this->getToken();

        $request = Http::withToken($token);
        if (app()->environment('local')) {
            $request->withoutVerifying();
        }

        $response = $request->get($url . '/api/siswa');

        if ($response->status() === 401 && !$isRetry) {
            $this->getToken(true);
            return $this->syncSiswaFromApi(true);
        }

        if ($response->failed()) {
            throw new Exception('Gagal mengambil daftar siswa dari Master API.');
        }

        $items = $response->json();
        $updatedCount = 0;
        $insertedCount = 0;
        $errors = [];

        $fields = [
            'nama',
            'nis',
            'nisn',
            'nik',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'nama_ayah',
            'nama_ibu',
            'telepon',
            'email',
            'status',
            'foto',
        ];

        foreach ($items as $item) {
            $id = $item['id'] ?? null;
            if (!$id) continue;

            try {
                $record = Siswa::find($id);
                $isNew = false;
                
                if (!$record) {
                    $record = new Siswa();
                    $record->id = $id;
                    $record->lembaga_id = null;
                    $record->kelas_id = null;
                    $isNew = true;
                }

                foreach ($fields as $field) {
                    if (array_key_exists($field, $item)) {
                        $record->$field = $item[$field];
                    } else {
                        // Defaults if field not found in API response
                        if (in_array($field, ['nis', 'email'])) {
                            $record->$field = null;
                        } elseif ($field === 'status') {
                            $record->$field = $record->$field ?? 99;
                        }
                    }
                }
                $record->last_sync_at = now();

                if ($record->isDirty()) {
                    $record->save();
                    if ($isNew) {
                        $insertedCount++;
                    } else {
                        $updatedCount++;
                    }
                }
            } catch (Exception $e) {
                $errors[] = [
                    'id' => $id,
                    'nama' => $item['nama'] ?? 'Unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        $errorCount = count($errors);
        $message = "Sinkronisasi selesai. $insertedCount data baru, $updatedCount data diperbarui.";
        if ($errorCount > 0) {
            $message .= " Ada $errorCount data bentrok/error.";
        }

        return [
            'success' => true,
            'message' => $message,
            'inserted_count' => $insertedCount,
            'updated_count' => $updatedCount,
            'error_count' => $errorCount,
            'errors' => $errors,
        ];
    }
}
