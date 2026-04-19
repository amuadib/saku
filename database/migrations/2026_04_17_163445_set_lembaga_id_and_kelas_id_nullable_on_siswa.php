<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->integer('lembaga_id')->nullable()->default(99)->change();
            $table->char('kelas_id', 36)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->integer('lembaga_id')->nullable(false)->default(99)->change();
            $table->char('kelas_id', 36)->nullable(false)->change();
        });
    }
};
