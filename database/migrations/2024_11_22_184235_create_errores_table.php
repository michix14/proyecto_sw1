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
        Schema::create('errores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('ejercicio_id')
                ->constrained('ejercicios')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->text('error_tipo')->nullable(); // Descripción del error
            $table->json('detalles')->nullable(); // Detalles adicionales sobre el error (e.g., respuestas del usuario)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errores');
    }
};
