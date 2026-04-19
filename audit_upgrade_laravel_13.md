# Laporan Audit Upgrade Laravel 13

Laporan ini menganalisa kesiapan proyek **SAKU** untuk ditingkatkan ke **Laravel 13** (rilis Maret 2026).

## Ringkasan Eksekutif

Upgrade ke Laravel 13 dari Laravel 11.9 merupakan lompatan besar yang melibatkan dua versi mayor framework (L12 & L13). Titik resiko tertinggi bukan pada Laravel-nya sendiri, melainkan pada **Filament v3** yang harus ditingkatkan ke **Filament v5** agar kompatibel dengan ekosistem terbaru.

> [!CAUTION]
> **Upgrade ini TIDAK bisa dilakukan hanya dengan mengganti versi di `composer.json` saja.** 
> Ada banyak perubahan struktur kode (breaking changes) pada Filament yang akan menyebabkan error jika tidak diperbaiki secara manual.

---

## Detail Analisa Resiko

### 1. Kebutuhan PHP (Wajib ^8.3)
Laravel 13 mensyaratkan minimal **PHP 8.3**.
- **Status Sekarang:** `composer.json` mencatat `^8.2`, namun sistem saat ini sudah menjalankan PHP 8.3.0.
- **Tindakan:** Update constraint di `composer.json` menjadi `"php": "^8.3"`.

### 2. Laravel Framework (v11 → v13)
Lompatan ini melewati Laravel 12. Beberapa perubahan penting:
- **Carbon 3:** Laravel 12 & 13 mewajibkan Carbon v3. Jika ada manipulasi tanggal yang sangat kompleks, perlu dilakukan pengetesan ulang.
- **Dependency Updates:** PHPUnit harus ditingkatkan ke versi 11+.

### 3. Filament Framework (Resiko Tingkat Tinggi: v3 → v5)
Filament v5 adalah standar untuk Laravel 13. Lompatan dari v3 ke v5 membawa banyak perubahan API:
- **Table Builder:** Beberapa metode pewarnaan (`color()`) dan icon mungkin memiliki perubahan signature.
- **Form Builder:** Komponen seperti `FileUpload` dan `Select` dengan `options()` dinamis seringkali mengalami perubahan cara menangani *state*.
- **Bulk Actions:** Closure pada `BulkAction::make()` mungkin memerlukan update parameter.
- **Infolists:** Penanganan entry dan layouting yang lebih ketat.

### 4. Kompatibilitas Plugin (Wajib Diperiksa)
Proyek Anda menggunakan beberapa plugin Filament yang **WAJIB** dicek apakah sudah mendukung Filament v5 & Laravel 13:
- `ariaieboy/filament-currency`
- `leandrocfe/filament-apex-charts`
- `noxoua/filament-activity-log`
- `stechstudio/filament-impersonate`

### 5. Penggunaan `env()` di Luar Config
Ditemukan penggunaan fungsi `env()` langsung di dalam file `SiswaResource.php` dan `TagihanResource.php` (misalnya untuk pengecekan `WHATSAPP_NOTIFICATION`).
- **Resiko:** Pada Laravel 13, praktek ini semakin tidak disarankan karena akan mengembalikan `null` jika konfigurasi di-cache (`php artisan config:cache`).
- **Rekomendasi:** Pindahkan variabel tersebut ke file config (misalnya `config/custom.php`) dan gunakan `config('custom.whatsapp_notif')`.

---

## Prediksi Error yang Akan Muncul

Jika Anda memaksa `composer update` sekarang, kemungkinan besar akan muncul error berikut:
1. **Dependency Conflict:** Composer akan menolak instalasi karena plugin-plugin di atas belum diupdate versinya.
2. **Class Not Found / Method Not Found:** Saat membuka dashboard, Filament mungkin error karena mencari class atau method v3 yang sudah dihapus di v5.
3. **Internal Server Error (500):** Pada fitur cetak struk dan pengiriman WhatsApp karena adanya perubahan penanganan `Collection` atau `Carbon`.

---

## Langkah-Langkah Rekomendasi

1. **Backup:** Backup database dan source code (Git).
2. **Update PHP:** Pastikan environment produksi juga sudah PHP 8.3 (seperti local Anda).
3. **Pembersihan `env()`:** Pindahkan semua `env()` di dalam logic program ke file config.
4. **Upgrade Bertahap:** 
   - Upgrade ke Laravel 12 terlebih dahulu.
   - Upgrade Filament ke v4.
   - Baru kemudian ke Laravel 13 dan Filament v5.
5. **Gunakan Laravel Shift:** Sangat disarankan untuk menggunakan [Laravel Shift](https://laravelshift.com/) guna mengotomatisasi migrasi framework dan Filament.

---

## Kesimpulan
Proyek ini **BISA** diupgrade ke Laravel 13, namun membutuhkan waktu estimasi **2-3 hari kerja** untuk melakukan migrasi Filament secara tuntas dan pengetesan fitur (terutama fitur Tagihan dan Cetak Struk).
