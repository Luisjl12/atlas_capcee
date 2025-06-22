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
        Schema::create('localidades', function (Blueprint $table) {
        $table->id();  
        $table->unsignedBigInteger('municipio_id');
        $table->string('nombre_localidad', 255); 
        $table->timestamps();
    });
    Schema::table('localidades', function (Blueprint $table) {
        $table->foreign('municipio_id')->references('id')->on('municipios')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            // Primero quitamos la llave foránea, luego borramos la tabla
        Schema::table('localidades', function (Blueprint $table) {
            $table->dropForeign(['municipio_id']);
        });

        Schema::dropIfExists('localidades');
    }
};
