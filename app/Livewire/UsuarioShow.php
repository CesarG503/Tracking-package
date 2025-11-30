<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UsuarioShow extends Component
{
    public User $usuario;
    public $mostrarTodosVehiculos = false;
    public $mostrarTodosEnvios = false;
    
    public function mount($usuario)
    {
        $this->usuario = $usuario->load(['envios', 'vehiculoAsignaciones.vehiculo']);
    }
    
    public function toggleMostrarTodosVehiculos()
    {
        $this->mostrarTodosVehiculos = !$this->mostrarTodosVehiculos;
    }
    
    public function toggleMostrarTodosEnvios()
    {
        $this->mostrarTodosEnvios = !$this->mostrarTodosEnvios;
    }
    
    
    public function render()
    {
        // Calcular estadísticas
        $totalEnvios = $this->usuario->envios->count();
        $enviosEntregados = $this->usuario->envios->where('estado', 'entregado')->count();
        $vehiculosActivos = $this->usuario->vehiculoAsignaciones->where('estado', 'activo')->count();
        $enviosEnProceso = $this->usuario->envios->whereIn('estado', ['pendiente', 'en_ruta'])->count();
        
        // Calcular tasa de éxito
        $finalizados = $this->usuario->envios->whereIn('estado', ['entregado', 'devuelto', 'cancelado']);
        $exitosos = $this->usuario->envios->where('estado', 'entregado');
        $tasaExito = $finalizados->count() > 0 ? round(($exitosos->count() / $finalizados->count()) * 100) : 0;
        
        // Filtrar vehículos
        $vehiculos = $this->mostrarTodosVehiculos 
            ? $this->usuario->vehiculoAsignaciones 
            : $this->usuario->vehiculoAsignaciones->take(5);
            
        // Filtrar envíos recientes
        $enviosRecientes = $this->mostrarTodosEnvios 
            ? $this->usuario->envios->sortByDesc('created_at')
            : $this->usuario->envios->sortByDesc('created_at')->take(5);
        
        return view('livewire.usuario-show', compact(
            'totalEnvios',
            'enviosEntregados',
            'vehiculosActivos',
            'tasaExito',
            'enviosEnProceso',
            'vehiculos',
            'enviosRecientes'
        ));
    }
}