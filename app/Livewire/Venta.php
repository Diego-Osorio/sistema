<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Clientes;
use App\Models\Productos;
use App\Models\Ventas;
use Illuminate\Support\Facades\DB;

class Venta extends Component
{
    public $producto_id, $producto, $cantidad, $precio, $total, $cliente_id, $metodo_pago, $clientes, $productos;
    public $productoSeleccionado = null;
    public $stockDisponible = 0;
    public $productosSeleccionados = [];
    public $totalVenta = 0;
    public $pago_efectivo = 0, $pago_tarjeta = 0, $restante = 0, $vuelto = 0;
public $mostrarMetodoPago = false;
    public function mount()
    {
        $this->producto_id = null;
        $this->clientes = Clientes::all();
        $this->productosSeleccionados = [];
        $this->productos = [];  // Inicializar productos
    }

    public function buscarProducto()
    {
        if (!empty($this->producto)) {
            $this->productos = Productos::where('nombre', 'like', '%' . $this->producto . '%')
                ->orWhere('codigo', 'like', '%' . $this->producto . '%')
                ->get();
        } else {
            $this->productos = [];
        }
    }

    public function seleccionarProducto($id)
    {
        $producto = Productos::find($id);
    
        if ($producto) {
            // Verificar si el producto ya está en el carrito
            foreach ($this->productosSeleccionados as $item) {
                if ($item['producto_id'] == $producto->id) {
                    session()->flash('error', 'El producto ya está en el carrito.');
                    return;
                }
            }
    
            // Agregar el producto al carrito con cantidad inicial de 1
            $this->productosSeleccionados[] = [
                'producto_id' => $producto->id,
                'producto' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => 1,
                'total' => $producto->precio,
            ];
    
            // Actualizar el total de la venta
            $this->totalVenta = array_sum(array_column($this->productosSeleccionados, 'total'));
    
            // Limpiar la lista de búsqueda
            $this->productos = [];
            $this->producto = null;
    
            session()->flash('message', 'Producto agregado al carrito.');
        } else {
            session()->flash('error', 'Producto no encontrado.');
        }
    }
    public function mostrarMetodoPago()
    {
        $this->mostrarMetodoPago = true;
    }
    public function agregarProducto()
    {
        $this->validate([
            'producto_id' => 'required|exists:producto,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Productos::find($this->producto_id);

        if ($producto) {
            if ($producto->stock < $this->cantidad) {
                session()->flash('error', "No hay suficiente stock para el producto {$producto->nombre}.");
                return;
            }

            $total = $producto->precio * $this->cantidad;
            $this->productosSeleccionados[] = [
                'producto_id' => $producto->id,
                'producto' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $this->cantidad,
                'total' => $total,
            ];

            // Actualizar total de la venta
            $this->totalVenta = array_sum(array_column($this->productosSeleccionados, 'total'));
            $this->calcularRestante();
        }

        $this->reset(['producto_id', 'producto', 'cantidad']);
    }

    public function agregarProductoLista()
    {
        $this->agregarProducto();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['pago_efectivo', 'pago_tarjeta'])) {
            $this->calcularRestante();
        }
    }

    public function calcularRestante()
    {
        $totalPagado = $this->pago_efectivo + $this->pago_tarjeta;

        if ($totalPagado >= $this->totalVenta) {
            $this->vuelto = $totalPagado - $this->totalVenta;
            $this->restante = 0;
        } else {
            $this->vuelto = 0;
            $this->restante = $this->totalVenta - $totalPagado;
        }
    }
    public function actualizarCantidad($index)
{
    $producto = &$this->productosSeleccionados[$index];

    if ($producto['cantidad'] < 1) {
        $producto['cantidad'] = 1; // Evitar cantidades menores a 1
    }

    $productoModel = Productos::find($producto['producto_id']);
    if ($producto['cantidad'] > $productoModel->stock) {
        $producto['cantidad'] = $productoModel->stock; // Limitar la cantidad al stock disponible
        session()->flash('error', 'Cantidad ajustada al stock disponible.');
    }

    // Recalcular el total del producto
    $producto['total'] = $producto['cantidad'] * $producto['precio'];

    // Recalcular el total de la venta
    $this->totalVenta = array_sum(array_column($this->productosSeleccionados, 'total'));
}

    public function submit()
    {
        $this->validate([
            "cliente_id" => "required|exists:cliente,id",
            "pago_efectivo" => "required|numeric|min:0",
            "pago_tarjeta" => "required|numeric|min:0",
        ]);

        $totalPagado = $this->pago_efectivo + $this->pago_tarjeta;
        if ($totalPagado < $this->totalVenta) {
            session()->flash('error', 'El monto pagado no es suficiente para cubrir el total.');
            return;
        }

        DB::beginTransaction();

        try {
            $venta = Ventas::create([
                "total" => $this->totalVenta,
                "cliente_id" => $this->cliente_id,
                "metodo_pago" => $this->pago_tarjeta > 0 ? 'dividido' : 'efectivo',
                "estado" => "pendiente",
                "fecha_venta" => now(),
            ]);

            foreach ($this->productosSeleccionados as $producto) {
                $productoActual = Productos::find($producto['producto_id']);
                if ($productoActual) {
                    if ($productoActual->stock >= $producto['cantidad']) {
                        $productoActual->stock -= $producto['cantidad'];
                        $productoActual->save();

                        $venta->productos()->create([
                            'producto_id' => $producto['producto_id'],
                            'cantidad' => $producto['cantidad'],
                            'precio' => $producto['precio'],
                            'total' => $producto['total'],
                        ]);
                    } else {
                        throw new \Exception("El producto {$productoActual->nombre} no tiene suficiente stock.");
                    }
                } else {
                    throw new \Exception("El producto con ID {$producto['producto_id']} no existe.");
                }
            }

            $venta->estado = 'completado';
            $venta->save();

            DB::commit();

            $this->reset(['producto', 'cantidad', 'precio', 'cliente_id', 'productosSeleccionados', 'totalVenta', 'pago_efectivo', 'pago_tarjeta', 'vuelto']);

            session()->flash('message', 'Venta registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.venta');
    }
}