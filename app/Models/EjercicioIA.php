<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EjercicioIA extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pregunta',
        'respuesta_correcta',
        'tipo',
        'generado_desde',
    ];

    protected $casts = [
        'generado_desde' => 'array', // Para manejar el JSON de forma sencilla
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
