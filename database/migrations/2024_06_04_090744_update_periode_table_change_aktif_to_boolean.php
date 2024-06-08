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
        Schema::table('periode', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });
        Schema::table('periode', function (Blueprint $table) {
            $table->boolean('aktif')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });
        Schema::table('periode', function (Blueprint $table) {
            $table->enum('aktif', ['y', 'n']);
        });
    }
};
