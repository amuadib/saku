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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->uuid('id')
                ->primary();
            $table->string('kode', 20)
                ->nullable();
            $table->uuid('supplier_id');
            $table->foreign('supplier_id')
                ->references('id')
                ->on('supplier')
                ->onUpdate('cascade');
            $table->decimal('total', 10, 0);
            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
