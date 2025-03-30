<?php
// app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla (por si la tabla no sigue la convención)
    protected $table = 'cliente';

    // Especificar qué columnas se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
    ];
}
