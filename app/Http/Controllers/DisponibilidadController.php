<?php

namespace App\Http\Controllers;

use App\Models\Disponibilidad;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DisponibilidadController extends Controller
{
    // Colores predefinidos para los empleados
    private $coloresEmpleados = [
        '#3b82f6', // blue
        '#10b981', // emerald
        '#f59e0b', // amber
        '#ef4444', // red
        '#8b5cf6', // violet
        '#ec4899', // pink
        '#06b6d4', // cyan
        '#f97316', // orange
        '#84cc16', // lime
        '#6366f1', // indigo
    ];

    public function index(Request $request)
    {
        $mes = $request->get('mes', Carbon::now()->month);
        $anio = $request->get('anio', Carbon::now()->year);
        
        $fechaInicio = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
        
        // Obtener repartidores activos con un color asignado
        $repartidores = User::where('rol', 'repartidor')
            ->where('activo', true)
            ->get()
            ->map(function($repartidor, $index) {
                $repartidor->color = $this->coloresEmpleados[$index % count($this->coloresEmpleados)];
                return $repartidor;
            });
        
        // Obtener vehículos
        $vehiculos = Vehiculo::all();
        
        // Obtener disponibilidades del mes
        $disponibilidades = Disponibilidad::with(['repartidor', 'vehiculo'])
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                    ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                    ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                        $q->where('fecha_inicio', '<=', $fechaInicio)
                          ->where('fecha_fin', '>=', $fechaFin);
                    });
            })
            ->orderBy('fecha_inicio')
            ->get()
            ->map(function($disp) use ($repartidores) {
                // Asignar el color del repartidor a la disponibilidad
                $repartidor = $repartidores->firstWhere('id', $disp->repartidor_id);
                $disp->color = $repartidor ? $repartidor->color : '#6b7280';
                return $disp;
            });
        
        // Calcular estadísticas de vehículos
        $vehiculosEnUso = Disponibilidad::whereNotNull('vehiculo_id')
            ->where('fecha_inicio', '<=', Carbon::now())
            ->where('fecha_fin', '>=', Carbon::now())
            ->where('tipo', 'disponible')
            ->distinct('vehiculo_id')
            ->pluck('vehiculo_id')
            ->toArray();
        
        $vehiculosDisponibles = $vehiculos->whereNotIn('id', $vehiculosEnUso);
        
        return view('disponibilidad.index', compact(
            'repartidores', 
            'vehiculos', 
            'disponibilidades', 
            'mes', 
            'anio',
            'fechaInicio',
            'fechaFin',
            'vehiculosEnUso',
            'vehiculosDisponibles'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'repartidor_ids' => 'required|array|min:1',
            'repartidor_ids.*' => 'exists:users,id',
            'vehiculo_id' => 'nullable|exists:vehiculos,id',
            'fechas' => 'required|array|min:1',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo' => 'required|in:disponible,ocupado,vacaciones,bloqueo',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $creados = [];
        
        foreach ($request->repartidor_ids as $repartidorId) {
            foreach ($request->fechas as $fecha) {
                $fechaCarbon = Carbon::parse($fecha);
                $inicio = $fechaCarbon->copy()->setTimeFromTimeString($request->hora_inicio);
                $fin = $fechaCarbon->copy()->setTimeFromTimeString($request->hora_fin);

                // Validar disponibilidad del repartidor
                $repartidorOcupado = Disponibilidad::where('repartidor_id', $repartidorId)
                    ->where(function($query) use ($inicio, $fin) {
                        $query->whereBetween('fecha_inicio', [$inicio, $fin])
                            ->orWhereBetween('fecha_fin', [$inicio, $fin])
                            ->orWhere(function($q) use ($inicio, $fin) {
                                $q->where('fecha_inicio', '<=', $inicio)
                                  ->where('fecha_fin', '>=', $fin);
                            });
                    })
                    ->exists();

                if ($repartidorOcupado) {
                    $repartidor = User::find($repartidorId);
                    return response()->json([
                        'success' => false,
                        'message' => "El repartidor {$repartidor->nombre} ya tiene una asignación el {$fecha} en este horario."
                    ], 422);
                }

                // Validar disponibilidad del vehículo (si se seleccionó uno)
                if ($request->vehiculo_id) {
                    $vehiculoOcupado = Disponibilidad::where('vehiculo_id', $request->vehiculo_id)
                        ->where(function($query) use ($inicio, $fin) {
                            $query->whereBetween('fecha_inicio', [$inicio, $fin])
                                ->orWhereBetween('fecha_fin', [$inicio, $fin])
                                ->orWhere(function($q) use ($inicio, $fin) {
                                    $q->where('fecha_inicio', '<=', $inicio)
                                      ->where('fecha_fin', '>=', $fin);
                                });
                        })
                        ->exists();

                    if ($vehiculoOcupado) {
                        $vehiculo = Vehiculo::find($request->vehiculo_id);
                        return response()->json([
                            'success' => false,
                            'message' => "El vehículo {$vehiculo->placa} ya está en uso el {$fecha} en este horario."
                        ], 422);
                    }
                }
                
                $disponibilidad = Disponibilidad::create([
                    'repartidor_id' => $repartidorId,
                    'vehiculo_id' => $request->vehiculo_id,
                    'fecha_inicio' => $inicio,
                    'fecha_fin' => $fin,
                    'tipo' => $request->tipo,
                    'descripcion' => $request->descripcion,
                ]);
                
                $creados[] = $disponibilidad->id;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Disponibilidad creada exitosamente',
            'ids' => $creados
        ]);
    }

    public function update(Request $request, Disponibilidad $disponibilidad)
    {
        $request->validate([
            'vehiculo_id' => 'nullable|exists:vehiculos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'tipo' => 'required|in:disponible,ocupado,vacaciones,bloqueo',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $disponibilidad->update([
            'vehiculo_id' => $request->vehiculo_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Disponibilidad actualizada exitosamente'
        ]);
    }

    public function destroy(Disponibilidad $disponibilidad)
    {
        $disponibilidad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disponibilidad eliminada exitosamente'
        ]);
    }

    public function getEventos(Request $request)
    {
        $mes = $request->get('mes', Carbon::now()->month);
        $anio = $request->get('anio', Carbon::now()->year);
        
        $fechaInicio = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
        
        $colores = $this->coloresEmpleados;
        
        $repartidores = User::where('rol', 'repartidor')
            ->where('activo', true)
            ->get()
            ->mapWithKeys(function($rep, $index) use ($colores) {
                return [$rep->id => $colores[$index % count($colores)]];
            });
        
        $disponibilidades = Disponibilidad::with(['repartidor', 'vehiculo'])
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                    ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin]);
            })
            ->get()
            ->map(function($disp) use ($repartidores) {
                return [
                    'id' => $disp->id,
                    'repartidor_id' => $disp->repartidor_id,
                    'repartidor_nombre' => $disp->repartidor->nombre ?? 'Sin asignar',
                    'vehiculo_id' => $disp->vehiculo_id,
                    'vehiculo_placa' => $disp->vehiculo->placa ?? null,
                    'fecha_inicio' => $disp->fecha_inicio->format('Y-m-d H:i'),
                    'fecha_fin' => $disp->fecha_fin->format('Y-m-d H:i'),
                    'tipo' => $disp->tipo,
                    'descripcion' => $disp->descripcion,
                    'color' => $repartidores[$disp->repartidor_id] ?? '#6b7280',
                ];
            });

        return response()->json($disponibilidades);
    }
}
