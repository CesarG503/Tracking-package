<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Envio;
use Livewire\Attributes\On;

class ShipmentList extends Component
{
    public $activeTab = 'pendiente';
    public $enviosEnRuta = [];
    public $enviosPendientes = [];
    public $enviosEntregados = [];

    public function mount()
    {
        $this->loadShipments();
    }

    #[On('shipment-updated')]
    public function loadShipments()
    {
        // Cargar envíos en ruta
        $this->enviosEnRuta = Envio::where('estado', 'en_ruta')
            ->with(['repartidor', 'vehiculoAsignacion'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Cargar envíos pendientes
        $this->enviosPendientes = Envio::where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Cargar envíos entregados
        $this->enviosEntregados = Envio::where('estado', 'entregado')
            ->with(['repartidor'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function selectShipment($shipmentId)
    {
        // Disparar evento para actualizar la tarjeta de detalles
        $this->dispatch('shipment-selected', shipmentId: $shipmentId);
    }

    public function render()
    {
        return view('livewire.shipment-list');
    }
}

