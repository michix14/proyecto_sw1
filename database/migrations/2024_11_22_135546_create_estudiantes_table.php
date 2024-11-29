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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 70);
            $table->integer('telefono')->unsigned();
            $table->string('sexo', 1)->nullable();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('suscripcion_id') // Relación con suscripciones
            ->default(1) // Valor por defecto: "Free"
            ->constrained('suscripcions')
            ->nullOnDelete();
            $table->foreignId('nivel_actual_id')
            ->default(1) // Valor inicial por defecto: Nivel 1
            ->constrained('niveles') // Relación con la tabla levels
            ->cascadeOnUpdate()
            ->nullOnDelete();
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
