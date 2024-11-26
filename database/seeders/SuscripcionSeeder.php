<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Suscripcion;


class SuscripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suscripcion1 = Suscripcion::create(['nombre' => 'Free','precio' => 0, 'caracteristica' => json_encode(['Acceso Basico'])]);
        $suscripcion2 = Suscripcion::create(['nombre' => 'Premium','precio' => 9.99, 'caracteristica' => json_encode(['Acceso Completo' ,'Funciones IA'])]);
    }
}