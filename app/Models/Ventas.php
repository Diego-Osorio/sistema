<?php

namespace App\Models;

use App\Models\Clientes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Ventas extends Model
{
    use HasFactory;
    // Especificamos el nombre de la tabla (singular)
    protected $table = 'venta';

    // Especificamos los campos que pueden ser asignados de forma masiva
    protected $fillable = ['producto', 'cantidad', 'precio', 'total', 'cliente_id', 'metodo_pago', 'estado', 'fecha_venta'];
// app/Models/Venta.php
public function cliente()
{
    return $this->belongsTo(Clientes::class);
}
public function productos()
{
    return $this->belongsToMany(Productos::class)->withPivot('cantidad', 'precio', 'total');
}



}