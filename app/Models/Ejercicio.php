<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    use HasFactory;
    protected $fillable = [
        'leccion_id',
        'pregunta_texto',
        'pregunta_audio',
        'respuesta_texto',
        'respuesta_audio',
        'dificultad',
        'tipo',
        'opciones',
    ];

    protected $casts = [
        'opciones' => 'array', // Para manejar el JSON de forma sencilla
    ];

    public function leccion()
    {
        return $this->belongsTo(Leccion::class);
    }
}
