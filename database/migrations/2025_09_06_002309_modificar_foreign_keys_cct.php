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
        Schema::table('detalle_servicios', function (Blueprint $table) {
            $table->dropForeign(['cct']);

            $table->foreign('cct')
                ->references('cct')->on('planteles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_servicios', function (Blueprint $table) {
            $table->dropForeign(['cct']);

            $table->foreign('cct')
                ->references('cct')->on('planteles');
            // Sin cascada, como estaba antes
        });
    }
};
