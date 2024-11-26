<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscripcion extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'precio',
        'caracteristica',
    ];

    protected $casts = [
        'caracteristica' => 'array', // Para manejar el JSON de forma sencilla
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
