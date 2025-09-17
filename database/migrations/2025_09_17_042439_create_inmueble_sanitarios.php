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
        Schema::create('inmueble_sanitarios', function (Blueprint $table) {
            $table->id();
            $table->string('cct')->index();
            $table->unsignedBigInteger('banos_hombres')->default(0);
            $table->unsignedInteger('banos_mujeres')->default(0);
            $table->unsignedInteger('banos_mixtos')->default(0);
            $table->unsignedInteger('total_sanitarios')->default(0);
            $table->unsignedInteger('sanitarios_ambos')->default(0);
            $table->unsignedInteger('lavamanos')->default(0);
            $table->unsignedInteger('tomas_bebederos')->default(0);
            $table->unsignedInteger('banos_discapacitados')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmueble_sanitarios');
    }
};
