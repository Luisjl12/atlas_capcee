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
        Schema::create('inmueble_drenaje', function (Blueprint $table) {
            $table->id();
            $table->string('cct')->index();
            $table->boolean('drenaje_publico')->nullable();
            $table->boolean('fosa_septica')->nullable();
            $table->boolean('planta_tratamiento')->nullable();
            $table->boolean('descarga_otro')->nullable();
            $table->boolean('separacion_aguas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmueble_drenaje');
    }
};
