<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Michel Cárdenas',
            'email' => 'michixcard@gmail.com',
            'password' => bcrypt('123456789'),
        ])->assignRole('estudiante');

        User::create([
            'name' => 'David Soliz',
            'email' => 'david@gmail.com',
            'password' => bcrypt('123456789'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Junior Zamo',
            'email' => 'junior@prueba.com',
            'password' => bcrypt('123456789'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Roy Martinez',
            'email' => 'ingmartinez@gmail.com',
            'password' => bcrypt('proyectofinal'),
        ])->assignRole('admin');
    }
}
