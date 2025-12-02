<?php

namespace App\Livewire;

use App\Models\Envio;
use App\Models\Mensaje;
use Livewire\Component;
use Livewire\Attributes\Layout;

class TrackingEnvio extends Component
{
    public $codigo;
    public $envio;
    public $nuevoMensaje;

    public function mount($codigo)
    {
        $this->codigo = $codigo;
        $this->envio = Envio::where('codigo', $codigo)
            ->with(['historial', 'mensajes', 'repartidor', 'vehiculoAsignacion.vehiculo'])
            ->firstOrFail();
    }

    public function sendMessage()
    {
        $this->validate([
            'nuevoMensaje' => 'required|string|max:1000',
        ]);

        $esRepartidor = false;
        if (auth()->check()) {
            $user = auth()->user();
            $esRepartidor = $user->isAdmin() || $user->isRepartidor();
        }

        Mensaje::create([
            'envio_id' => $this->envio->id,
            'mensaje' => $this->nuevoMensaje,
            'es_repartidor' => $esRepartidor,
        ]);

        $this->nuevoMensaje = '';
        $this->dispatch('scroll-chat');
    }

    #[Layout('layouts.guest')] // Usar layout guest para vista pública
    public function render()
    {
        // Recargar mensajes y estado
        $this->envio->refresh();
        
        return view('livewire.tracking-envio', [
            'mensajes' => $this->envio->mensajes()->orderBy('created_at', 'asc')->get()
        ]);
    }
}
