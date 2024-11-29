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
        Schema::create('lecciones', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 255)->comment('Nombre de la lección');
            $table->text('descripcion')->nullable()->comment('Descripción de la lección');
            $table->foreignId('nivel_id')
                ->constrained('niveles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->comment('Relación con la tabla niveles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecciones');
    }
};
