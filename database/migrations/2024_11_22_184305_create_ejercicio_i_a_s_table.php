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
        Schema::create('ejercicio_i_a_s', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->text('pregunta'); // Pregunta generada por la IA
                $table->text('respuesta_correcta')->nullable(); // Respuesta correcta esperada
                $table->enum('tipo', ['grammar', 'vocabulary', 'listening', 'speaking'])
                    ->default('grammar'); // Categoría del ejercicio
                $table->json('generado_desde')->nullable(); // Referencia al error o tema de origen
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ejercicio_i_a_s');
    }
};
