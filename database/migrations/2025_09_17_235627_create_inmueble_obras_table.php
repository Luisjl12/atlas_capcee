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
        Schema::create('inmueble_obras', function (Blueprint $table) {
            $table->id();
            $table->string('cct')->index();
            $table->boolean('rehabilitacion_realizada')->nullable();
            $table->boolean('rehabilitacion_impermeabilizacion')->nullable();
            $table->boolean('rehabilitacion_albanileria')->nullable();
            $table->boolean('rehabilitacion_pintura')->nullable();
            $table->boolean('rehabilitacion_red_hidraulica')->nullable();
            $table->boolean('rehabilitacion_red_sanitaria')->nullable();
            $table->boolean('rehabilitacion_esctructural')->nullable();
            $table->boolean('obras_nuevas')->nullable();
            $table->boolean('construccion_educativa')->nullable();
            $table->boolean('construccion_deportiva')->nullable();
            $table->boolean('construccion_sanitaria')->nullable();
            $table->boolean('construccion_complementos')->nullable();
            $table->boolean('construccion_total')->nullable();
            $table->boolean('construccion_otro')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmueble_obras');
    }
};
