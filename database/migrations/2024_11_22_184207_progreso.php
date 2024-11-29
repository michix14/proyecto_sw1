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
        Schema::create('progresos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->comment('Relación con la tabla de usuarios');
            $table->foreignId('leccion_id')
                ->constrained('lecciones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->comment('Relación con la tabla de lecciones');
            $table->boolean('completado')
                ->default(false)
                ->comment('Indica si la lección fue completada');
            $table->timestamp('completado_fecha')
                ->nullable()
                ->comment('Fecha en la que se completó la lección');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progresos');
    }
};
