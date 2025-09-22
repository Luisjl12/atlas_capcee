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
        Schema::table('inmueble_sanitarios', function (Blueprint $table) {
            $table->foreign('cct')
                ->references('cct')
                ->on('planteles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmueble_sanitarios', function (Blueprint $table) {
            $table->dropForeign(['cct']);
        });
    }
};
