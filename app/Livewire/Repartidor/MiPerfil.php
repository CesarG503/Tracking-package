<?php
// app/Livewire/Repartidor/MiPerfil.php
namespace App\Livewire\Repartidor;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class MiPerfil extends Component
{
    #[Computed]
    public function estadisticasGenerales()
    {
        $repartidor = Auth::user();
        $totalEnvios = $repartidor->envios()->count();
        $entregados = $repartidor->envios()->where('estado', 'entregado')->count();
        
        return [
            'total_envios' => $totalEnvios,
            'entregados' => $entregados,
            'en_ruta' => $repartidor->envios()->where('estado', 'en_ruta')->count(),
            'pendientes' => $repartidor->envios()->where('estado', 'pendiente')->count(),
            'devueltos' => $repartidor->envios()->where('estado', 'devuelto')->count(),
            'tasa_exito' => $totalEnvios > 0 
                ? round(($entregados / $totalEnvios) * 100, 1)
                : 0,
        ];
    }

    #[Computed]
    public function vehiculoActual()
    {
        return Auth::user()->vehiculoAsignaciones()
            ->where('estado', 'activo')
            ->with('vehiculo')
            ->first();
    }

    #[Computed]
    public function enviosDelMes()
    {
        return Auth::user()->envios()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function render()
    {
        return view('livewire.repartidor.mi-perfil');
    }
}