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
        Schema::create('siswa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('nik');
            $table->string('tempat_lahir')
                ->nullable();
            $table->date('tanggal_lahir')
                ->nullable();
            $table->enum('jenis_kelamin', ['l', 'p']);
            $table->text('alamat');
            $table->string('nama_ayah')
                ->nullable();
            $table->string('nama_ibu')
                ->nullable();
            $table->string('telepon')
                ->nullable();
            $table->string('email')
                ->nullable();
            $table->enum('yatim_piatu', ['y', 'n'])
                ->default('n');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
