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
            $table->foreignId('macroregion_id')
                ->nullable()
                ->constrained('macroregiones')
                ->onDelete('set null');

            $table->foreignId('microregion_id')
                ->nullable()
                ->constrained('microregiones')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planteles', function (Blueprint $table) {
            //
        });
    }
};
