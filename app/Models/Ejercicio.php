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
        'respuesta_texto',
        'dificultad',
    ];

    public function leccion()
    {
        return $this->belongsTo(Leccion::class);
    }
}
