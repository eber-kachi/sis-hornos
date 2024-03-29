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
        Schema::create('asignacion_lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_produccion_id')
            ->constrained('lotes_produccion')->onDelete('cascade');
            $table->foreignId('grupos_trabajo_id')
            ->constrained('grupos_trabajos');
            $table->integer('cantidad_asignada');

            $table->integer('porcentaje_avance');
            $table->foreignId('id_procesos')
                ->constrained('procesos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_lotes');
    }
};
