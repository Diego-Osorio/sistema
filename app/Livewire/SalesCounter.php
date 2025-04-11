<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Ventas;

class SalesCounter extends Component
{
    public $totalSales = 0;

    // Listener para actualizar el contador cuando se emita el evento 'ventaRegistrada'
    protected $listeners = ['ventaRegistrada' => 'updateTotalSales'];

    public function mount()
    {
        $this->updateTotalSales();
    }

    public function updateTotalSales()
    {
        // Sumar las ventas completadas
        $this->totalSales = Ventas::where('estado', 'completado')->sum('total');
    }

    public function render()
    {
        // Pasamos la variable totalSales a la vista
        return view('livewire.sales-counter');
    }
}
