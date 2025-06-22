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
        Schema::create('espacios_areas', function (Blueprint $table) {
        $table->id();  
        $table->string('cct', 20); 
        $table->string('nombre_espacio', 255);
        $table -> integer ('cantidad')->default (1);
        $table -> enum ('estado_conservacion', ['BUENO', 'REGULAR', 'MALO', 'NO_APLICA', 'EN_PROCESO'])->default('REGULAR');
        $table->timestamp('fecha_actualizacion_seccion')->useCurrent()->useCurrentOnUpdate();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('espacios_areas');
    }
};
