<?php
// app/Livewire/Repartidor/MisEnvios.php

namespace App\Livewire\Repartidor;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MisEnvios extends Component
{
    use WithPagination, WithFileUploads;

    public $busqueda = '';
    public $estadoSeleccionado = 'todos';
    
    // Coordenadas de la empresa
    public $empresaCoordenadas = [
        'lat' => 13.439624,
        'lng' => -88.157400
    ];
    
    // Modal para actualizar estado
    public $mostrarModal = false;
    public $envioSeleccionado = null;
    public $nuevoEstado = '';
    public $foto_entrega = null;
    public $observaciones = '';

    protected $rules = [
        'foto_entrega' => 'nullable|image|max:2048',
        'observaciones' => 'nullable|string|max:500'
    ];

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingEstadoSeleccionado()
    {
        $this->resetPage();
    }

    #[Computed]
    public function enviosHoy()
    {
        $query = Auth::user()->envios()
            ->whereDate('created_at', now()->toDateString())
            ->with(['vehiculoAsignacion.vehiculo'])
            ->withCount(['mensajes as mensajes_cliente_count' => function ($query) {
                $query->where('es_repartidor', false)->where('leido', false);
            }])
            ->orderBy('created_at', 'desc');

        if ($this->estadoSeleccionado !== 'todos') {
            $query->where('estado', $this->estadoSeleccionado);
        }

        if ($this->busqueda) {
            $query->where(function($q) {
                $q->where('destinatario_nombre', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('destinatario_direccion', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('codigo', 'like', '%' . $this->busqueda . '%');
            });
        }

        return $query->paginate(3);
    }

    #[Computed]
    public function estadisticas()
    {
        $enviosBase = Auth::user()->envios()
            ->whereDate('created_at', now()->toDateString());

        return [
            'pendientes' => (clone $enviosBase)->where('estado', 'pendiente')->count(),
            'en_ruta' => (clone $enviosBase)->where('estado', 'en_ruta')->count(),
            'entregados' => (clone $enviosBase)->where('estado', 'entregado')->count(),
        ];
    }

    public function abrirModalEstado($envioId, $nuevoEstado)
    {
        $this->envioSeleccionado = Auth::user()->envios()->find($envioId);
        
        if (!$this->envioSeleccionado) {
            $this->dispatch('envio-actualizado', [
                'mensaje' => 'Envío no encontrado',
                'tipo' => 'error'
            ]);
            return;
        }

        $this->nuevoEstado = $nuevoEstado;
        $this->mostrarModal = true;
        $this->foto_entrega = null;
        $this->observaciones = '';
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->envioSeleccionado = null;
        $this->nuevoEstado = '';
        $this->foto_entrega = null;
        $this->observaciones = '';
        $this->resetValidation();
    }

    public function actualizarEstado()
    {
        $this->validate();

        if (!$this->envioSeleccionado) {
            return;
        }

        $datosActualizar = [
            'estado' => $this->nuevoEstado,
            'observaciones' => $this->observaciones
        ];

        // Guardar foto si existe
        if ($this->foto_entrega) {
            $nombreArchivo = 'entrega_' . $this->envioSeleccionado->id . '_' . time() . '.' . $this->foto_entrega->extension();
            $rutaFoto = $this->foto_entrega->storeAs('entregas', $nombreArchivo, 'public');
            $datosActualizar['foto_entrega'] = $rutaFoto;
        }

        $this->envioSeleccionado->update($datosActualizar);

        $this->dispatch('envio-actualizado', [
            'mensaje' => 'Estado actualizado correctamente',
            'tipo' => 'success'
        ]);

        $this->cerrarModal();
    }

    public function cambiarEstado($envioId, $nuevoEstado)
    {
        // Para "en_ruta" cambio directo, para otros abre modal
        if ($nuevoEstado === 'en_ruta') {
            $envio = Auth::user()->envios()->find($envioId);
            
            if ($envio) {
                $envio->update(['estado' => $nuevoEstado]);
                
                $this->dispatch('envio-actualizado', [
                    'mensaje' => 'Estado actualizado correctamente',
                    'tipo' => 'success'
                ]);
            }
        } else {
            $this->abrirModalEstado($envioId, $nuevoEstado);
        }
    }

    public function render()
    {
        return view('livewire.repartidor.mis-envios');
    }
}