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
