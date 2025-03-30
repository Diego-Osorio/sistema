<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;
    protected $table = 'producto';
    protected $fillable = [
        'nombre',
        'codigo',
        'precio',
        'categoria_id',
        'stock',
        'stock_minimo',
    ];
    

    /**
     * Relación con Categoría.
     */
    public function categoria()
    {
        return $this->belongsTo(Categorias::class);
    }

    /**
     * Verifica si el stock está por debajo del mínimo.
     */
    public function estaEnStockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
    public function ventas()
    {
        return $this->belongsToMany(Ventas::class, 'venta_producto')
            ->withPivot('cantidad', 'precio', 'total')
            ->withTimestamps();
    }
}
