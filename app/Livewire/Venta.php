<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Clientes;
use App\Models\Productos;
use App\Models\Ventas;

class Venta extends Component
{
    public $producto_id,$nombre, $codigo,$stock,$categoria_id,$producto, $cantidad, $precio, $total, $cliente_id, $metodo_pago, $clientes, $productos;
    public $productoSeleccionado = null;
    public $stockDisponible = 0;
    public $productosSeleccionados = [];
    public $totalVenta = 0;
    public $pago_efectivo = 0, $pago_tarjeta = 0, $restante = 0;

    public function mount()
    {
        $this->producto_id = null;
        $this->clientes = Clientes::all();
        $this->productosSeleccionados = [];
    }

    // Función para buscar productos
    public function buscarProducto()
    {
        $this->productos = Productos::where('nombre', 'like', '%' . $this->producto . '%')->get();
    }

    // Función para seleccionar un producto
    public function seleccionarProducto($id)
    {
        $producto = Productos::find($id);
        $this->producto_id = $producto->id;  // Asignar el producto_id correctamente
        $this->producto = $producto->nombre;
        $this->precio = $producto->precio;
        $this->stockDisponible = $producto->stock;
        $this->cantidad = 1;
        $this->productoSeleccionado = $producto;
        $this->productos = [];
    }
    

    public function agregarProducto()
    {
        $this->validate([
            'producto_id' => 'required|exists:producto,id',  // Validación de producto
            'cantidad' => 'required|integer|min:1',  // Validación de cantidad
        ]);
    
        $producto = Productos::find($this->producto_id);
        
        if ($producto) {
            $total = $producto->precio * $this->cantidad;
            $this->productosSeleccionados[] = [
                'producto_id' => $producto->id,
                'producto' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $this->cantidad,
                'total' => $total,
            ];
    
            // Actualizar total de la venta
            $this->totalVenta += $total;
            $this->calcularRestante();  // Actualizar el pago restante
        }
    
        $this->reset(['producto_id', 'cantidad']);  // Limpiar los campos
    }
    
    
    
    

    // Función para eliminar un producto del carrito
    public function eliminarProducto($index)
    {
        unset($this->productosSeleccionados[$index]);
        $this->productosSeleccionados = array_values($this->productosSeleccionados);
        $this->totalVenta = array_sum(array_column($this->productosSeleccionados, 'total'));
    }

    // Función para actualizar el pago restante
    public function calcularRestante()
    {
        $this->restante = $this->totalVenta - ($this->pago_efectivo + $this->pago_tarjeta);
    }

    // Función para registrar la venta
    public function submit()
    {
        // Validación
        $this->validate([
            "cliente_id" => "required|exists:cliente,id",  // Validación de cliente
            "pago_efectivo" => "required|numeric|min:0",    // Validación de efectivo
            "pago_tarjeta" => "required|numeric|min:0",     // Validación de tarjeta
        ]);
    
        // Crear la venta
        $venta = Ventas::create([
            "total" => $this->totalVenta,
            "cliente_id" => $this->cliente_id,
            "metodo_pago" => 'dividido',  // Especificamos que es un pago dividido
            "estado" => "pendiente",
            "fecha_venta" => now(),
        ]);
    
        // Crear los registros de venta por cada producto seleccionado
        foreach ($this->productosSeleccionados as $producto) {
            // Crear el registro de venta del producto
            $venta->productos()->create([
                'producto_id' => $producto['producto_id'],  // Asegúrate de que este campo sea correcto
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio'],
                'total' => $producto['total'],
            ]);
            
    
            // Actualizar el stock del producto
            $productoActual = Productos::find($producto['producto_id']);  // Encuentra el producto
            if ($productoActual) {
                $productoActual->stock -= $producto['cantidad'];  // Restar la cantidad vendida
                $productoActual->save();  // Guardar el producto con el stock actualizado
            }
        }
    
        // Limpiar campos
        $this->reset(['producto', 'cantidad', 'precio', 'cliente_id', 'productosSeleccionados', 'totalVenta', 'pago_efectivo', 'pago_tarjeta']);
    
        // Mensaje de éxito
        session()->flash('message', 'Venta registrada correctamente.');
    }
    
    
    public function agregarProductoLista (){
        $this->agregarProducto(); 
    }
    public function render()
    {
        return view('livewire.venta');
    }
}





