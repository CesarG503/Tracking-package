@extends('layouts.app')

@section('title', 'Disponibilidad')

@push('styles')
<style>
    .calendar-day {
        min-height: 120px;
        transition: all 0.2s ease;
    }
    .calendar-day:hover {
        background: rgba(59, 130, 246, 0.05);
    }
    .calendar-day.selected {
        background: rgba(59, 130, 246, 0.1);
        border: 2px solid var(--color-primary);
    }
    .calendar-day.other-month {
        opacity: 0.4;
    }
    .event-pill {
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 4px;
        margin-bottom: 2px;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .event-pill:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .repartidor-chip {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .repartidor-chip.selected {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .vehiculo-card {
        transition: all 0.2s ease;
    }
    .vehiculo-card:hover {
        transform: translateY(-2px);
    }
    .vehiculo-card.selected {
        border-color: var(--color-primary);
        background: rgba(59, 130, 246, 0.05);
    }
    .vehiculo-card.in-use {
        opacity: 0.6;
    }
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-20 glass-sidebar flex flex-col items-center py-6 gap-2">
        <!-- Logo -->
        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-hover rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-primary/30">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>

        <!-- Nav Items -->
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-foreground-muted hover:bg-surface-secondary hover:text-foreground' }} flex items-center justify-center transition-colors" title="Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </a>
            <a href="{{ route('disponibilidad.index') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('disponibilidad.*') ? 'bg-primary/10 text-primary' : 'text-foreground-muted hover:bg-surface-secondary hover:text-foreground' }} flex items-center justify-center transition-colors" title="Disponibilidad">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </a>
            <a href="{{ route('vehiculos.index') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('vehiculos.*') ? 'bg-primary/10 text-primary' : 'text-foreground-muted hover:bg-surface-secondary hover:text-foreground' }} flex items-center justify-center transition-colors" title="Vehiculos">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </a>
            <a href="{{ route('usuarios.index') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('usuarios.*') ? 'bg-primary/10 text-primary' : 'text-foreground-muted hover:bg-surface-secondary hover:text-foreground' }} flex items-center justify-center transition-colors" title="Usuarios">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </a>
        </nav>

        <!-- Bottom Nav -->
        <div class="flex flex-col gap-2">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="button" onclick="confirmLogout()" class="w-12 h-12 rounded-xl text-foreground-muted flex items-center justify-center hover:bg-danger-light hover:text-danger transition-colors" title="Cerrar Sesion">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex overflow-hidden">
        <!-- Left Panel - Repartidores y Vehiculos -->
        <div class="w-[320px] glass-sidebar border-r border-white/20 flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-border">
                <h1 class="text-xl font-bold text-foreground mb-2">Disponibilidad</h1>
                <p class="text-sm text-foreground-muted">Gestiona horarios y vehiculos</p>
            </div>

            <!-- Repartidores Section -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-semibold text-foreground mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Repartidores
                </h3>
                <div class="flex flex-wrap gap-2" id="repartidores-list">
                    @foreach($repartidores as $repartidor)
                    <div class="repartidor-chip px-3 py-2 rounded-lg text-white text-sm font-medium flex items-center gap-2" 
                         style="background-color: {{ $repartidor->color }};"
                         data-id="{{ $repartidor->id }}"
                         data-nombre="{{ $repartidor->nombre }}"
                         data-color="{{ $repartidor->color }}"
                         onclick="toggleRepartidor(this)">
                        <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs">
                            {{ substr($repartidor->nombre, 0, 1) }}
                        </span>
                        <span>{{ Str::limit($repartidor->nombre, 12) }}</span>
                        <svg class="w-4 h-4 hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @endforeach
                </div>
                @if($repartidores->isEmpty())
                <p class="text-sm text-foreground-muted text-center py-4">No hay repartidores activos</p>
                @endif
            </div>

            <!-- Vehiculos Section -->
            <div class="flex-1 overflow-y-auto p-4">
                <h3 class="text-sm font-semibold text-foreground mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    Vehiculos
                    <span class="ml-auto text-xs px-2 py-0.5 bg-success-light text-success rounded-full">
                        {{ $vehiculosDisponibles->count() }} disponibles
                    </span>
                </h3>
                <div class="space-y-2" id="vehiculos-list">
                    @foreach($vehiculos as $vehiculo)
                    @php $enUso = in_array($vehiculo->id, $vehiculosEnUso); @endphp
                    <div class="vehiculo-card glass-card rounded-xl p-3 {{ $enUso ? 'in-use' : '' }}" 
                         data-id="{{ $vehiculo->id }}"
                         data-placa="{{ $vehiculo->placa }}"
                         data-en-uso="{{ $enUso ? 'true' : 'false' }}"
                         onclick="toggleVehiculo(this)">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-secondary flex items-center justify-center">
                                <svg class="w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-foreground text-sm">{{ $vehiculo->placa }}</p>
                                <p class="text-xs text-foreground-muted">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
                            </div>
                            <div>
                                @if($enUso)
                                <span class="px-2 py-1 text-xs rounded-full bg-warning-light text-warning">En uso</span>
                                @else
                                <span class="px-2 py-1 text-xs rounded-full bg-success-light text-success">Libre</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($vehiculos->isEmpty())
                <p class="text-sm text-foreground-muted text-center py-4">No hay vehiculos registrados</p>
                @endif
            </div>

            <!-- Action Button -->
            <div class="p-4 border-t border-border">
                <button type="button" 
                        onclick="openCreateModal()"
                        class="w-full py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear Disponibilidad
                </button>
            </div>
        </div>

        <div class="flex-1 flex flex-col p-6 gap-4 overflow-hidden">
            <!-- Calendar Header -->
            <div class="glass-card rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button onclick="cambiarMes(-1)" class="w-10 h-10 rounded-xl bg-surface-secondary hover:bg-border flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <h2 class="text-xl font-bold text-foreground" id="mes-titulo">
                            {{ Carbon\Carbon::create($anio, $mes, 1)->locale('es')->translatedFormat('F Y') }}
                        </h2>
                        <button onclick="cambiarMes(1)" class="w-10 h-10 rounded-xl bg-surface-secondary hover:bg-border flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="irAHoy()" class="px-4 py-2 bg-surface-secondary hover:bg-border rounded-xl text-sm font-medium text-foreground transition-colors">
                            Hoy
                        </button>
                        <button onclick="seleccionarSemana()" class="px-4 py-2 bg-surface-secondary hover:bg-border rounded-xl text-sm font-medium text-foreground transition-colors">
                            Seleccionar Semana
                        </button>
                        <button onclick="limpiarSeleccion()" class="px-4 py-2 bg-surface-secondary hover:bg-border rounded-xl text-sm font-medium text-foreground transition-colors">
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="flex-1 glass-card rounded-2xl overflow-hidden flex flex-col">
                <!-- Days Header -->
                <div class="grid grid-cols-7 bg-surface-secondary">
                    @foreach(['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'] as $dia)
                    <div class="py-3 text-center text-sm font-semibold text-foreground-muted border-b border-border">
                        {{ $dia }}
                    </div>
                    @endforeach
                </div>

                <!-- Calendar Days -->
                <div class="flex-1 grid grid-cols-7 overflow-y-auto" id="calendar-grid">
                    @php
                        $primerDia = $fechaInicio->copy()->startOfMonth();
                        $ultimoDia = $fechaInicio->copy()->endOfMonth();
                        $inicioCalendario = $primerDia->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
                        $finCalendario = $ultimoDia->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
                        $diaActual = $inicioCalendario->copy();
                        $hoy = Carbon\Carbon::today();
                    @endphp

                    @while($diaActual <= $finCalendario)
                        @php
                            $esOtroMes = $diaActual->month != $mes;
                            $esHoy = $diaActual->isSameDay($hoy);
                            $fechaStr = $diaActual->format('Y-m-d');
                            $eventosDelDia = $disponibilidades->filter(function($d) use ($diaActual) {
                                return $diaActual->between($d->fecha_inicio->startOfDay(), $d->fecha_fin->endOfDay());
                            });
                        @endphp
                        <div class="calendar-day border-b border-r border-border p-2 {{ $esOtroMes ? 'other-month' : '' }}" 
                             data-fecha="{{ $fechaStr }}"
                             data-dia-semana="{{ $diaActual->dayOfWeek }}"
                             onclick="toggleDia(this, event)">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium {{ $esHoy ? 'w-7 h-7 rounded-full bg-primary text-white flex items-center justify-center' : ($esOtroMes ? 'text-foreground-muted' : 'text-foreground') }}">
                                    {{ $diaActual->day }}
                                </span>
                            </div>
                            <div class="space-y-1 max-h-20 overflow-y-auto">
                                @foreach($eventosDelDia->take(3) as $evento)
                                <div class="event-pill text-white" 
                                     style="background-color: {{ $evento->color }};"
                                     onclick="event.stopPropagation(); editarEvento({{ $evento->id }})"
                                     title="{{ $evento->repartidor->nombre ?? 'Sin asignar' }} - {{ $evento->fecha_inicio->format('H:i') }} a {{ $evento->fecha_fin->format('H:i') }}">
                                    {{ $evento->repartidor->nombre ?? 'N/A' }} {{ $evento->fecha_inicio->format('H:i') }}
                                </div>
                                @endforeach
                                @if($eventosDelDia->count() > 3)
                                <div class="text-xs text-foreground-muted text-center">+{{ $eventosDelDia->count() - 3 }} mas</div>
                                @endif
                            </div>
                        </div>
                        @php $diaActual->addDay(); @endphp
                    @endwhile
                </div>
            </div>

            <!-- Legend -->
            <div class="glass-card rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-foreground">Leyenda:</span>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-success"></span>
                            <span class="text-sm text-foreground-muted">Disponible</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-warning"></span>
                            <span class="text-sm text-foreground-muted">Ocupado</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="text-sm text-foreground-muted">Vacaciones</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-danger"></span>
                            <span class="text-sm text-foreground-muted">Bloqueado</span>
                        </div>
                    </div>
                    <div class="text-sm text-foreground-muted">
                        <span id="dias-seleccionados">0</span> dias seleccionados
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Crear/Editar Disponibilidad -->
<div id="modal-disponibilidad" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-border">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-foreground" id="modal-titulo">Crear Disponibilidad</h3>
                <button onclick="cerrarModal()" class="w-8 h-8 rounded-lg hover:bg-surface-secondary flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="form-disponibilidad" class="p-6 space-y-4">
            <input type="hidden" id="evento-id" value="">
            
            <!-- Repartidores seleccionados -->
            <div class="form-group">
                <label class="form-label">Repartidores Seleccionados</label>
                <div id="modal-repartidores" class="flex flex-wrap gap-2 p-3 bg-surface-secondary rounded-lg min-h-[44px]">
                    <span class="text-sm text-foreground-muted">Selecciona repartidores en el panel izquierdo</span>
                </div>
            </div>

            <!-- Dias seleccionados -->
            <div class="form-group">
                <label class="form-label">Dias Seleccionados</label>
                <div id="modal-fechas" class="flex flex-wrap gap-2 p-3 bg-surface-secondary rounded-lg min-h-[44px]">
                    <span class="text-sm text-foreground-muted">Selecciona dias en el calendario</span>
                </div>
            </div>

            <!-- Horario -->
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="hora_inicio">Hora Inicio</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" value="08:00" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="hora_fin">Hora Fin</label>
                    <input type="time" id="hora_fin" name="hora_fin" class="form-control" value="18:00" required>
                </div>
            </div>

            <!-- Vehiculo -->
            <div class="form-group">
                <label class="form-label">Vehiculo Asignado</label>
                <div id="modal-vehiculo" class="p-3 bg-surface-secondary rounded-lg">
                    <span class="text-sm text-foreground-muted">Opcional: selecciona un vehiculo en el panel izquierdo</span>
                </div>
            </div>

            <!-- Tipo -->
            <div class="form-group">
                <label class="form-label" for="tipo">Tipo de Disponibilidad</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="disponible">Disponible</option>
                    <option value="ocupado">Ocupado</option>
                    <option value="vacaciones">Vacaciones</option>
                    <option value="bloqueo">Bloqueado</option>
                </select>
            </div>

            <!-- Descripcion -->
            <div class="form-group">
                <label class="form-label" for="descripcion">Descripcion (opcional)</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="2" placeholder="Notas adicionales..."></textarea>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="cerrarModal()" class="flex-1 py-3 bg-surface-secondary hover:bg-border text-foreground rounded-xl font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Evento Individual -->
<div id="modal-editar" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-border">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-foreground">Editar Disponibilidad</h3>
                <button onclick="cerrarModalEditar()" class="w-8 h-8 rounded-lg hover:bg-surface-secondary flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="form-editar" class="p-6 space-y-4">
            <input type="hidden" id="editar-id" value="">
            
            <div class="form-group">
                <label class="form-label">Repartidor</label>
                <p id="editar-repartidor" class="text-foreground font-medium"></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label" for="editar-fecha-inicio">Fecha/Hora Inicio</label>
                    <input type="datetime-local" id="editar-fecha-inicio" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="editar-fecha-fin">Fecha/Hora Fin</label>
                    <input type="datetime-local" id="editar-fecha-fin" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="editar-vehiculo">Vehiculo</label>
                <select id="editar-vehiculo" class="form-control">
                    <option value="">Sin vehiculo</option>
                    @foreach($vehiculos as $vehiculo)
                    <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="editar-tipo">Tipo</label>
                <select id="editar-tipo" class="form-control" required>
                    <option value="disponible">Disponible</option>
                    <option value="ocupado">Ocupado</option>
                    <option value="vacaciones">Vacaciones</option>
                    <option value="bloqueo">Bloqueado</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="editar-descripcion">Descripcion</label>
                <textarea id="editar-descripcion" class="form-control" rows="2"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="eliminarEvento()" class="py-3 px-4 bg-danger-light hover:bg-danger text-danger hover:text-white rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                <button type="button" onclick="cerrarModalEditar()" class="flex-1 py-3 bg-surface-secondary hover:bg-border text-foreground rounded-xl font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Estado global
    let mesActual = {{ $mes }};
    let anioActual = {{ $anio }};
    let repartidoresSeleccionados = [];
    let diasSeleccionados = [];
    let vehiculoSeleccionado = null;
    let eventosData = @json($disponibilidades);

    // Toggle repartidor
    function toggleRepartidor(element) {
        const id = parseInt(element.dataset.id);
        const nombre = element.dataset.nombre;
        const color = element.dataset.color;
        
        const index = repartidoresSeleccionados.findIndex(r => r.id === id);
        
        if (index === -1) {
            repartidoresSeleccionados.push({ id, nombre, color });
            element.classList.add('selected');
            element.querySelector('.check-icon').classList.remove('hidden');
        } else {
            repartidoresSeleccionados.splice(index, 1);
            element.classList.remove('selected');
            element.querySelector('.check-icon').classList.add('hidden');
        }
        
        actualizarModalRepartidores();
    }

    // Toggle vehiculo
    function toggleVehiculo(element) {
        const id = parseInt(element.dataset.id);
        const placa = element.dataset.placa;
        const enUso = element.dataset.enUso === 'true';
        
        // Quitar seleccion anterior
        document.querySelectorAll('.vehiculo-card.selected').forEach(el => {
            el.classList.remove('selected');
        });
        
        if (vehiculoSeleccionado && vehiculoSeleccionado.id === id) {
            vehiculoSeleccionado = null;
        } else {
            vehiculoSeleccionado = { id, placa, enUso };
            element.classList.add('selected');
        }
        
        actualizarModalVehiculo();
    }

    // Toggle dia en calendario
    function toggleDia(element, event) {
        if (event.target.classList.contains('event-pill')) return;
        
        const fecha = element.dataset.fecha;
        const index = diasSeleccionados.indexOf(fecha);
        
        if (index === -1) {
            diasSeleccionados.push(fecha);
            element.classList.add('selected');
        } else {
            diasSeleccionados.splice(index, 1);
            element.classList.remove('selected');
        }
        
        document.getElementById('dias-seleccionados').textContent = diasSeleccionados.length;
        actualizarModalFechas();
    }

    // Seleccionar semana completa
    function seleccionarSemana() {
        const hoy = new Date();
        const diaSemana = hoy.getDay();
        const inicioSemana = new Date(hoy);
        inicioSemana.setDate(hoy.getDate() - diaSemana);
        
        diasSeleccionados = [];
        document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
        
        for (let i = 0; i < 7; i++) {
            const fecha = new Date(inicioSemana);
            fecha.setDate(inicioSemana.getDate() + i);
            const fechaStr = fecha.toISOString().split('T')[0];
            
            const elemento = document.querySelector(`[data-fecha="${fechaStr}"]`);
            if (elemento) {
                diasSeleccionados.push(fechaStr);
                elemento.classList.add('selected');
            }
        }
        
        document.getElementById('dias-seleccionados').textContent = diasSeleccionados.length;
        actualizarModalFechas();
    }

    // Limpiar seleccion
    function limpiarSeleccion() {
        diasSeleccionados = [];
        repartidoresSeleccionados = [];
        vehiculoSeleccionado = null;
        
        document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
        document.querySelectorAll('.repartidor-chip.selected').forEach(el => {
            el.classList.remove('selected');
            el.querySelector('.check-icon').classList.add('hidden');
        });
        document.querySelectorAll('.vehiculo-card.selected').forEach(el => el.classList.remove('selected'));
        
        document.getElementById('dias-seleccionados').textContent = '0';
        actualizarModalRepartidores();
        actualizarModalFechas();
        actualizarModalVehiculo();
    }

    // Actualizar modal con repartidores
    function actualizarModalRepartidores() {
        const container = document.getElementById('modal-repartidores');
        if (repartidoresSeleccionados.length === 0) {
            container.innerHTML = '<span class="text-sm text-foreground-muted">Selecciona repartidores en el panel izquierdo</span>';
        } else {
            container.innerHTML = repartidoresSeleccionados.map(r => 
                `<span class="px-2 py-1 rounded-lg text-white text-sm" style="background-color: ${r.color}">${r.nombre}</span>`
            ).join('');
        }
    }

    // Actualizar modal con fechas
    function actualizarModalFechas() {
        const container = document.getElementById('modal-fechas');
        if (diasSeleccionados.length === 0) {
            container.innerHTML = '<span class="text-sm text-foreground-muted">Selecciona dias en el calendario</span>';
        } else {
            const fechasOrdenadas = [...diasSeleccionados].sort();
            container.innerHTML = fechasOrdenadas.map(f => {
                const fecha = new Date(f + 'T12:00:00');
                return `<span class="px-2 py-1 bg-primary/10 text-primary rounded-lg text-sm">${fecha.toLocaleDateString('es', { day: 'numeric', month: 'short' })}</span>`;
            }).join('');
        }
    }

    // Actualizar modal con vehiculo
    function actualizarModalVehiculo() {
        const container = document.getElementById('modal-vehiculo');
        if (!vehiculoSeleccionado) {
            container.innerHTML = '<span class="text-sm text-foreground-muted">Opcional: selecciona un vehiculo en el panel izquierdo</span>';
        } else {
            const clase = vehiculoSeleccionado.enUso ? 'bg-warning-light text-warning' : 'bg-success-light text-success';
            container.innerHTML = `<span class="px-3 py-1.5 ${clase} rounded-lg text-sm font-medium">${vehiculoSeleccionado.placa}</span>`;
        }
    }

    // Abrir modal crear
    function openCreateModal() {
        if (repartidoresSeleccionados.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona repartidores',
                text: 'Debes seleccionar al menos un repartidor antes de crear disponibilidad',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        if (diasSeleccionados.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona dias',
                text: 'Debes seleccionar al menos un dia en el calendario',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        document.getElementById('modal-titulo').textContent = 'Crear Disponibilidad';
        document.getElementById('evento-id').value = '';
        actualizarModalRepartidores();
        actualizarModalFechas();
        actualizarModalVehiculo();
        document.getElementById('modal-disponibilidad').classList.remove('hidden');
    }

    // Cerrar modal
    function cerrarModal() {
        document.getElementById('modal-disponibilidad').classList.add('hidden');
    }

    // Cambiar mes
    function cambiarMes(delta) {
        mesActual += delta;
        if (mesActual > 12) {
            mesActual = 1;
            anioActual++;
        } else if (mesActual < 1) {
            mesActual = 12;
            anioActual--;
        }
        
        window.location.href = `{{ route('disponibilidad.index') }}?mes=${mesActual}&anio=${anioActual}`;
    }

    // Ir a hoy
    function irAHoy() {
        const hoy = new Date();
        window.location.href = `{{ route('disponibilidad.index') }}?mes=${hoy.getMonth() + 1}&anio=${hoy.getFullYear()}`;
    }

    // Editar evento existente
    function editarEvento(id) {
        const evento = eventosData.find(e => e.id === id);
        if (!evento) return;
        
        document.getElementById('editar-id').value = evento.id;
        document.getElementById('editar-repartidor').textContent = evento.repartidor?.nombre || 'Sin asignar';
        document.getElementById('editar-fecha-inicio').value = evento.fecha_inicio.replace(' ', 'T').substring(0, 16);
        document.getElementById('editar-fecha-fin').value = evento.fecha_fin.replace(' ', 'T').substring(0, 16);
        document.getElementById('editar-vehiculo').value = evento.vehiculo_id || '';
        document.getElementById('editar-tipo').value = evento.tipo;
        document.getElementById('editar-descripcion').value = evento.descripcion || '';
        
        document.getElementById('modal-editar').classList.remove('hidden');
    }

    // Cerrar modal editar
    function cerrarModalEditar() {
        document.getElementById('modal-editar').classList.add('hidden');
    }

    // Eliminar evento
    function eliminarEvento() {
        const id = document.getElementById('editar-id').value;
        
        Swal.fire({
            title: 'Eliminar disponibilidad',
            text: 'Esta accion no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/disponibilidad/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar la disponibilidad'
                    });
                });
            }
        });
    }

    // Submit form crear
    document.getElementById('form-disponibilidad').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const data = {
            repartidor_ids: repartidoresSeleccionados.map(r => r.id),
            vehiculo_id: vehiculoSeleccionado?.id || null,
            fechas: diasSeleccionados,
            hora_inicio: document.getElementById('hora_inicio').value,
            hora_fin: document.getElementById('hora_fin').value,
            tipo: document.getElementById('tipo').value,
            descripcion: document.getElementById('descripcion').value
        };
        
        fetch('{{ route("disponibilidad.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo crear la disponibilidad'
            });
        });
    });

    // Submit form editar
    document.getElementById('form-editar').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('editar-id').value;
        const data = {
            vehiculo_id: document.getElementById('editar-vehiculo').value || null,
            fecha_inicio: document.getElementById('editar-fecha-inicio').value,
            fecha_fin: document.getElementById('editar-fecha-fin').value,
            tipo: document.getElementById('editar-tipo').value,
            descripcion: document.getElementById('editar-descripcion').value
        };
        
        fetch(`/disponibilidad/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo actualizar la disponibilidad'
            });
        });
    });

    // Logout confirmation
    function confirmLogout() {
        Swal.fire({
            title: 'Cerrar Sesion',
            text: 'Estas seguro que deseas cerrar sesion?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Si, cerrar sesion',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush
