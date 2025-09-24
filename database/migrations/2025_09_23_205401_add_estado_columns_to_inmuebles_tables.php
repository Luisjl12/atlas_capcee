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

        Schema::table('inmueble_agua', function (Blueprint $table) {
            $table->string('estado_red_hidraulica')->nullable();
        });

        Schema::table('inmueble_sanitarios', function (Blueprint $table) {
            $table->string('estado_instalacion_sanitaria')->nullable();
        });

        Schema::table('inmueble_energia', function (Blueprint $table) {
            $table->string('estado_instalacion_electrica')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('inmueble_agua', function (Blueprint $table) {
            $table->dropColumn('estado_red_hidraulica');
        });

        Schema::table('inmueble_sanitarios', function (Blueprint $table) {
            $table->dropColumn('estado_instalacion_sanitaria');
        });

        Schema::table('inmueble_energia', function (Blueprint $table) {
            $table->dropColumn('estado_instalacion_electrica');
        });
    }
};
