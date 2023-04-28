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
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('carnet_identidad');
            $table->date('fecha_nacimiento');
            $table->string('direccion');

            $table->date('fecha_registro')->nullable() ;
            $table->integer ('id_grupo_trabajo')->unsigned ()->nullable ();
            $table->foreign ('id_grupo_trabajo')->references ('id')->on ('grupo_trabajo')->onDelete ('set null');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }


};
