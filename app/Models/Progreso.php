<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progreso extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'leccion_id',
        'completado',
        'completado_fecha',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leccion()
    {
        return $this->belongsTo(Leccion::class);
    }
}
