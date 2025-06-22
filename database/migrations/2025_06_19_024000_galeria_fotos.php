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
        Schema::create('galeria_fotos', function (Blueprint $table) {
        $table->id(); 
        $table->string('cct', 20);  
        $table->unsignedBigInteger('id_espacio')->nullable()->comment('FK a espacios_areas (opcional)');
        $table->string('nombre_foto_original',255)->nullable();
        $table->string('nombre_foto_sistema', 255);
        $table->string('ruta_foto', 512);
        $table->text('descripcion_foto')->nullable();
        $table->timestamp('fecha_subida')->useCurrent();
        $table->unsignedBigInteger('id_usuario_subio')->nullable();
        $table->timestamp('fecha_actualizacion_seccion')->useCurrent()->useCurrentOnUpdate();
 
    });

    Schema::table('galeria_fotos', function (Blueprint $table) {
    $table->foreign('id_espacio')->references('id')->on('espacios_areas')->nullOnDelete();
    $table->foreign('id_usuario_subio')->references('id')->on('usuarios')->nullOnDelete();
     });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeria_fotos');
    }
};
