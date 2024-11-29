<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'opciones' => 'array',
    ];

    /**
     * Get the lección associated with the ejercicio.
     */
    public function leccion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }
}
