<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home-tagihan');
});

Route::get(
    '/cetak/{view}/{transaksi_id}/{mode?}',
    [
        \App\Http\Controllers\CetakStrukController::class,
        'cetak'
    ]
);

Route::post('/admin/login', function () {
    return back()->with('error', 'Gagal memproses login. Pastikan Javascript aktif di browser Anda atau tunggu halaman selesai dimuat lalu coba lagi.');
})->name('filament.admin.auth.login.post');
