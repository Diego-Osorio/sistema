<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;
    protected $table = 'categoria';
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación con productos (Una categoría tiene muchos productos).
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
