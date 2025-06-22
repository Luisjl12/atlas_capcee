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
        Schema::create('usuarios', function (Blueprint $table) {
        $table->id(); 
        $table->string('nombre_completo', 255); 
        $table->string('correo_electronico', 255)->unique();
        $table->string('password_hash', 255);
        $table->string('telefono_contacto', 255);
        $table->enum('estado', ['activo', 'inactivo'])->default('activo'); 
        $table->unsignedBigInteger('role_id');
        $table->timestamps(); 
      });
       Schema::table('usuarios', function (Blueprint $table) {
        $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            // Primero quitamos la llave foránea, luego borramos la tabla
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::dropIfExists('usuarios');
    }
};
