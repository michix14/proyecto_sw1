<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leccion extends Model
{
    protected $table = 'lecciones'; // Nombre de la tabla corregido
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'nivel_id',
    ];

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function ejercicio()
    {
        return $this->hasMany(Ejercicio::class);
    }

    public function progreso()
    {
        return $this->hasMany(Progreso::class);
    }
}
