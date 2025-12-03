<div>
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Header con Notificaciones --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-foreground">Bienvenido, {{ auth()->user()->nombre }}</h1>
                        <p class="text-foreground-muted mt-1">{{ \Carbon\Carbon::now()->locale('es')->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="glass glass-strong px-4 py-2.5 rounded-xl text-foreground font-medium flex items-center gap-2 hover:shadow-md transition-all">
                        {{-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg> --}}
                        DEJO ESTO POR SI SE QUIERE AGREGAR ALGO MAS (button)
                    </button>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4" wire:poll.5s>
                {{-- Envíos Asignados --}}
                <div class="glass-card rounded-2xl p-4 lg:p-6 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl glass glass-blue glass-static flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">{{ $this->statsHoy['asignados'] }}</p>
                    <p class="text-sm text-foreground">Envíos Asignados</p>
                </div>
                
                {{-- Entregados Hoy --}}
                <div class="glass-card rounded-2xl p-4 lg:p-6 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl glass glass-green flex items-center justify-center">
                            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">{{ $this->statsHoy['entregados_hoy'] }}</p>
                    <p class="text-sm text-foreground">Entregados Hoy</p>
                </div>

                {{-- Devueltos --}}
                <div class="glass-card rounded-2xl p-4 lg:p-6 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl glass glass-amber flex items-center justify-center">
                            <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">{{ $this->statsHoy['devueltos'] }}</p>
                    <p class="text-sm text-foreground">Devueltos</p>
                </div>

                {{-- Cancelados --}}
                <div class="glass-card rounded-2xl p-4 lg:p-6 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl glass glass-red glass-static flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">{{ $this->statsHoy['cancelados'] }}</p>
                    <p class="text-sm text-foreground">Cancelados</p>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Mapa con Rutas --}}
                    <div class="glass-card glass-strong rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Rutas de Hoy
                                <span class="glass glass-blue glass-subtle px-2 py-0.5 rounded text-xs font-semibold">
                                    {{ count($this->enviosEnMapa) }} puntos
                                </span>
                            </h2>
                            <button wire:click="$refresh" class="glass glass-strong text-foreground px-3 py-1.5 rounded-lg text-sm font-medium hover:shadow-md transition-all">
                                Actualizar mapa
                            </button>
                        </div>
                        
                        {{-- Mapa Contenedor con wire:ignore --}}
                    <div class="relative w-full h-64 lg:h-96 rounded-xl overflow-hidden" 
                         wire:ignore 
                         x-data="{ 
                            envios: @js($this->enviosEnMapa)
                         }"
                         x-init="
                            // Cargar marcadores iniciales cuando el componente se monta
                            $nextTick(() => {
                                if (typeof cargarMarcadoresIniciales === 'function') {
                                    cargarMarcadoresIniciales(envios);
                                }
                            });
                         ">
                        <div id="ruta-map" class="w-full h-full rounded-xl"></div>
                        
                        {{-- Controles del mapa --}}
                        <div class="absolute top-3 right-3 flex gap-2 z-[1000]">
                            <button onclick="centerMap()" class="w-10 h-10 glass-advanced rounded-xl flex items-center justify-center text-foreground hover:bg-surface transition-colors shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                            <button onclick="ajustarVistaATodosLosMarcadores()" class="w-10 h-10 glass-advanced rounded-xl flex items-center justify-center text-foreground hover:bg-surface transition-colors shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Stats del mapa --}}
                        <div class="absolute bottom-3 left-3 right-3 glass-advanced rounded-lg p-3">
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <p class="text-xs text-foreground-muted">Pendientes</p>
                                    <p class="text-lg font-bold text-warning" id="map-stats-pendientes">0</p>
                                </div>
                                <div>
                                    <p class="text-xs text-foreground-muted">En Ruta</p>
                                    <p class="text-lg font-bold text-primary" id="map-stats-enruta">0</p>
                                </div>
                                <div>
                                    <p class="text-xs text-foreground-muted">Total</p>
                                    <p class="text-lg font-bold text-foreground" id="map-stats-total">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    {{-- Historial Rápido --}}
                    <div class="glass glass-strong rounded-2xl p-6" wire:poll.5s>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Actividad Reciente
                            </h2>
                            <a href="{{ route('mis-envios') }}" class="glass glass-subtle px-3 py-1.5 rounded-lg text-sm font-medium hover:shadow-md transition-all">
                                Ver todo
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($this->actividadReciente as $envio)
                            <div class="flex items-start gap-3 p-3 glass glass-subtle rounded-xl hover:shadow-md transition-all">
                                <div class="w-10 h-10 rounded-lg glass 
                                    {{ $envio->estado === 'entregado' ? 'glass-green' : '' }}
                                    {{ $envio->estado === 'en_ruta' ? 'glass-blue' : '' }}
                                    {{ $envio->estado === 'pendiente' ? 'glass-amber' : '' }}
                                    {{ $envio->estado === 'devuelto' ? 'glass-red' : '' }}
                                    flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-foreground">Orden ID #{{ $envio->codigo }}</p>
                                    <p class="text-sm text-foreground-muted truncate">{{ $envio->destinatario_direccion }}</p>
                                    <p class="text-xs text-foreground-muted mt-1">{{ $envio->updated_at->diffForHumans() }}</p>
                                </div>
                                <span class="glass glass-subtle px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap
                                    {{ $envio->estado === 'entregado' ? 'text-success' : '' }}
                                    {{ $envio->estado === 'en_ruta' ? 'text-primary' : '' }}
                                    {{ $envio->estado === 'pendiente' ? 'text-warning' : '' }}
                                    {{ $envio->estado === 'devuelto' ? 'text-danger' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $envio->estado)) }}
                                </span>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-xl glass glass-subtle flex items-center justify-center">
                                    <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-foreground-muted">No hay actividad reciente</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    
                    {{-- Mis Vehículos --}}
                    <div class="glass glass-strong rounded-2xl p-6" wire:poll.5s>
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Mis Vehículos (Esta Semana)
                        </h2>

                        <div class="flex flex-col gap-6">
                            @forelse($this->vehiculosSemana as $item)
                                @if(isset($item->vehiculo))
                                    <div class="flex flex-col gap-3">
                                        <div class="glass glass-subtle rounded-xl p-4">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-12 h-12 rounded-xl glass glass-blue flex items-center justify-center">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-foreground">{{ $item->vehiculo->marca }} {{ $item->vehiculo->modelo }}</p>
                                                    <p class="text-sm text-foreground-muted">Año {{ $item->vehiculo->anio }}</p>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-foreground-muted">Placa</span>
                                                    <span class="font-mono glass glass-subtle px-2 py-0.5 rounded">{{ $item->vehiculo->placa }}</span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-foreground-muted">Capacidad</span>
                                                    <span class="text-foreground font-medium">{{ $item->vehiculo->capacidad ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-foreground-muted">Estado</span>
                                                    <span class="glass glass-green glass-subtle px-2 py-0.5 rounded text-success text-xs font-semibold">Activo</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-xl glass glass-subtle flex items-center justify-center">
                                        <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                    <p class="text-foreground-muted mb-3">Sin vehículo asignado esta semana</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Mi Disponibilidad --}}
                    <div class="glass glass-strong rounded-2xl p-6" wire:poll.5s>
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Esta Semana
                        </h2>

                        <div class="space-y-2 mb-4">
                            @php
                                // Definir estilos según el tipo
                                $estilos = [
                                    'disponible' => [
                                        'badge' => 'glass-green text-success',
                                        'texto' => 'Disponible'
                                    ],
                                    'ocupado' => [
                                        'badge' => 'glass-red text-danger',
                                        'texto' => 'Ocupado'
                                    ],
                                    'vacaciones' => [
                                        'badge' => 'glass-amber text-warning',
                                        'texto' => 'Vacaciones'
                                    ],
                                    'bloqueo' => [
                                        'badge' => 'glass-red text-danger',
                                        'texto' => 'Bloqueado'
                                    ],
                                    'asignado_envio' => [
                                        'badge' => 'glass-blue text-primary',
                                        'texto' => 'En Ruta'
                                    ]
                                ];
                            @endphp

                            @forelse($this->diasSemana as $dia)
                                @php
                                    $esHoy = $dia['es_hoy'];
                                    $tipo = $dia['tipo'];
                                    $estilo = $tipo ? ($estilos[$tipo] ?? $estilos['disponible']) : null;
                                @endphp
                                
                                <div wire:click="seleccionarDia('{{ $dia['fecha'] }}')" 
                                    class="flex items-center justify-between p-2 rounded-lg cursor-pointer hover:bg-surface transition-colors {{ $esHoy ? 'glass glass-blue ring-1 ring-primary' : 'glass glass-subtle' }}" 
                                    title="{{ $dia['descripcion'] }}">
                                    <div class="flex items-center gap-3">
                                        <div class="flex flex-col items-center min-w-[3rem]">
                                            <span class="text-sm font-medium {{ $esHoy ? 'text-primary' : 'text-foreground' }}">
                                                {{ $dia['dia_nombre'] }}
                                            </span>
                                            <span class="text-xs text-foreground-muted">
                                                {{ $dia['dia_numero'] }}
                                            </span>
                                        </div>
                                        @if($dia['horario'])
                                            <span class="text-xs text-foreground-muted bg-surface-secondary/50 px-2 py-1 rounded">
                                                {{ $dia['horario'] }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($estilo)
                                        <span class="glass {{ $estilo['badge'] }} glass-subtle px-2 py-0.5 rounded text-xs font-semibold">
                                            {{ $estilo['texto'] }}
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-foreground-muted text-sm">No hay días disponibles</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <a href="{{ route('repartidor.calendario') }}" class="block text-center glass glass-subtle px-4 py-2 rounded-xl text-sm font-medium hover:shadow-md transition-all">
                            Ver mi calendarización
                        </a>
                    </div>
                    
                    {{-- Detalles del Día (Modal/Panel) --}}
                    @if($diaSeleccionado)
                        <div class="glass glass-strong rounded-2xl p-6 animate-in fade-in slide-in-from-right-4 duration-300">
                            <h3 class="text-lg font-semibold text-foreground mb-4">Detalles del Día</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-foreground-muted">Fecha</p>
                                    <p class="text-lg font-bold text-foreground capitalize">{{ $diaSeleccionado['fecha'] }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-foreground-muted">Horario</p>
                                    <p class="text-foreground font-medium">{{ $diaSeleccionado['horario'] }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-foreground-muted">Estado</p>
                                    <span class="inline-block mt-1 px-3 py-1 rounded-lg text-sm font-semibold capitalize
                                        {{ $diaSeleccionado['tipo'] === 'disponible' ? 'glass glass-green text-success' : '' }}
                                        {{ $diaSeleccionado['tipo'] === 'ocupado' ? 'glass glass-red text-danger' : '' }}
                                        {{ $diaSeleccionado['tipo'] === 'vacaciones' ? 'glass glass-amber text-warning' : '' }}
                                        {{ $diaSeleccionado['tipo'] === 'bloqueo' ? 'glass glass-gray text-foreground' : '' }}">
                                        {{ $diaSeleccionado['tipo'] }}
                                    </span>
                                </div>

                                @if($diaSeleccionado['vehiculo'])
                                    <div class="glass glass-subtle rounded-xl p-3">
                                        <p class="text-sm text-foreground-muted mb-2">Vehículo Asignado</p>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg glass glass-blue flex items-center justify-center">
                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-foreground text-sm">{{ $diaSeleccionado['vehiculo']['marca'] }} {{ $diaSeleccionado['vehiculo']['modelo'] }}</p>
                                                <p class="text-xs text-foreground-muted font-mono">{{ $diaSeleccionado['vehiculo']['placa'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <button wire:click="cerrarDetalle" class="w-full glass glass-subtle py-2 rounded-xl text-sm font-medium hover:bg-surface transition-colors">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

</div>

