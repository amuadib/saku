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
        Schema::create('tabungan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('siswa_id');
            $table->foreign('siswa_id')
                ->references('id')
                ->on('siswa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->uuid('kas_id');
            $table->foreign('kas_id')
                ->references('id')
                ->on('kas');
            $table->decimal('saldo', 20, 0)
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabungan');
    }
};
