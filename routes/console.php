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

Schedule::command('backup:run --only-db')->twiceDailyAt(11, 15, 7)
    ->onFailure(function () {
        Log::alert("Proses Backup gagal dijalankan");
    })
    ->onSuccess(function () {
        Log::info("Proses Backup berhasil dijalankan");
    });

Schedule::command('backup:run')->weeklyOn(1, '2:22')
    ->onFailure(function () {
        Log::alert("Proses Backup seluruh data gagal dijalankan");
    })
    ->onSuccess(function () {
        Log::info("Proses Backup seluruh data berhasil dijalankan");
    });
