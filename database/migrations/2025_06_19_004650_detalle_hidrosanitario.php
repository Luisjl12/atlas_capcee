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
        Schema::create('detalle_hidrosanitario', function (Blueprint $table) {
        $table->id();  
        $table->string('cct', 20); 
        $table->string ('fuente_agua', 100)->nullable();
        $table-> string ('almacenamiento_agua', 255)->nullable();
        $table-> string ('tipo_drenaje', 100)->nullable();
        $table-> integer('sanitarios_hombres_wc')->default(0);
        $table->integer ('sanitarios_hombres_lavabos')->default(0);
        $table->integer('sanitarios_mujeres_wc')->default(0);
        $table->integer('sanitarios_mujeres_lavabos')->default(0);
        $table ->text('observaciones')->nullable();
        $table->timestamp('fecha_actualizacion_seccion')->useCurrent()->useCurrentOnUpdate();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
