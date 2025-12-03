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
    
    // Modal para actualizar estado
    public $mostrarModal = false;
    public $envioSeleccionado = null;
    public $nuevoEstado = '';
    public $foto_entrega = null;
    public $observaciones = '';
    public $subiendo = false;

    protected function rules()
    {
        return [
            'foto_entrega' => $this->nuevoEstado === 'entregado' 
                ? 'required|image|max:2048|mimes:jpeg,png,jpg,gif' 
                : 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
            'observaciones' => 'nullable|string|max:500'
        ];
    }

    protected $messages = [
        'foto_entrega.required' => 'La foto de entrega es obligatoria para confirmar la entrega.',
        'foto_entrega.image' => 'El archivo debe ser una imagen válida.',
        'foto_entrega.max' => 'La imagen no debe pesar más de 2MB.',
        'foto_entrega.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg o gif.',
        'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.'
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
        $this->subiendo = false;
        $this->resetValidation();
    }

    public function actualizarEstado()
    {
        $this->validate();

        if (!$this->envioSeleccionado) {
            $this->dispatch('envio-actualizado', [
                'mensaje' => 'Envío no encontrado',
                'tipo' => 'error'
            ]);
            return;
        }

        $this->subiendo = true;

        try {
            $datosActualizar = [
                'estado' => $this->nuevoEstado,
                'observaciones' => $this->observaciones
            ];

            // Guardar foto si existe
            if ($this->foto_entrega) {
                // Crear un nombre único para la imagen
                $nombreArchivo = 'entrega_' . $this->envioSeleccionado->id . '_' . time() . '.' . $this->foto_entrega->extension();
                
                // Almacenar la imagen en storage/app/public/entregas
                $rutaFoto = $this->foto_entrega->storeAs('entregas', $nombreArchivo, 'public');
                
                // Guardar la ruta en la base de datos
                $datosActualizar['foto_entrega'] = $rutaFoto;
                
                // Eliminar foto anterior si existe
                if ($this->envioSeleccionado->foto_entrega && Storage::disk('public')->exists($this->envioSeleccionado->foto_entrega)) {
                    Storage::disk('public')->delete($this->envioSeleccionado->foto_entrega);
                }
            }

            // Actualizar el envío en la base de datos
            $this->envioSeleccionado->update($datosActualizar);

            $this->dispatch('envio-actualizado', [
                'mensaje' => 'Estado y foto de entrega actualizados correctamente',
                'tipo' => 'success'
            ]);

            $this->cerrarModal();
            
            // Refrescar las propiedades computadas
            $this->resetPage();
            unset($this->enviosHoy, $this->estadisticas);
            
        } catch (\Exception $e) {
            $this->dispatch('envio-actualizado', [
                'mensaje' => 'Error al actualizar el envío: ' . $e->getMessage(),
                'tipo' => 'error'
            ]);
        } finally {
            $this->subiendo = false;
        }
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
                
                // Refrescar las propiedades computadas
                unset($this->enviosHoy, $this->estadisticas);
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