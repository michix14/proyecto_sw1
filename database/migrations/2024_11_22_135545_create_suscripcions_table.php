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
        Schema::create('suscripcions', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 50)->comment('Nombre del plan (e.g., Free, Premium)');
            $table->decimal('precio', 8, 2)->default(0)->comment('Precio del plan');
            $table->text('caracteristica')->nullable()->comment('Lista de características');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripcions');
    }
};
