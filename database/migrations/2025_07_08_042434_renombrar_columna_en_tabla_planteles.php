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
        Schema::table('planteles', function (Blueprint $table) {
            $table->renameColumn('nombre_director_registado', 'nombre_director_registrado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planteles', function (Blueprint $table) {
            $table->renameColumn('nombre_director_registrado', 'nombre_director_registado');
        });
    }
};
