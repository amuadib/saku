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
        Schema::create('barang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode', 20)
                ->nullable();
            $table->string('jenis', 3)
                ->default('LLN');
            $table->string('nama');
            $table->string('foto')
                ->nullable();
            $table->text('keterangan')
                ->nullable();
            $table->decimal('harga', 10, 0)
                ->default(0);
            $table->integer('stok')
                ->default(0);
            $table->string('satuan', 3)
                ->default('PCS');
            $table->integer('stok_minimal')
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
