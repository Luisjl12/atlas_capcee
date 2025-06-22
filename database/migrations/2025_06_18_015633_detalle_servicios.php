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
        Schema::create('detalle_servicios', function (Blueprint $table) {
        $table->id(); 
        $table->string('cct', 20); 
        $table-> boolean('electricidad_contrato')->default(0);
        $table-> boolean('telefonia_fija')->default(0);
        $table-> boolean('internet_acceso')->default(0);
        $table -> string('gas_tipo', 100)->nullable();
        $table -> string ('internet_tipo', 100)-> nullable();
        $table -> text('observaciones')->nullable();
        $table->timestamp('fecha_actualizacion_seccion')->useCurrent()->useCurrentOnUpdate();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_servicios');
    }
};
 