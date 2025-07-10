<?php

use App\Models\Kelas;
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
        Schema::table('kelas', function (Blueprint $table) {
            $table->integer('tingkat')
                ->nullable();
        });
        \DB::statement("UPDATE `kelas` SET `tingkat` = SUBSTR(`nama`,1,1) WHERE SUBSTR(`nama`,1,1)<>'L';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('tingkat');
        });
    }
};
