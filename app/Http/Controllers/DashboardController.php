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

        return view('dashboard', compact('enviosEnRuta', 'enviosPendientes', 'enviosEntregados', 'stats'));
    }
}
