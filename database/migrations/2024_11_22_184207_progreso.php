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
        Schema::create('progresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('leccion_id')
                ->constrained('lecciones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('completado')->default(false); // Si la lección fue completada
            $table->timestamp('completado_fecha')->nullable(); // Fecha de finalización
            $table->timestamps();
        }); //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progresos'); //
    }
};
