<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Envio;
use Livewire\Attributes\On;

class OrderDetails extends Component
{
    public $selectedShipment = null;
    public $showCard = false;

    public function mount()
    {
        // No mostrar nada al inicio
        $this->showCard = false;
    }

    #[On('shipment-selected')]
    public function loadShipment($shipmentId)
    {
        $this->selectedShipment = Envio::with(['repartidor', 'vehiculoAsignacion'])
            ->find($shipmentId);
        
        if ($this->selectedShipment) {
            $this->showCard = true;
        }
    }

    public function render()
    {
        return view('livewire.order-details');
    }
}
