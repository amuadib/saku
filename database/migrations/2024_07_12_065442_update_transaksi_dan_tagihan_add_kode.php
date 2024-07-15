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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('kode', 20)
                ->nullable()
                ->after('id');
        });
        Schema::table('tagihan', function (Blueprint $table) {
            $table->string('kode', 20)
                ->nullable()
                ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
        Schema::table('tagihan', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
