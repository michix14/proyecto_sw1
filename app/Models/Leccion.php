<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leccion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lecciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'nivel_id',
    ];

    /**
     * Get the nivel associated with the lección.
     */
    public function nivel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }

    /**
     * Get the ejercicios associated with the lección.
     */
    public function ejercicio(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ejercicio::class);
    }

    /**
     * Get the progresos associated with the lección.
     */
    public function progreso(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Progreso::class);
    }
}

