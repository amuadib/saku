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
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('penjualan_id');
            $table->foreign('penjualan_id')
                ->references('id')
                ->on('penjualan')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->uuid('barang_id');
            $table->foreign('barang_id')
                ->references('id')
                ->on('barang')
                ->onUpdate('cascade');
            $table->integer('jumlah');
            $table->decimal('harga', 10, 0);
            $table->decimal('total', 10, 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
