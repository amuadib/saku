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
            $table->dropForeign(['kas_id']);
            $table->dropColumn(['kas_id', 'tagihan_id', 'tabungan_id']);
        });
        Schema::table('transaksi', function (Blueprint $table) {
            $table->uuidMorphs('transable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreign('kas_id')
                ->references('id')
                ->on('kas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->uuid('tagihan_id')
                ->nullable();
            $table->uuid('tabungan_id')
                ->nullable();
        });
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropMorphs('transable');
        });
    }
};
