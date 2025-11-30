<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Verificar el rol y mostrar vista correspondiente
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->repartidorDashboard();
    }

    /**
     * Dashboard para admins
     */
    private function adminDashboard()
    {
        $enviosEnRuta = Envio::where('estado', 'en_ruta')
            ->with(['repartidor', 'vehiculoAsignacion.vehiculo'])
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $enviosPendientes = Envio::where('estado', 'pendiente')
            ->with(['repartidor'])
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $enviosEntregados = Envio::where('estado', 'entregado')
            ->with(['repartidor'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_envios' => Envio::count(),
            'en_ruta' => Envio::where('estado', 'en_ruta')->count(),
            'pendientes' => Envio::where('estado', 'pendiente')->count(),
            'entregados' => Envio::where('estado', 'entregado')->count(),
            'repartidores_activos' => User::where('rol', 'repartidor')->where('activo', true)->count(),
            'vehiculos_disponibles' => Vehiculo::where('estado', 'disponible')->count(),
        ];

        // Coordenadas de la empresa
        $empresaCoordenadas = [
            'lat' => 13.439624,
            'lng' => -88.157400
        ];

        return view('dashboard', compact('enviosEnRuta', 'enviosPendientes', 'enviosEntregados', 'stats', 'empresaCoordenadas'));
    }

    /**
     * Dashboard para repartidores
     */
    private function repartidorDashboard()
    {
        $repartidor = auth()->user();

        // Solo SUS envíos
        $enviosEnRuta = Envio::where('repartidor_id', $repartidor->id)
            ->where('estado', 'en_ruta')
            ->with(['vehiculoAsignacion.vehiculo'])
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $enviosPendientes = Envio::where('repartidor_id', $repartidor->id)
            ->where('estado', 'pendiente')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $enviosEntregados = Envio::where('repartidor_id', $repartidor->id)
            ->where('estado', 'entregado')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_envios' => $repartidor->envios->count(),
            'en_ruta' => $repartidor->envios->where('estado', 'en_ruta')->count(),
            'pendientes' => $repartidor->envios->where('estado', 'pendiente')->count(),
            'entregados' => $repartidor->envios->where('estado', 'entregado')->count(),
            'repartidores_activos' => 0, // No necesita ver esto
            'vehiculos_disponibles' => 0, // No necesita ver esto
        ];

        $empresaCoordenadas = [
            'lat' => 13.439624,
            'lng' => -88.157400
        ];
        

        return view('repartidor.dashboard', compact('enviosEnRuta', 'enviosPendientes', 'enviosEntregados', 'stats', 'empresaCoordenadas'));
    }
}
