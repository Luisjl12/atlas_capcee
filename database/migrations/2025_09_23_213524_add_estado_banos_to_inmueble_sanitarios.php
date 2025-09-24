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
        Schema::table('inmueble_sanitarios', function (Blueprint $table) {
            $table->string('estado_banos')->nullable();
            $table->string('estado_minigitorios')->nullable();
            $table->string('estado_lavamanos')->nullable();
            $table->string('estado_bebederos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmueble_sanitarios', function (Blueprint $table) {
            $table->dropColumn([
                'estado_banos',
                'estado_minigitorios',
                'estado_lavamanos',
                'estado_bebederos',
            ]);
        });
    }
};
