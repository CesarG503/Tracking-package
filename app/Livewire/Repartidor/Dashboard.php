<?php

namespace App\Livewire\Repartidor;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    #[Computed]
    public $empresaCoordenadas = [
            'lat' => 13.439624,
            'lng' => -88.157400
        ];
        
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

    // Vehículo(s) asignado(s) para HOY
    #[Computed]
    public function vehiculosHoy()
    {
        $repartidor = Auth::user();
        $hoy = now();

        $disponibilidadHoy = $repartidor->vehiculoAsignaciones()
            ->where('estado', 'activo')
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->get();

        return $disponibilidadHoy;
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
                'fecha' => $fecha,
                'dia_nombre' => $fecha->locale('es')->translatedFormat('D'),
                'dia_numero' => $fecha->format('d'),
                'tipo' => $evento ? $evento->tipo : 'disponible',
                'descripcion' => $evento ? $evento->descripcion : null,
                'es_hoy' => $fecha->isToday()
            ];
        }

        return $dias;
    }


    // LOGICA MAPA

    public $notificaciones = 0;
    public $ultimaNotificacion = null;

    // Obtener envíos con coordenadas para el mapa
    #[Computed]
    public function enviosEnMapa()
    {
        $repartidor = Auth::user();
        $hoy = now()->startOfDay();
        
        return $repartidor->envios()
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
                ];
            })
            ->filter(function($envio) {
                // Solo incluir envíos con coordenadas válidas
                return !is_null($envio['lat']) && !is_null($envio['lng']);
            })
            ->values()
            ->toArray();
    }

    // Escuchar cuando se asigna un nuevo envío
    #[On('echo-private:repartidor.{userId},envio.actualizado')]
    public function onEnvioActualizado($event)
    {
        // Obtener el envío actualizado
        $envio = \App\Models\Envio::find($event['envio_id']);
        
        if ($envio && $envio->lat && $envio->lng) {
            // Enviar evento al frontend para actualizar el mapa
            $this->dispatch('actualizar-marcador-mapa', [
                'id' => $envio->id,
                'lat' => $envio->lat,
                'lng' => $envio->lng,
                'estado' => $envio->estado,
                'destinatario' => $envio->destinatario_nombre,
                'direccion' => $envio->destinatario_direccion,
                'telefono' => $envio->destinatario_telefono,
                'accion' => $event['tipo'] // 'nuevo', 'actualizado', 'estado_cambiado'
            ]);
        }
        
        // Refrescar stats
        unset($this->statsHoy);
        unset($this->actividadReciente);
        unset($this->enviosEnMapa);
        
        $this->notificaciones++;
        $this->ultimaNotificacion = $event;
    }

    // Método para cuando se elimina o cancela un envío
    public function eliminarMarcador($envioId)
    {
        $this->dispatch('eliminar-marcador-mapa', ['id' => $envioId]);
    }

    public function getListeners()
    {
        $userId = Auth::id();
        return [
            "echo-private:repartidor.{$userId},envio.actualizado" => 'onEnvioActualizado',
        ];
    }

    public function render()
    {
        return view('livewire.repartidor.dashboard');
    }
}