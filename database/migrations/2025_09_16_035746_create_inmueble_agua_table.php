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
        Schema::create('inmueble_agua', function (Blueprint $table) {
            $table->id();
            $table->string('cct')->index();
            $table->boolean('agua_red_publica')->nullable();
            $table->boolean('agua_pozo')->nullable();
            $table->boolean('agua_cuerpo')->nullable();
            $table->boolean('agua_pipas')->nullable();
            $table->boolean('agua_otro')->nullable();
            $table->boolean('cisterna')->nullable();
            $table->boolean('tinacos')->nullable();
            $table->boolean('tanque')->nullable();
            $table->boolean('almacenamiento_otro')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmueble_agua');
    }
};
