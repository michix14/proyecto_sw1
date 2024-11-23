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
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leccion_id') // Relación con lecciones
                ->constrained('lecciones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            // Pregunta
            $table->text('pregunta_texto')->nullable(); // Texto de la pregunta
            $table->string('pregunta_audio', 2048)->nullable(); // URL o ruta al archivo de audio
            // Respuesta correcta
            $table->text('respuesta_texto')->nullable(); // Texto de la respuesta
            $table->string('respuesta_audio', 2048)->nullable(); // URL o ruta al archivo de audio
            // Dificultad
            $table->enum('dificultad', ['easy', 'medium', 'hard']);
            $table->timestamps();
        }); //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ejercicios'); //
    }
};
