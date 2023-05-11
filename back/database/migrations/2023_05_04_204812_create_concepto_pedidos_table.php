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
        Schema::create('concepto_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')
            ->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('producto_id')
            ->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->integer('precio');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepto_pedidos');
    }
};
