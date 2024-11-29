<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'telefono',
        'sexo',
        'suscripcion_id',
        'user_id',
        'nivel_actual_id',
    ];

    /**
     * Get the user associated with the estudiante.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the suscripción associated with the estudiante.
     */
    public function suscripcion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Suscripcion::class);
    }

    /**
     * Get the current level associated with the estudiante.
     */
    public function nivelActual(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'nivel_actual_id');
    }
}

