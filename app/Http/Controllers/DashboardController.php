<?php

// namespace App\Http\Controllers;

// use App\Models\Envio;
// use App\Models\User;
// use App\Models\Vehiculo;
// use Illuminate\Http\Request;

// class DashboardController extends Controller
// {
//     public function index()
//     {
//         // Verificar el rol y mostrar vista correspondiente
//         if (auth()->user()->isAdmin()) {
//             return $this->adminDashboard();
//         }

//         return $this->repartidorDashboard();
//     }

//     /**
//      * Dashboard para admins
//      */
//     private function adminDashboard()
//     {
//         $enviosEnRuta = Envio::where('estado', 'en_ruta')
//             ->with(['repartidor', 'vehiculoAsignacion.vehiculo'])
//             ->orderBy('fecha_creacion', 'desc')
//             ->get();

//         $enviosPendientes = Envio::where('estado', 'pendiente')
//             ->with(['repartidor'])
//             ->orderBy('fecha_creacion', 'desc')
//             ->get();

//         $enviosEntregados = Envio::where('estado', 'entregado')
//             ->with(['repartidor'])
//             ->orderBy('updated_at', 'desc')
//             ->limit(10)
//             ->get();

//         $stats = [
//             'total_envios' => Envio::count(),
//             'en_ruta' => Envio::where('estado', 'en_ruta')->count(),
//             'pendientes' => Envio::where('estado', 'pendiente')->count(),
//             'entregados' => Envio::where('estado', 'entregado')->count(),
//             'repartidores_activos' => User::where('rol', 'repartidor')->where('activo', true)->count(),
//             'vehiculos_disponibles' => Vehiculo::where('estado', 'disponible')->count(),
//         ];

//         // Coordenadas de la empresa
//         $empresaCoordenadas = [
//             'lat' => 13.439624,
//             'lng' => -88.157400
//         ];

//         return view('dashboard', compact('enviosEnRuta', 'enviosPendientes', 'enviosEntregados', 'stats', 'empresaCoordenadas'));
//     }

//     /**
//      * Dashboard para repartidores
//      */
//     private function repartidorDashboard()
//     {
//         $repartidor = auth()->user();

//         // Solo SUS envíos
//         $enviosEnRuta = Envio::where('repartidor_id', $repartidor->id)
//             ->where('estado', 'en_ruta')
//             ->with(['vehiculoAsignacion.vehiculo'])
//             ->orderBy('fecha_creacion', 'desc')
//             ->get();

//         $enviosPendientes = Envio::where('repartidor_id', $repartidor->id)
//             ->where('estado', 'pendiente')
//             ->orderBy('fecha_creacion', 'desc')
//             ->get();

//         $enviosEntregados = Envio::where('repartidor_id', $repartidor->id)
//             ->where('estado', 'entregado')
//             ->orderBy('updated_at', 'desc')
//             ->limit(10)
//             ->get();

//         $stats = [
//             'total_envios' => $repartidor->envios->count(),
//             'en_ruta' => $repartidor->envios->where('estado', 'en_ruta')->count(),
//             'pendientes' => $repartidor->envios->where('estado', 'pendiente')->count(),
//             'entregados' => $repartidor->envios->where('estado', 'entregado')->count(),
//         ];

//         $empresaCoordenadas = [
//             'lat' => 13.439624,
//             'lng' => -88.157400
//         ];
        
//         // Obtener la semana actual (lunes a domingo)
//         $inicioSemana = now()->startOfWeek(); // Lunes
//         $finSemana = now()->endOfWeek(); // Domingo

//         // Obtener los eventos de disponibilidad de esta semana
//         $disponibilidadSemana = $repartidor->disponibilidades()
//             ->where(function($query) use ($inicioSemana, $finSemana) {
//                 $query->whereBetween('fecha_inicio', [$inicioSemana, $finSemana])
//                       ->orWhereBetween('fecha_fin', [$inicioSemana, $finSemana]);
//             })
//             ->get();

//         // Crear array con los días de la semana
//         $diasSemana = [];
//         for ($i = 0; $i < 7; $i++) {
//             $fecha = $inicioSemana->copy()->addDays($i);
            
//             // Buscar si hay un evento para este día
//             $evento = $disponibilidadSemana->first(function($disp) use ($fecha) {
//                 return $fecha->between(
//                     \Carbon\Carbon::parse($disp->fecha_inicio)->startOfDay(),
//                     \Carbon\Carbon::parse($disp->fecha_fin)->endOfDay()
//                 );
//             });
            
//             $diasSemana[] = [
//                 'fecha' => $fecha,
//                 'dia_nombre' => $fecha->locale('es')->translatedFormat('D'),
//                 'tipo' => $evento ? $evento->tipo : 'disponible',
//                 'descripcion' => $evento ? $evento->descripcion : null,
//                 'es_hoy' => $fecha->isToday()
//             ];
//         }

//         return view('repartidor.dashboard', compact('enviosEnRuta', 'enviosPendientes', 'enviosEntregados', 'stats', 'empresaCoordenadas', 'diasSemana'));
//     }
// }


// AQUIIIIIIIIII

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

        //el usuario es repartidor
        return view('repartidor.dashboard');
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

        $empresaCoordenadas = [
            'lat' => 13.439624,
            'lng' => -88.157400
        ];

        return view('dashboard', compact('enviosEnRuta', 'enviosPendientes', 'enviosEntregados', 'stats', 'empresaCoordenadas'));
    }
}