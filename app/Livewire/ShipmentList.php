<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Envio;
use Livewire\Attributes\On;

class ShipmentList extends Component
{
    public $activeTab = 'pendiente';
    public $enviosEnRuta;
    public $enviosPendientes;
    public $enviosEntregados;
    public $searchTerm = '';
    public $showSearch = false;
    public $allEnviosEnRuta;
    public $allEnviosPendientes;
    public $allEnviosEntregados;

    public function mount()
    {
        // Inicializar las colecciones
        $this->enviosEnRuta = collect();
        $this->enviosPendientes = collect();
        $this->enviosEntregados = collect();
        $this->allEnviosEnRuta = collect();
        $this->allEnviosPendientes = collect();
        $this->allEnviosEntregados = collect();
        
        // Recuperar el tab activo desde la sesión
        $this->activeTab = session('shipment_list_active_tab', 'pendiente');
        $this->loadShipments();
    }

    #[On('shipment-updated')]
    public function loadShipments()
    {
        // Cargar todos los envíos en ruta
        $this->allEnviosEnRuta = Envio::where('estado', 'en_ruta')
            ->with(['repartidor', 'vehiculoAsignacion'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Cargar todos los envíos pendientes
        $this->allEnviosPendientes = Envio::where('estado', 'pendiente')
            ->with(['repartidor'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Cargar todos los envíos entregados
        $this->allEnviosEntregados = Envio::where('estado', 'entregado')
            ->with(['repartidor'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $this->applySearch();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        // Guardar el tab activo en la sesión
        session(['shipment_list_active_tab' => $tab]);
    }

    public function selectShipment($shipmentId)
    {
        // Disparar evento para actualizar la tarjeta de detalles
        $this->dispatch('shipment-selected', shipmentId: $shipmentId);
    }

    public function toggleSearch()
    {
        $this->showSearch = !$this->showSearch;
        if (!$this->showSearch) {
            $this->searchTerm = '';
            $this->applySearch();
        }
    }

    public function updatedSearchTerm()
    {
        $this->applySearch();
    }

    protected function applySearch()
    {
        $search = strtolower(trim($this->searchTerm));

        if (empty($search)) {
            // Sin búsqueda, mostrar todos (con límites)
            $this->enviosEnRuta = $this->allEnviosEnRuta;
            $this->enviosPendientes = $this->allEnviosPendientes->take(10);
            $this->enviosEntregados = $this->allEnviosEntregados->take(10);
        } else {
            // Aplicar búsqueda
            $this->enviosEnRuta = $this->allEnviosEnRuta->filter(function ($envio) use ($search) {
                return str_contains(strtolower($envio->codigo), $search) ||
                       str_contains(strtolower($envio->remitente_direccion), $search) ||
                       str_contains(strtolower($envio->destinatario_direccion), $search) ||
                       str_contains(strtolower($envio->destinatario_nombre), $search) ||
                       ($envio->repartidor && str_contains(strtolower($envio->repartidor->nombre), $search));
            });

            $this->enviosPendientes = $this->allEnviosPendientes->filter(function ($envio) use ($search) {
                return str_contains(strtolower($envio->codigo), $search) ||
                       str_contains(strtolower($envio->remitente_direccion), $search) ||
                       str_contains(strtolower($envio->destinatario_direccion), $search) ||
                       str_contains(strtolower($envio->destinatario_nombre), $search) ||
                       ($envio->repartidor && str_contains(strtolower($envio->repartidor->nombre), $search));
            });

            $this->enviosEntregados = $this->allEnviosEntregados->filter(function ($envio) use ($search) {
                return str_contains(strtolower($envio->codigo), $search) ||
                       str_contains(strtolower($envio->remitente_direccion), $search) ||
                       str_contains(strtolower($envio->destinatario_direccion), $search) ||
                       str_contains(strtolower($envio->destinatario_nombre), $search) ||
                       ($envio->repartidor && str_contains(strtolower($envio->repartidor->nombre), $search));
            });
        }
    }

    public function render()
    {
        return view('livewire.shipment-list');
    }
}

