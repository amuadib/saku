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
        \DB::statement("UPDATE `transaksi`
                        SET `kode` = CONCAT(SUBSTR(`kode`,1,3),'1', SUBSTR(`kode`,4,12))
                        WHERE LENGTH(`kode`) = 15");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("UPDATE `transaksi`
                        SET `kode` = CONCAT(SUBSTR(`kode`,1,3), SUBSTR(`kode`,5,12))
                        WHERE LENGTH(`kode`) = 16");
    }
};
