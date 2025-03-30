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

    // Función para agregar un nuevo producto o editar uno existente
    public function agregarProducto()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:producto,codigo,' . $this->producto_id,  // Cambiar producto por productos
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categoria,id',  // Cambiar categoria por categorias
        ]);

        Productos::updateOrCreate(
            ['id' => $this->producto_id],
            [
                'nombre' => $this->nombre,
                'codigo' => $this->codigo,
                'precio' => $this->precio,
                'stock' => $this->stock,
                'categoria_id' => $this->categoria_id,
            ]
        );
        

        session()->flash('success', $this->producto_id ? 'Producto actualizado con éxito' : 'Producto agregado con éxito');
        $this->reset();
        $this->productos = Productos::all();  // Usar Productos correctamente
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