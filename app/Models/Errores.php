<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Errores extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ejercicio_id',
        'error_tipo',
        'detalles',
    ];

    protected $casts = [
        'detalles' => 'array', // Para manejar el JSON de forma sencilla
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ejercicio()
    {
        return $this->belongsTo(Ejercicio::class);
    }
}
