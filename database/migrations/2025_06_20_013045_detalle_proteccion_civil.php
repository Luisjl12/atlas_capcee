<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_proteccion_civil', function (Blueprint $table) {
            $table->id('id_pc_detalle');
            $table->string('cct', 20);
            $table->boolean('programa_interno_pc')->default(false);
            $table->date('programa_interno_pc_fecha')->nullable();
            $table->enum('senaletica_estado', ['COMPLETA', 'PARCIAL', 'INEXISTENTE', 'NO_APLICA'])->default('INEXISTENTE');
            $table->integer('extintores_cantidad')->default(0);
            $table->integer('extintores_vigentes')->default(0);
            $table->date('extintores_ultima_recarga')->nullable();
            $table->boolean('botiquin_existencia')->default(false);
            $table->enum('botiquin_estado', ['COMPLETO', 'BASICO', 'INCOMPLETO', 'NO_APLICA'])->default('BASICO');
            $table->boolean('alarma_sismica')->default(false);
            $table->boolean('alarma_sismica_funcional')->default(false);
            $table->boolean('brigadas_conformadas')->default(false);
            $table->integer('simulacros_ultimo_anio')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_actualizacion_seccion')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('cct')->references('cct')->on('planteles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_proteccion_civil');
    }
};

