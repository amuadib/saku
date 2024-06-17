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
            $table->dropColumn('yatim_piatu');
        });
        Schema::table('siswa', function (Blueprint $table) {
            $table->json('label')
                ->after('foto')
                ->default('[]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->enum('yatim_piatu', ['y', 'n'])
                ->after('foto')
                ->default('n');
        });
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }
};
