<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Categorias;

class Categoria extends Component
{
    public $nombre, $descripcion, $modo_edicion = false, $categoria_id;
    public $categorias;

    public function mount()
    {
        // Cargar categorías al inicializar el componente
        $this->categorias = Categorias::all();
    }

    // Función para agregar o actualizar una categoría
    public function agregarCategoria()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
           
        ]);

        Categorias::create([
            'nombre' => $this->nombre,
       
        ]);

        session()->flash('success', 'Categoría agregada con éxito');
        $this->resetCampos();
        $this->categorias = Categorias::all();
    }

    // Función para editar una categoría
    public function editarCategoria($id)
    {
        $categoria = Categorias::find($id);
        $this->categoria_id = $categoria->id;
        $this->nombre = $categoria->nombre;
        $this->modo_edicion = true;
    }

    // Función para actualizar la categoría
    public function actualizarCategoria()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
        
        ]);

        Categorias::find($this->categoria_id)->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);

        session()->flash('success', 'Categoría actualizada con éxito');
        $this->resetCampos();
        $this->categorias = Categorias::all();
    }

    // Función para eliminar una categoría
    public function eliminarCategoria($id)
    {
        Categorias::find($id)->delete();
        session()->flash('success', 'Categoría eliminada con éxito');
        $this->categorias = Categorias::all();
    }

    // Función para resetear los campos y el modo de edición
    public function resetCampos()
    {
        $this->nombre = '';
        $this->modo_edicion = false;
        $this->categoria_id = null;
    }

    public function render()
    {
        return view('livewire.categoria', [
            'categoria' => $this->categorias,
        ]);
    }
}
