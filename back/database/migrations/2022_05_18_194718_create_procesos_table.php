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
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->string('marcado_planchas');
            $table->string('cortado_planchas');
            $table->string('plegado_planchas');
            $table->string('soldadura');
            $table->string('prueba_conductos');
            $table->string('armado_cuerpo');
            $table->string('pintado');
            $table->string('armado_accesorios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procesos');
    }
};
