<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\VehiculoAsignacion;
use App\Models\Disponibilidad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EnvioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('envios.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('envios.create');
    }

    /**
     * Get available delivery personnel and vehicles for a specific date
     */
    public function getAvailableResources(Request $request)
    {
        $fecha = $request->input('fecha');
        $hora = $request->input('hora', '08:00'); // Hora solo para verificar disponibilidad
        
        if (!$fecha) {
            return response()->json([
                'recursos' => []
            ]);
        }

        // Combinar fecha y hora para verificar disponibilidad
        $fechaHora = $fecha . ' ' . $hora;
        $fechaHoraCarbon = \Carbon\Carbon::parse($fechaHora);

        // Debug: Log para verificar qué fecha y hora se está usando para disponibilidad
        Log::info('Buscando recursos para fecha: ' . $fecha . ' a las ' . $hora);

        // Obtener todas las asignaciones activas (sin filtro de fecha primero)
        $todasAsignaciones = VehiculoAsignacion::with(['vehiculo', 'repartidor'])
            ->where('estado', 'activo')
            ->get();

        //Log::info('Total asignaciones activas: ' . $todasAsignaciones->count());

        // Filtrar asignaciones que están vigentes en la fecha y hora solicitada
        $asignacionesDisponibles = $todasAsignaciones->filter(function ($asignacion) use ($fechaHoraCarbon) {
            // La asignación debe estar activa en la fecha y hora solicitada
            $fechaInicio = $asignacion->fecha_inicio;
            $fechaFin = $asignacion->fecha_fin;
            
            // Verificar que la fecha/hora solicitada esté dentro del rango de la asignación
            $enRango = $fechaHoraCarbon >= $fechaInicio && 
                      ($fechaFin === null || $fechaHoraCarbon <= $fechaFin);
            
            if ($enRango) {
                //Log::info("Asignación ID {$asignacion->id} disponible: {$asignacion->repartidor->name} - {$asignacion->vehiculo->marca}");
            }
            
            return $enRango;
        });

        //Log::info('Asignaciones en rango de fecha: ' . $asignacionesDisponibles->count());

        // Filtrar las que no están ocupadas con envíos en la misma fecha
        $recursosDisponibles = collect();

        foreach ($asignacionesDisponibles as $asignacion) {
            // Verificar si está ocupado con envíos pendientes/en ruta en la fecha
            $ocupado = Envio::where('vehiculo_asignacion_id', $asignacion->id)
                ->whereDate('fecha_estimada', $fecha)
                ->whereIn('estado', ['pendiente', 'en_ruta']) // Solo estados que ocupan el recurso
                ->exists();

            if (!$ocupado) {
                $recursosDisponibles->push([
                    'tipo' => 'asignacion',
                    'id' => $asignacion->id,
                    'repartidor_id' => $asignacion->repartidor_id,
                    'vehiculo_id' => $asignacion->vehiculo_id,
                    'repartidor_name' => $asignacion->repartidor->nombre,
                    'vehiculo_info' => $asignacion->vehiculo->marca . ' ' . $asignacion->vehiculo->modelo . ' (' . $asignacion->vehiculo->placa . ')',
                    'descripcion' => $asignacion->repartidor->nombre . ' - ' . $asignacion->vehiculo->marca . ' ' . $asignacion->vehiculo->modelo . ' (' . $asignacion->vehiculo->placa . ')'
                ]);
                
                //Log::info("Recurso disponible: {$asignacion->repartidor->name} - {$asignacion->vehiculo->marca}");
            } else {
                //Log::info("Recurso ocupado: {$asignacion->repartidor->name} - {$asignacion->vehiculo->marca}");
            }
        }

        //Log::info('Total recursos disponibles: ' . $recursosDisponibles->count());

        return response()->json([
            'recursos' => $recursosDisponibles->values(),
            'debug' => [
                'fecha_solicitada' => $fecha,
                'hora_para_disponibilidad' => $hora,
                'total_asignaciones_activas' => $todasAsignaciones->count(),
                'asignaciones_disponibles_fecha_hora' => $asignacionesDisponibles->count(),
                'recursos_disponibles' => $recursosDisponibles->count()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'remitente_nombre' => 'required|string|max:255',
            'remitente_telefono' => 'nullable|string|max:20',
            'remitente_direccion' => 'required|string|max:500',
            'destinatario_nombre' => 'required|string|max:255',
            'destinatario_email' => 'required|email|max:255',
            'destinatario_telefono' => 'nullable|string|max:20',
            'destinatario_direccion' => 'required|string|max:500',
            'descripcion' => 'nullable|string|max:1000',
            'peso' => 'nullable|numeric|min:0',
            'tipo_envio' => 'required|in:express,normal,economico',
            'fecha_estimada' => 'required|date|after_or_equal:today',
            'vehiculo_asignacion_id' => 'nullable|exists:vehiculo_asignaciones,id',
            'foto_paquete' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Generar código único
        $validated['codigo'] = 'ENV-' . strtoupper(Str::random(8));
        $validated['estado'] = 'pendiente';
        $validated['fecha_creacion'] = now();

        // Obtener el repartidor desde la asignación de vehículo
        if ($validated['vehiculo_asignacion_id']) {
            $asignacion = VehiculoAsignacion::find($validated['vehiculo_asignacion_id']);
            if ($asignacion) {
                $validated['repartidor_id'] = $asignacion->repartidor_id;
            }
        }

        // Manejar la imagen
        if ($request->hasFile('foto_paquete')) {
            $path = $request->file('foto_paquete')->store('envios', 'public');
            $validated['foto_paquete'] = $path;
        }

        Envio::create($validated);

        return redirect()->route('envios.index')
            ->with('success', 'Envío creado exitosamente con código: ' . $validated['codigo']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Envio $envio)
    {
        $envio->load(['repartidor', 'vehiculoAsignacion.vehiculo']);
        return view('envios.show', compact('envio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Envio $envio)
    {
        $envio->load(['vehiculoAsignacion.vehiculo', 'vehiculoAsignacion.repartidor']);
        return view('envios.edit', compact('envio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Envio $envio)
    {
        $validated = $request->validate([
            'remitente_nombre' => 'required|string|max:255',
            'remitente_telefono' => 'nullable|string|max:20',
            'remitente_direccion' => 'required|string|max:500',
            'destinatario_nombre' => 'required|string|max:255',
            'destinatario_email' => 'required|email|max:255',
            'destinatario_telefono' => 'nullable|string|max:20',
            'destinatario_direccion' => 'required|string|max:500',
            'descripcion' => 'nullable|string|max:1000',
            'peso' => 'nullable|numeric|min:0',
            'tipo_envio' => 'required|in:express,normal,economico',
            'estado' => 'required|in:pendiente,en_ruta,entregado,devuelto,cancelado',
            'fecha_estimada' => 'required|date',
            'vehiculo_asignacion_id' => 'nullable|exists:vehiculo_asignaciones,id',
            'foto_paquete' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Obtener el repartidor desde la asignación de vehículo
        if ($validated['vehiculo_asignacion_id']) {
            $asignacion = VehiculoAsignacion::find($validated['vehiculo_asignacion_id']);
            if ($asignacion) {
                $validated['repartidor_id'] = $asignacion->repartidor_id;
            }
        } else {
            $validated['repartidor_id'] = null;
        }

        // Manejar la imagen
        if ($request->hasFile('foto_paquete')) {
            // Eliminar imagen anterior si existe
            if ($envio->foto_paquete) {
                Storage::disk('public')->delete($envio->foto_paquete);
            }
            $path = $request->file('foto_paquete')->store('envios', 'public');
            $validated['foto_paquete'] = $path;
        }

        $envio->update($validated);

        return redirect()->route('envios.index')
            ->with('success', 'Envío actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Envio $envio)
    {
        // Eliminar imagen si existe
        if ($envio->foto_paquete) {
            Storage::disk('public')->delete($envio->foto_paquete);
        }

        $envio->delete();

        return redirect()->route('envios.index')
            ->with('success', 'Envío eliminado exitosamente.');
    }
}
