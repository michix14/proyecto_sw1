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
        Schema::create('estudiantes', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 70)->comment('Nombre completo del estudiante');
            $table->unsignedInteger('telefono')->comment('Número de teléfono del estudiante');
            $table->string('sexo', 1)->nullable()->comment('Sexo del estudiante: M (Masculino), F (Femenino)');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->comment('Relación con la tabla de usuarios');
            $table->foreignId('suscripcion_id')
                ->default(1)
                ->constrained('suscripcions')
                ->nullOnDelete()
                ->comment('Relación con la tabla suscripcions. Por defecto: Free');
            $table->foreignId('nivel_actual_id')
                ->default(1)
                ->constrained('niveles')
                ->cascadeOnUpdate()
                ->nullOnDelete()
                ->comment('Relación con la tabla niveles. Nivel inicial por defecto: Nivel 1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
