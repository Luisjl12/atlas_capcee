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
        Schema::create('archivos_plantel', function (Blueprint $table) {
        $table->id(); 
        $table->string('cct', 20); 
        $table->string('nombre_archivo_original', 255); 
        $table->string('nombre_archivo_sistema', 255);
        $table->string('ruta_archivo', 512);
        $table->string('tipo_documento', 100)->nullable();
        $table->text('descripcion')->nullable();
        $table->timestamp('fecha_subido');
        $table->string('mime_type', 100)->nullable();
        $table->unsignedBigInteger('tamano_byte')->nullable();
        $table->unsignedBigInteger('id_usuario_subio')->nullable();
        $table->timestamp('fecha_actualizacion_seccion')->useCurrent()->useCurrentOnUpdate();
    });
    Schema::table('archivos_plantel', function (Blueprint $table) {
    $table->foreign('id_usuario_subio')->references('id')->on('usuarios')->nullOnDelete();
     });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('archivos_plantel');
    }
};
