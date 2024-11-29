<?php

declare(strict_types=1);

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
        Schema::create('ejercicio_i_a_s', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->comment('Relación con la tabla de usuarios');

            $table->text('pregunta')
                ->comment('Pregunta generada por la IA');

            $table->text('respuesta_correcta')
                ->nullable()
                ->comment('Respuesta correcta esperada');

            $table->enum('tipo', ['grammar', 'vocabulary', 'listening', 'speaking'])
                ->default('grammar')
                ->comment('Categoría del ejercicio generado por la IA');

            $table->json('generado_desde')
                ->nullable()
                ->comment('Referencia al error o tema de origen del ejercicio');

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
