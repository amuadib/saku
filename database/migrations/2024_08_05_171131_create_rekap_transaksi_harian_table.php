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
        Schema::create('rekap_transaksi_harian', function (Blueprint $table) {
            $table->uuid('id');
            $table->date('tanggal');
            $table->uuid('kas_id');
            $table->foreign('kas_id')
                ->references('id')
                ->on('kas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->decimal('masuk', 20, 0)
                ->default(0);
            $table->decimal('keluar', 20, 0)
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_transaksi_harian');
    }
};
