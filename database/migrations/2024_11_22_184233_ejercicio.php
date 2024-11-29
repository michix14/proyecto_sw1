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
        Schema::create('ejercicios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('leccion_id')
                ->constrained('lecciones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->comment('Relación con la tabla de lecciones');

            // Pregunta
            $table->text('pregunta_texto')
                ->nullable()
                ->comment('Texto de la pregunta');
            $table->string('pregunta_audio', 2048)
                ->nullable()
                ->comment('URL o ruta al archivo de audio de la pregunta');

            // Respuesta correcta
            $table->text('respuesta_texto')
                ->nullable()
                ->comment('Texto de la respuesta correcta');
            $table->string('respuesta_audio', 2048)
                ->nullable()
                ->comment('URL o ruta al archivo de audio de la respuesta correcta');

            // Dificultad y tipo
            $table->enum('dificultad', ['easy', 'medium', 'hard'])
                ->comment('Nivel de dificultad del ejercicio');
            $table->enum('tipo', ['1', '2', '3', '4', '5', '6'])
                ->default('1')
                ->comment('Tipo de ejercicio');

            // Opciones para preguntas multi-opciones
            $table->json('opciones')
                ->nullable()
                ->comment('Opciones para las preguntas multi-opciones');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ejercicios');
    }
};
