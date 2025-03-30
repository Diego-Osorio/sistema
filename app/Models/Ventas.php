<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;

    protected $table = 'venta'; // Asegúrate de que el nombre de la tabla sea correcto

    protected $fillable = [
        'total',
        'cliente_id',
        'metodo_pago',
        'estado',
        'fecha_venta',
    ];

    // Relación con productos
    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'venta_producto')
            ->withPivot('cantidad', 'precio', 'total')
            ->withTimestamps();
    }
}