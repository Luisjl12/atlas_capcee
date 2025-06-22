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
        Schema::create('planteles', function (Blueprint $table) {
        $table->id();
        $table->string('cct', 20)->unique(); 
        $table->string('nombre_escuela', 255);
        $table->unsignedBigInteger('id_municipio')->nullable();
        $table->unsignedBigInteger('id_localidad')->nullable();
        $table->unsignedBigInteger('id_corde')->nullable();
        $table->unsignedBigInteger('id_director_asignado')->nullable();
        $table->string('nombre_director_registado', 255)->nullable();
        $table->string('turno', 50)->nullable();
        $table->string('nivel_educativo', 100)->nullable();
        $table->string('sostenimiento', 100)->nullable();
        $table->string('domicilio_calle_numero')->nullable();
        $table->string('domicilio_colonia')->nullable();
        $table->string('domicilio_cp', 10)->nullable();
        $table->string('telefono_plantel', 50)->nullable();
        $table->string('correo_institucional')->nullable();
        $table->boolean('accesibilidad_rampas')->default(false);
        $table->boolean('accesibilidad_banos_adaptados')->default(false);
        $table->boolean('accesibilidad_sanaletica_braille')->default(false);
        $table->text('accesibilidad_otros')->nullable();
        $table->integer('total_alumnos')->default(0);
        $table->integer('total_docentes')->default(0);
        $table->integer('total_administrativos')->default(0);
        $table->decimal('latitud', 10, 8)->nullable();
        $table->decimal('longitud', 11, 8)->nullable();
        $table->timestamp('fecha_registro')->useCurrent();
        $table->timestamp('fecha_ultima_actualizacion_general')->useCurrent()->useCurrentOnUpdate();
        $table->decimal('porcentaje_avance_captura', 5, 2)->default(0.00);
        $table->enum('estatus_plantel', ['ACTIVO', 'INACTIVO', 'EN_REVISION'])->default('EN_REVISION'); 
    });
    Schema::table('planteles', function (Blueprint $table) {
    $table->foreign('id_municipio')->references('id')->on('municipios')->nullOnDelete();
    $table->foreign('id_localidad')->references('id')->on ('localidades')->nullOnDelete();
    $table->foreign('id_corde')->references('id')->on('cordes')->nullOnDelete();
    $table->foreign('id_director_asignado')->references('id')->on('usuarios');
     });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planteles');
    }
};
