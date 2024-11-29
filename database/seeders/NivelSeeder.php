<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nivel;

class NivelSeeder extends Seeder
{
    public function run()
    {
        $niveles = [
            ['nombre' => 'A1 - Beginner', 'descripcion' => 'Basic level: Can understand and use familiar everyday expressions and very basic phrases.'],
            ['nombre' => 'A2 - Elementary', 'descripcion' => 'Basic level: Can communicate in simple and routine tasks requiring a simple exchange of information.'],
            ['nombre' => 'B1 - Intermediate', 'descripcion' => 'Intermediate level: Can deal with familiar situations likely to arise in daily life.'],
            ['nombre' => 'B2 - Upper Intermediate', 'descripcion' => 'Intermediate-advanced level: Can interact with a degree of fluency and spontaneity.'],
            ['nombre' => 'C1 - Advanced', 'descripcion' => 'Advanced level: Can use the language effectively and flexibly for social, academic, and professional purposes.'],
            ['nombre' => 'C2 - Proficient', 'descripcion' => 'Proficiency level: Can express themselves with fluency, precision, and spontaneity.'],
        ];

        foreach ($niveles as $nivel) {
            Nivel::create($nivel);
        }
    }
}
