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
        Schema::table('inmueble_seguridad', function (Blueprint $table) {
            $table->string('estado_barda')->nullable();
            $table->string('estado_cerco')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmueble_seguridad', function (Blueprint $table) {
            $table->dropColumn([
                'estado_barda',
                'estado_cerco',
            ]);
        });
    }
};
