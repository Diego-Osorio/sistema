<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model
{
    use HasFactory;

    protected $table = 'venta_producto';

    // Agrega las columnas permitidas para asignación masiva
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio',
        'total'
    ];

    public function venta()
    {
        return $this->belongsTo(Ventas::class);
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class);
    }
}
