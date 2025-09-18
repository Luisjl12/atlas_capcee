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
        Schema::create('inmueble_seguridad', function (Blueprint $table) {
            $table->id();
            $table->string('cct')->index();
            $table->boolean('proteccion_civil')->nullable();
            $table->boolean('barda_completa')->nullable();
            $table->boolean('barda_incompleta')->nullable();
            $table->boolean('infraestructura_discapacidad')->nullable();
            $table->boolean('sin_infraestructura_discapacidad')->nullable();
            $table->boolean('equipo_discpacidad_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmueble_seguridad');
    }
};
