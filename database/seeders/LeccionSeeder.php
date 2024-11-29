<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leccion;

class LeccionSeeder extends Seeder
{
    public function run()
    {
        $lecciones = [
            // Lecciones para A1
            ['nombre' => 'Introducción al inglés', 'descripcion' => 'Saludos, presentaciones y vocabulario básico.', 'nivel_id' => 1],
            ['nombre' => 'El alfabeto', 'descripcion' => 'Aprender a pronunciar y escribir el alfabeto inglés.', 'nivel_id' => 1],
            ['nombre' => 'Números y colores', 'descripcion' => 'Números del 1 al 100 y colores comunes.', 'nivel_id' => 1],

            // Lecciones para A2
            ['nombre' => 'Frases comunes', 'descripcion' => 'Frases de uso cotidiano en inglés.', 'nivel_id' => 2],
            ['nombre' => 'Direcciones y ubicaciones', 'descripcion' => 'Cómo preguntar y dar indicaciones.', 'nivel_id' => 2],
            ['nombre' => 'Tiempos verbales básicos', 'descripcion' => 'Presente simple y continuo.', 'nivel_id' => 2],

            // Lecciones para B1
            ['nombre' => 'Conversaciones simples', 'descripcion' => 'Cómo entablar una conversación básica.', 'nivel_id' => 3],
            ['nombre' => 'Rutinas diarias', 'descripcion' => 'Hablar sobre actividades y horarios.', 'nivel_id' => 3],
            ['nombre' => 'Pasado simple', 'descripcion' => 'Uso del pasado para describir eventos.', 'nivel_id' => 3],

            // Lecciones para B2
            ['nombre' => 'Opiniones y argumentos', 'descripcion' => 'Cómo expresar opiniones claras.', 'nivel_id' => 4],
            ['nombre' => 'Comprensión de textos', 'descripcion' => 'Leer y entender textos más complejos.', 'nivel_id' => 4],
            ['nombre' => 'Condicionales', 'descripcion' => 'Uso de estructuras condicionales.', 'nivel_id' => 4],

            // Lecciones para C1
            ['nombre' => 'Discursos y presentaciones', 'descripcion' => 'Hablar en público de manera efectiva.', 'nivel_id' => 5],
            ['nombre' => 'Escritura avanzada', 'descripcion' => 'Redacción de ensayos y artículos.', 'nivel_id' => 5],

            // Lecciones para C2
            ['nombre' => 'Perfeccionamiento del idioma', 'descripcion' => 'Dominio total del inglés.', 'nivel_id' => 6],
            ['nombre' => 'Inglés profesional', 'descripcion' => 'Aplicaciones del inglés en el trabajo.', 'nivel_id' => 6],
        ];

        foreach ($lecciones as $leccion) {
            Leccion::create($leccion);
        }
    }
}
