<div wire:poll.5s>
    {{-- resources/views/livewire/repartidor/mi-perfil.blade.php --}}
<div class="max-w-4xl mx-auto space-y-6 pb-6">
    
    {{-- Header con Avatar e Información Principal --}}
    <div class="glass-card rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center ring-4 ring-primary/20 flex-shrink-0">
                <span class="text-white text-3xl font-bold">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</span>
            </div>
            
            {{-- Info Principal --}}
            <div class="flex-1 text-center sm:text-left">
                <h1 class="text-2xl font-bold text-foreground">{{ auth()->user()->nombre }}</h1>
                <p class="text-foreground-muted mt-1">{{ auth()->user()->email }}</p>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                    <span class="glass-blue glass-subtle px-3 py-1 rounded-lg text-xs font-semibold text-primary">
                        Repartidor
                    </span>
                    <span class="glass-green glass-subtle px-3 py-1 rounded-lg text-xs font-semibold text-success">
                        {{ auth()->user()->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Responsive --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Información de Contacto --}}
        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Información de Contacto
            </h2>
            
            <div class="space-y-3">
                @if(auth()->user()->telefono)
                <div class="flex items-center gap-3 p-3 glass-subtle rounded-xl">
                    <div class="w-10 h-10 rounded-lg glass-green flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-foreground-muted">Teléfono</p>
                        <p class="text-sm font-medium text-foreground">{{ auth()->user()->telefono }}</p>
                    </div>
                </div>
                @endif

                @if(auth()->user()->licencia)
                <div class="flex items-center gap-3 p-3 glass-subtle rounded-xl">
                    <div class="w-10 h-10 rounded-lg glass-amber flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-foreground-muted">Licencia de Conducir</p>
                        <p class="text-sm font-medium text-foreground font-mono">{{ auth()->user()->licencia }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-3 p-3 glass-subtle rounded-xl">
                    <div class="w-10 h-10 rounded-lg glass flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-foreground-muted">Miembro desde</p>
                        <p class="text-sm font-medium text-foreground">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vehículo Asignado --}}
        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Mi Vehículo
            </h2>
            
            @if($this->vehiculoActual && $this->vehiculoActual->vehiculo)
                <div class="space-y-3">
                    <div class="p-4 glass-blue glass-subtle rounded-xl text-center">
                        <p class="text-lg font-bold text-foreground">{{ $this->vehiculoActual->vehiculo->marca }} {{ $this->vehiculoActual->vehiculo->modelo }}</p>
                        <p class="text-sm text-foreground-muted">Año {{ $this->vehiculoActual->vehiculo->anio }}</p>
                    </div>

                    <div class="flex items-center gap-3 p-3 glass-subtle rounded-xl">
                        <div class="w-10 h-10 rounded-lg glass flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-foreground-muted">Placa</p>
                            <p class="text-sm font-bold text-foreground font-mono">{{ $this->vehiculoActual->vehiculo->placa }}</p>
                        </div>
                    </div>

                    @if($this->vehiculoActual->vehiculo->capacidad)
                    <div class="flex items-center gap-3 p-3 glass-subtle rounded-xl">
                        <div class="w-10 h-10 rounded-lg glass flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-foreground-muted">Capacidad de Carga</p>
                            <p class="text-sm font-medium text-foreground">{{ $this->vehiculoActual->vehiculo->capacidad }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-xl glass-subtle flex items-center justify-center">
                        <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <p class="text-sm text-foreground-muted">Sin vehículo asignado</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Estadísticas de Rendimiento --}}
    <div class="glass-card rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-foreground mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Estadísticas de Rendimiento
        </h2>
        
        {{-- Grid de Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="text-center p-4 glass-subtle rounded-xl">
                <div class="w-10 h-10 mx-auto mb-2 rounded-lg glass-blue flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-foreground mb-1">{{ $this->estadisticasGenerales['total_envios'] }}</p>
                <p class="text-xs text-foreground-muted">Total</p>
            </div>

            <div class="text-center p-4 glass-green glass-subtle rounded-xl">
                <div class="w-10 h-10 mx-auto mb-2 rounded-lg glass-green flex items-center justify-center">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-success mb-1">{{ $this->estadisticasGenerales['entregados'] }}</p>
                <p class="text-xs text-foreground-muted">Entregados</p>
            </div>

            <div class="text-center p-4 glass-blue glass-subtle rounded-xl">
                <div class="w-10 h-10 mx-auto mb-2 rounded-lg glass-blue flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-primary mb-1">{{ $this->estadisticasGenerales['en_ruta'] }}</p>
                <p class="text-xs text-foreground-muted">En Ruta</p>
            </div>

            <div class="text-center p-4 glass-amber glass-subtle rounded-xl">
                <div class="w-10 h-10 mx-auto mb-2 rounded-lg glass-amber flex items-center justify-center">
                    <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-warning mb-1">{{ $this->estadisticasGenerales['pendientes'] }}</p>
                <p class="text-xs text-foreground-muted">Pendientes</p>
            </div>
        </div>

        {{-- Tasa de Éxito y Envíos del Mes --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 glass-subtle rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm text-foreground-muted">Tasa de Éxito</p>
                    <p class="text-2xl font-bold text-foreground">{{ $this->estadisticasGenerales['tasa_exito'] }}%</p>
                </div>
                <div class="bg-surface-secondary rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-success to-primary h-full rounded-full transition-all duration-500" 
                         style="width: {{ $this->estadisticasGenerales['tasa_exito'] }}%"></div>
                </div>
            </div>

            <div class="p-4 glass-subtle rounded-xl text-center">
                <p class="text-sm text-foreground-muted mb-2">Envíos este Mes</p>
                <p class="text-3xl font-bold text-foreground">{{ $this->enviosDelMes }}</p>
            </div>
        </div>
    </div>
</div>
</div>
