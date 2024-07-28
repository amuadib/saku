<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Schedule::command('backup:clean')->daily()->at('11:00')
    ->onFailure(function () {
        Log::alert("Proses pembersihan Backup gagal dijalankan");
    })
    ->onSuccess(function () {
        Log::info("Proses pembersihan Backup berhasil dijalankan");
    });

Schedule::command('backup:run --only-db')->daily()->at('11:20')
    ->onFailure(function () {
        Log::alert("Proses Backup gagal dijalankan");
    })
    ->onSuccess(function () {
        Log::info("Proses Backup berhasil dijalankan");
    });
