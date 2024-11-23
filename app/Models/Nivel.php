<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'niveles';
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function leccion()
    {
        return $this->hasMany(Leccion::class);
    }
}
