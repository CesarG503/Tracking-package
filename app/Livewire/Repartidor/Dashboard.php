<?php

namespace App\Livewire\Repartidor;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $empresaCoordenadas = [
        'lat' => 13.439624,
        'lng' => -88.157400
    ];

    public $enviosAnteriores = [];
    public $primeraVez = true; // Flag para saber si es la primera carga
    
    // Método para stats de HOY
    #[Computed]
    public function statsHoy()
    {
        $repartidor = Auth::user();
        $hoy = now()->startOfDay();

        return [
            'asignados' => $repartidor->envios()
                ->whereIn('estado', ['pendiente', 'en_ruta'])
                ->whereDate('created_at', $hoy)
                ->count(),
            
            'entregados_hoy' => $repartidor->envios()
                ->where('estado', 'entregado')
                ->whereDate('created_at', $hoy)
                ->count(),
            
            'devueltos' => $repartidor->envios()
                ->where('estado', 'devuelto')
                ->whereDate('created_at', $hoy)
                ->count(),
                
            'cancelados' => $repartidor->envios()
                ->where('estado', 'cancelado')
                ->whereDate('created_at', $hoy)
                ->count(),
        ];
    }

    // Vehículo(s) asignado(s) para ESTA SEMANA
    #[Computed]
    public function vehiculosSemana()
    {
        $repartidor = Auth::user();
        $inicioSemana = now()->startOfWeek();
        $finSemana = now()->endOfWeek();

        // Obtener asignaciones que se solapen con la semana actual
        $asignacionesSemana = $repartidor->vehiculoAsignaciones()
            ->where('estado', 'activo')
            ->where(function($query) use ($inicioSemana, $finSemana) {
                $query->whereBetween('fecha_inicio', [$inicioSemana, $finSemana])
                      ->orWhereBetween('fecha_fin', [$inicioSemana, $finSemana])
                      ->orWhere(function($q) use ($inicioSemana, $finSemana) {
                          $q->where('fecha_inicio', '<=', $inicioSemana)
                            ->where('fecha_fin', '>=', $finSemana);
                      });
            })
            ->with('vehiculo')
            ->get()
            ->unique('vehiculo_id'); // Mostrar vehículos únicos asignados esta semana

        return $asignacionesSemana;
    }

    // Actividad reciente (últimos 7 días)
    #[Computed]
    public function actividadReciente()
    {
        $repartidor = Auth::user();

        return $repartidor->envios()
            ->where('updated_at', '>=', now()->subDays(7))
            ->with(['vehiculoAsignacion.vehiculo'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
    }

    public $diaSeleccionado = null;

    // Calendario semanal
    #[Computed]
    public function diasSemana()
    {
        $repartidor = Auth::user();
        $inicioSemana = now()->startOfWeek();
        $finSemana = now()->endOfWeek();

        $disponibilidadSemana = $repartidor->disponibilidades()
            ->where(function($query) use ($inicioSemana, $finSemana) {
                $query->whereBetween('fecha_inicio', [$inicioSemana, $finSemana])
                      ->orWhereBetween('fecha_fin', [$inicioSemana, $finSemana]);
            })
            ->get();

        $dias = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha = $inicioSemana->copy()->addDays($i);
            
            $evento = $disponibilidadSemana->first(function($disp) use ($fecha) {
                return $fecha->between(
                    \Carbon\Carbon::parse($disp->fecha_inicio)->startOfDay(),
                    \Carbon\Carbon::parse($disp->fecha_fin)->endOfDay()
                );
            });
            
            $dias[] = [
                'fecha' => $fecha->format('Y-m-d'),
                'dia_nombre' => $fecha->locale('es')->translatedFormat('D'),
                'dia_numero' => $fecha->format('d'),
                'tipo' => $evento ? $evento->tipo : null, // Null si no hay evento (no mostrar 'disponible')
                'descripcion' => $evento ? $evento->descripcion : null,
                'horario' => $evento ? \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') . ' - ' . \Carbon\Carbon::parse($evento->fecha_fin)->format('H:i') : null,
                'es_hoy' => $fecha->isToday()
            ];
        }

        return $dias;
    }

    public function seleccionarDia($fecha)
    {
        $repartidor = Auth::user();
        $fechaCarbon = \Carbon\Carbon::parse($fecha);
        
        $evento = $repartidor->disponibilidades()
            ->where(function($query) use ($fechaCarbon) {
                $query->where('fecha_inicio', '<=', $fechaCarbon->endOfDay())
                      ->where('fecha_fin', '>=', $fechaCarbon->startOfDay());
            })
            ->first();
            
        $vehiculoAsignacion = $repartidor->vehiculoAsignaciones()
            ->where('estado', 'activo')
            ->where('fecha_inicio', '<=', $fechaCarbon->endOfDay())
            ->where('fecha_fin', '>=', $fechaCarbon->startOfDay())
            ->with('vehiculo')
            ->first();

        $this->diaSeleccionado = [
            'fecha' => $fechaCarbon->locale('es')->translatedFormat('l, d \d\e F \d\e Y'),
            'tipo' => $evento ? $evento->tipo : 'disponible',
            'descripcion' => $evento ? $evento->descripcion : 'Sin descripción',
            'vehiculo' => $vehiculoAsignacion ? $vehiculoAsignacion->vehiculo : null,
            'horario' => $evento ? 
                \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') . ' - ' . \Carbon\Carbon::parse($evento->fecha_fin)->format('H:i') 
                : '08:00 - 17:00 (Por defecto)'
        ];
    }
    
    public function cerrarDetalle()
    {
        $this->diaSeleccionado = null;
    }

    // LOGICA MAPA - Obtener envíos con coordenadas para el mapa
    #[Computed]
    public function enviosEnMapa()
    {
        $repartidor = Auth::user();
        $hoy = now()->startOfDay();
        
        $envios = $repartidor->envios()
            ->whereIn('estado', ['pendiente', 'en_ruta'])
            ->whereDate('created_at', $hoy)
            ->get()
            ->map(function($envio) {
                return [
                    'id' => $envio->id,
                    'lat' => $envio->lat,
                    'lng' => $envio->lng,
                    'estado' => $envio->estado,
                    'destinatario' => $envio->destinatario_nombre,
                    'direccion' => $envio->destinatario_direccion,
                    'telefono' => $envio->destinatario_telefono,
                    'codigo' => $envio->codigo,
                ];
            })
            ->filter(function($envio) {
                return !is_null($envio['lat']) && !is_null($envio['lng']);
            })
            ->values()
            ->toArray();

        // Detectar cambios y disparar evento
        $this->detectarCambiosEnvios($envios);

        return $envios;
    }

    // Detectar nuevos envíos o cambios de estado
    private function detectarCambiosEnvios($enviosActuales)
    {
        // Si es la primera vez, no disparar eventos, solo guardar el estado inicial
        if ($this->primeraVez) {
            $this->enviosAnteriores = $enviosActuales;
            $this->primeraVez = false;
            return;
        }

        $idsActuales = collect($enviosActuales)->pluck('id')->toArray();
        $idsAnteriores = collect($this->enviosAnteriores)->pluck('id')->toArray();

        // Detectar nuevos envíos
        $nuevosIds = array_diff($idsActuales, $idsAnteriores);
        foreach ($nuevosIds as $nuevoId) {
            $envio = collect($enviosActuales)->firstWhere('id', $nuevoId);
            if ($envio) {
                $this->dispatch('nuevo-envio-mapa', $envio);
            }
        }

        // Detectar envíos eliminados (entregados, cancelados, etc)
        $eliminadosIds = array_diff($idsAnteriores, $idsActuales);
        foreach ($eliminadosIds as $eliminadoId) {
            $this->dispatch('eliminar-envio-mapa', ['id' => $eliminadoId]);
        }

        // Detectar cambios de estado
        foreach ($enviosActuales as $envioActual) {
            $envioAnterior = collect($this->enviosAnteriores)->firstWhere('id', $envioActual['id']);
            if ($envioAnterior && $envioAnterior['estado'] !== $envioActual['estado']) {
                $this->dispatch('actualizar-estado-envio-mapa', $envioActual);
            }
        }

        // Actualizar el array de envíos anteriores
        $this->enviosAnteriores = $enviosActuales;
    }

    public function mount()
    {
        // Inicializar con los envíos actuales
        $this->enviosAnteriores = $this->enviosEnMapa;
    }

    public function render()
    {
        return view('livewire.repartidor.dashboard');
    }
}