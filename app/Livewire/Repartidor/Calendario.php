<?php

namespace App\Livewire\Repartidor;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Calendario extends Component
{
    public $mes;
    public $anio;
    public $dias = [];
    public $diaSeleccionado = null;

    public function mount()
    {
        $this->mes = now()->month;
        $this->anio = now()->year;
        $this->cargarDias();
    }

    public function cargarDias()
    {
        $repartidor = Auth::user();
        $fechaInicio = Carbon::create($this->anio, $this->mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($this->anio, $this->mes, 1)->endOfMonth();

        // Ajustar inicio para empezar en lunes (o domingo según config, aquí lunes)
        $inicioCalendario = $fechaInicio->copy()->startOfWeek();
        $finCalendario = $fechaFin->copy()->endOfWeek();

        $disponibilidades = $repartidor->disponibilidades()
            ->where(function($query) use ($inicioCalendario, $finCalendario) {
                $query->whereBetween('fecha_inicio', [$inicioCalendario, $finCalendario])
                      ->orWhereBetween('fecha_fin', [$inicioCalendario, $finCalendario]);
            })
            ->get();

        $this->dias = [];
        $fechaActual = $inicioCalendario->copy();

        while ($fechaActual <= $finCalendario) {
            $eventosDelDia = $disponibilidades->filter(function($disp) use ($fechaActual) {
                return $fechaActual->between(
                    Carbon::parse($disp->fecha_inicio)->startOfDay(),
                    Carbon::parse($disp->fecha_fin)->endOfDay()
                );
            })->map(function($evento) {
                return [
                    'id' => $evento->id,
                    'tipo' => $evento->tipo,
                    'descripcion' => $evento->descripcion,
                    'hora_inicio' => Carbon::parse($evento->fecha_inicio)->format('H:i'),
                    'hora_fin' => Carbon::parse($evento->fecha_fin)->format('H:i'),
                ];
            })->values()->toArray();

            $this->dias[] = [
                'fecha' => $fechaActual->format('Y-m-d'),
                'dia' => $fechaActual->day,
                'es_mes_actual' => $fechaActual->month == $this->mes,
                'es_hoy' => $fechaActual->isToday(),
                'eventos' => $eventosDelDia
            ];

            $fechaActual->addDay();
        }
    }

    public function mesAnterior()
    {
        $fecha = Carbon::create($this->anio, $this->mes, 1)->subMonth();
        $this->mes = $fecha->month;
        $this->anio = $fecha->year;
        $this->cargarDias();
    }

    public function mesSiguiente()
    {
        $fecha = Carbon::create($this->anio, $this->mes, 1)->addMonth();
        $this->mes = $fecha->month;
        $this->anio = $fecha->year;
        $this->cargarDias();
    }
    
    public function seleccionarDia($fecha)
    {
        // Buscar evento para ese día
        $repartidor = Auth::user();
        $fechaCarbon = Carbon::parse($fecha);
        
        // Obtener todas las disponibilidades que se solapen con el día seleccionado
        // Usamos la misma lógica de filtrado que en cargarDias para consistencia
        $inicioDia = $fechaCarbon->copy()->startOfDay();
        $finDia = $fechaCarbon->copy()->endOfDay();
        
        $eventos = $repartidor->disponibilidades()
            ->with('vehiculo') // Eager load vehiculo
            ->get() 
            ->filter(function($disp) use ($inicioDia, $finDia) {
                return $inicioDia->between(
                        Carbon::parse($disp->fecha_inicio)->startOfDay(), 
                        Carbon::parse($disp->fecha_fin)->endOfDay()
                    ) || 
                    $finDia->between(
                        Carbon::parse($disp->fecha_inicio)->startOfDay(), 
                        Carbon::parse($disp->fecha_fin)->endOfDay()
                    ) ||
                    (Carbon::parse($disp->fecha_inicio)->gte($inicioDia) && Carbon::parse($disp->fecha_fin)->lte($finDia));
            });
            
        // Buscar vehículo asignado para ese día (mantener por compatibilidad si se usa asignación directa)
        $vehiculoAsignacion = $repartidor->vehiculoAsignaciones()
            ->where('fecha_inicio', '<=', $fechaCarbon->endOfDay())
            ->where('fecha_fin', '>=', $fechaCarbon->startOfDay())
            ->where('estado', '!=', 'cancelado')
            ->with('vehiculo')
            ->latest()
            ->first();

        $this->diaSeleccionado = [
            'fecha_raw' => $fecha,
            'fecha' => $fechaCarbon->locale('es')->translatedFormat('l, d \d\e F \d\e Y'),
            'eventos' => $eventos->map(function($evento) {
                return [
                    'tipo' => $evento->tipo,
                    'descripcion' => $evento->descripcion,
                    'horario' => Carbon::parse($evento->fecha_inicio)->format('H:i') . ' - ' . Carbon::parse($evento->fecha_fin)->format('H:i'),
                    'vehiculo' => $evento->vehiculo ? [
                        'marca' => $evento->vehiculo->marca,
                        'modelo' => $evento->vehiculo->modelo,
                        'placa' => $evento->vehiculo->placa
                    ] : null
                ];
            })->toArray(),
            'vehiculo' => $vehiculoAsignacion ? $vehiculoAsignacion->vehiculo : null,
        ];
    }
    
    public function cerrarDetalle()
    {
        $this->diaSeleccionado = null;
    }

    public function render()
    {
        return view('livewire.repartidor.calendario', [
            'nombreMes' => Carbon::create($this->anio, $this->mes, 1)->locale('es')->translatedFormat('F Y')
        ]);
    }
}
