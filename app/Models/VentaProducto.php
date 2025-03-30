<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model
{
    use HasFactory;

    protected $table = 'venta_producto';  // Si tu tabla tiene un nombre diferente

    // Define las relaciones con los otros modelos
    public function venta()
    {
        return $this->belongsTo(Ventas::class);
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class);
    }
}
