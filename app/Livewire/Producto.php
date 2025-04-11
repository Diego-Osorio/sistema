<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Productos;  // Importar el modelo correcto
use App\Models\Categorias;

class Producto extends Component
{
    public $nombre, $codigo, $precio, $stock, $categoria_id, $producto_id;
    public $productos;

    public function mount()
    {
        // Cargar productos al inicializar el componente
        $this->productos = Productos::all();  // Usar Productos correctamente
    }

    public function searchProducto()
    {
        // Corregir a Productos en lugar de Producto
        $productos = Productos::where('nombre', 'like', "%$this->nombre%")->get();
        // Aquí puedes asignar los resultados a $this->productos o hacer lo que sea necesario
        $this->productos = $productos;
    }

  
     public function agregarProducto()
    {
    $this->validate([
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'categoria_id' => 'required|exists:categorias,id',
    ]);

    if ($this->producto_id) {
        // Editar producto
        $producto = Productos::find($this->producto_id);
        $producto->update([
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'precio' => $this->precio,
            'stock' => $this->stock, // Actualizar el stock
            'categoria_id' => $this->categoria_id,
        ]);
        session()->flash('success', 'Producto actualizado correctamente.');
    } else {
        // Crear producto
        Productos::create([
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'precio' => $this->precio,
            'stock' => $this->stock, // Establecer el stock inicial
            'categoria_id' => $this->categoria_id,
        ]);
        session()->flash('success', 'Producto registrado correctamente.');
    }

    // Resetear los campos del formulario
    $this->reset(['nombre', 'codigo', 'precio', 'stock', 'categoria_id', 'producto_id']);

    // Emitir evento para cerrar el modal (si es necesario)
    $this->emit('cerrarModal');
}
    
    
    
    

    // Función para editar un producto
    public function edit($id)
    {
        $producto = Productos::find($id);  // Usar Productos correctamente
        $this->producto_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->codigo = $producto->codigo;
        $this->precio = $producto->precio;
        $this->stock = $producto->stock;
        $this->categoria_id = $producto->categoria_id;
    }

    // Función para eliminar un producto
    public function delete($id)
    {
        Productos::find($id)->delete();  // Usar Productos correctamente
        session()->flash('success', 'Producto eliminado con éxito');
        $this->productos = Productos::all();  // Usar Productos correctamente
    }

    public function render()
    {
        return view('livewire.producto', [
            'categoria' => Categorias::all(),  // Cambiar categoria por categorias
            'productos' => $this->productos,
        ]);
    }
}