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
    public $envioSeleccionado = null;
    
    // Modal para actualizar estado
    public $mostrarModal = false;
    public $envioParaActualizar = null;
    public $nuevoEstado = '';
    public $foto_entrega = null;
    public $observaciones = '';
    
    protected $paginationTheme = 'tailwind';

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

    public function selectShipment($envioId)
    {
        $this->envioSeleccionado = $envioId;
        $envio = Auth::user()->envios()->find($envioId);
        
        if ($envio) {
            $this->dispatch('shipment-selected', [
                'lat' => $envio->lat,
                'lng' => $envio->lng,
                'nombre' => $envio->destinatario_nombre,
                'direccion' => $envio->destinatario_direccion,
                'id' => $envio->id,
                'codigo' => $envio->codigo
            ]);
        }
    }

    public function abrirModalEstado($envioId, $nuevoEstado)
    {
        $this->envioParaActualizar = Auth::user()->envios()->find($envioId);
        
        if (!$this->envioParaActualizar) {
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
        $this->envioParaActualizar = null;
        $this->nuevoEstado = '';
        $this->foto_entrega = null;
        $this->observaciones = '';
        $this->subiendo = false;
        $this->resetValidation();
    }

    public function actualizarEstado()
{
    $this->validate();

    if (!$this->envioParaActualizar) {
        return;
    }

    $datosActualizar = [
        'estado' => $this->nuevoEstado,
        'observaciones' => $this->observaciones
    ];

    // Guardar foto si existe
    if ($this->foto_entrega) {
        $nombreArchivo = 'entrega_' . $this->envioParaActualizar->id . '_' . time() . '.' . $this->foto_entrega->extension();
        $rutaFoto = $this->foto_entrega->storeAs('entregas', $nombreArchivo, 'public');
        $datosActualizar['foto_entrega'] = $rutaFoto;
    }

    $this->envioParaActualizar->update($datosActualizar);

    $this->dispatch('envio-actualizado', [
        'mensaje' => 'Estado actualizado correctamente',
        'tipo' => 'success'
    ]);

    $this->cerrarModal();
    
    $this->dispatch('$refresh');
}

    public function cambiarEstado($envioId, $nuevoEstado)
{
    // Para "en_ruta" cambio directo, para otros abre modal
    if ($nuevoEstado === 'en_ruta') {
        $envio = Auth::user()->envios()->find($envioId);
        
        if ($envio) {
            $envio->update(['estado' => $nuevoEstado]);
            
            $this->dispatch('envio-actualizado', [
                'mensaje' => 'Ruta iniciada correctamente',
                'tipo' => 'success'
            ]);
            
            $this->dispatch('$refresh');
        }
    } else {
        // Para entregado y devuelto, abrir modal
        $this->abrirModalEstado($envioId, $nuevoEstado);
    }
}

    #[Computed]
    public function enviosHoy()
    {
        $query = Auth::user()->envios()
            ->whereDate('created_at', now()->toDateString())
            ->with(['vehiculoAsignacion.vehiculo'])
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

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.repartidor.mis-envios');
    }
}