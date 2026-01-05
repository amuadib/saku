<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * INDEX TABEL TAGIHAN
         */
        Schema::table('tagihan', function (Blueprint $table) {
            // Untuk join + filter bayar
            $table->index(
                ['siswa_id', 'kas_id', 'bayar'],
                'idx_tagihan_siswa_kas_bayar'
            );
        });

        /**
         * INDEX TABEL KAS
         */
        Schema::table('kas', function (Blueprint $table) {
            $table->index(
                ['lembaga_id'],
                'idx_kas_lembaga'
            );
        });

        /**
         * INDEX TABEL SISWA
         */
        Schema::table('siswa', function (Blueprint $table) {
            $table->index(
                ['id', 'nama'],
                'idx_siswa_id_nama'
            );
        });
    }

    public function down(): void
    {
        Schema::table('tagihan', function (Blueprint $table) {
            $table->dropIndex('idx_tagihan_siswa_kas_bayar');
        });

        Schema::table('kas', function (Blueprint $table) {
            $table->dropIndex('idx_kas_lembaga');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex('idx_siswa_id_nama');
        });
    }
};
