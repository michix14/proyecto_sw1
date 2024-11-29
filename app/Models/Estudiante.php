<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'sexo',
        'suscripcion_id',
        'nivel_actual_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }

    public function nivelActual()
    {
        return $this->belongsTo(nivel::class, 'nivel_actual_id');
    }
}
