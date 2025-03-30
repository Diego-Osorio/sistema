<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Clientes;

class Cliente extends Component
{
    public $nombre, $email, $telefono, $direccion, $cliente_id;

    public function mount($cliente_id = null)
    {
        if ($cliente_id) {
            $cliente = Clientes::find($cliente_id);
            if ($cliente) {
                $this->cliente_id = $cliente->id;
                $this->nombre = $cliente->nombre;
                $this->email = $cliente->email;
                $this->telefono = $cliente->telefono;
                $this->direccion = $cliente->direccion;
            } else {
                session()->flash('message', 'Cliente no encontrado.');
            }
        }
    }
    

    // Método para guardar un nuevo cliente o actualizar uno existente
    public function submit()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:cliente,email,' . $this->cliente_id,  // Permite actualizar email sin marcar como único
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        if ($this->cliente_id) {
            $cliente = Clientes::find($this->cliente_id);
            $cliente->update([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
            ]);
        } else {
            Clientes::create([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
            ]);
        }

        $this->reset(['nombre', 'email', 'telefono', 'direccion', 'cliente_id']);
        session()->flash('message', $this->cliente_id ? 'Cliente actualizado correctamente.' : 'Cliente registrado correctamente.');
    }

    public function render()
    {
        return view('livewire.cliente', [
            'clientes' => Clientes::all(),
        ]);
    }
}
