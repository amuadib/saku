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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kas_id');
            $table->foreign('kas_id')
                ->references('id')
                ->on('kas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->uuid('tagihan_id')
                ->nullable();
            $table->uuid('tabungan_id')
                ->nullable();

            $table->enum('mutasi', ['m', 'k']);
            $table->decimal('jumlah', 20, 0);
            $table->text('keterangan')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
