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
            $table->integer('largo');

            $table->integer('ancho');
            $table->integer('cm');

            $table->integer('cm2');
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
