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
        Schema::create('cordes', function (Blueprint $table) {
        $table->id(); 
        $table->string('clave_corde', 20);  
        $table->string('nombre_corde',255);  
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
