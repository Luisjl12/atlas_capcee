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
        Schema::create('inmueble_energia', function (Blueprint $table) {
            $table->id();
            $table->string('cct')->index();
            $table->boolean('energia_red_contrato')->nullable();
            $table->boolean('energia_red_sin_contrato')->nullable();
            $table->boolean('energia_planta')->nullable();
            $table->boolean('energia_paneles_solares')->nullable();
            $table->boolean('sin_energia')->nullable();
            $table->boolean('gas_natural')->nullable();
            $table->boolean('gas_estacionario')->nullable();
            $table->boolean('gas_cilindro')->nullable();
            $table->boolean('sin_gas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmueble_energia');
    }
};
