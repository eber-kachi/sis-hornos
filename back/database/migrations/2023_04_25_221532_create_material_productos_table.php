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
        Schema::create('material_productos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('descripcion');
            $table->float('kg')->nullable();
            $table->float('largo_cm')->nullable();
            $table->float('ancho_cm')->nullable();
            $table->float('cm2')->nullable()->change();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('material_id')->constrained('materials');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_productos');
    }
};
