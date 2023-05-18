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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_pedido');
            $table->integer('total_precio');
            $table->string('estado');
            $table->integer('cantidad');
            $table->foreignId('producto_id')
                ->constrained('productos')->onDelete('cascade');
            $table->foreignId('lote_produccion_id')
                ->nullable()
            ->constrained('lotes_produccion');
            $table->foreignId('cliente_id')
            ->constrained('clientes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
