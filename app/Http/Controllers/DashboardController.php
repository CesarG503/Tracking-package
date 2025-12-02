<?php
namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $empresaCoordenadas = [
            'lat' => 13.439624,
            'lng' => -88.157400
        ];
    public function index()
    {
        // Verificar el rol y mostrar vista correspondiente
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }else{  
            return $this->repartidorDashboard();
        }

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

        $empresaCoordenadas = $this->empresaCoordenadas;

        return view('dashboard', compact('enviosEnRuta', 'enviosPendientes', 'enviosEntregados', 'stats', 'empresaCoordenadas'));
    }

    /**
     * Dashboard para repartidor
     */
    private function repartidorDashboard()
    {
        $empresaCoordenadas = $this->empresaCoordenadas;

        return view('repartidor.dashboard', compact('empresaCoordenadas'));
    }

}