<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\SuscripcionSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);// llama al seeder proveedor
        $this->call(UserSeeder::class);// llama al seeder usuario
        $this->call(SuscripcionSeeder::class);// llama al seeder suscripcion
        $this->call(NivelSeeder::class);// llama al seeder nivel
        $this->call(LeccionSeeder::class);// llama al seeder leccion

    }
}
