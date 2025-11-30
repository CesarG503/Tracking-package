<div wire:poll.5s>
    <div class="mb-8">
        {{-- Header principal --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Avatar --}}
                <div class="relative">
                    <div class="glass glass-primary glass-static w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-3xl">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</span>
                    </div>
                    {{-- Indicador de estado --}}
                    <div class="glass glass-static !absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-4 border-background 
                        {{ $usuario->activo ? 'glass-green glass-strong' : 'glass-red glass-strong' }}">
                    </div>
                </div>

                {{-- Información --}}
                <div>
                    <h1 class="text-3xl font-bold text-foreground mb-1">{{ $usuario->nombre }}</h1>
                    <div class="flex items-center gap-3 text-foreground-muted">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $usuario->email }}
                        </span>
                        @if($usuario->telefono)
                        <span class="hidden sm:block">•</span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $usuario->telefono }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('usuarios.index') }}" class="glass glass-strong px-4 py-2.5 rounded-xl text-foreground font-medium transition-all hover:shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
                <a href="{{ route('usuarios.edit', $usuario) }}" class="glass glass-strong glass-blue px-4 py-2.5 rounded-xl text-gray font-medium transition-all hover:shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Columna izquierda: Información del usuario --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Información básica --}}
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-foreground mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Información General
                </h2>
                
                <div class="space-y-4">
                    {{-- Estado --}}
                    <div class="flex items-center justify-between py-3 border-b border-border/30">
                        <span class="text-foreground-muted text-sm font-medium">Estado</span>

                        <span class="glass glass-static glass-subtle glass-pill inline-flex items-center px-2.5 py-1 text-xs font-medium 
                            {{ $usuario->activo ? 'glass-green text-success' : 'glass-red text-danger' }}">
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    {{-- Rol --}}
                    <div class="flex items-center justify-between py-3 border-b border-border/30">
                        <span class="text-foreground-muted text-sm font-medium">Rol</span>
                        @if($usuario->rol === 'admin')
                        <span class="glass glass-static glass-strong glass-purple inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-purple-700">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                            Administrador
                        </span>
                        @else
                        <span class="glass glass-static glass-strong glass-blue inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-cyan-100 text-cyan-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Repartidor
                        </span>
                        @endif
                    </div>

                    {{-- Teléfono --}}
                    <div class="flex items-center justify-between py-3 border-b border-border/30">
                        <span class="text-foreground-muted text-sm font-medium">Teléfono</span>
                        <span class="text-foreground font-medium">{{ $usuario->telefono ?? '-' }}</span>
                    </div>

                    {{-- Licencia --}}
                    <div class="flex items-center justify-between py-3 border-b border-border/30">
                        <span class="text-foreground-muted text-sm font-medium">Licencia</span>
                        <span class="font-mono text-sm text-foreground {{ $usuario->licencia ? 'glass glass-subtle glass-static px-2 py-1 rounded' : '' }}">
                            {{ $usuario->licencia ?? '-' }}
                        </span>
                    </div>

                    {{-- Fecha de registro --}}
                    <div class="flex items-center justify-between py-3">
                        <span class="text-foreground-muted text-sm font-medium">Registrado</span>
                        <span class="text-foreground font-medium">{{ $usuario->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Card de estadísticas rápidas --}}
            <div class="glass-card glass-strong rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Resumen de Entregas</h3>
                <div class="space-y-3">
                    {{-- Tasa de éxito --}}
                    <div class="flex items-center justify-between p-3 glass glass-subtle glass-static rounded-xl">
                        <span class="text-foreground-muted text-sm">Tasa de éxito</span>
                        <span class="text-foreground font-bold">{{ $tasaExito }}%</span>
                    </div>

                    {{-- En proceso --}}
                    <div class="flex items-center justify-between p-3 glass glass-subtle glass-static rounded-xl">
                        <span class="text-foreground-muted text-sm">En proceso</span>
                        <span class="text-foreground font-medium">{{ $enviosEnProceso }}</span>
                    </div>

                    {{-- Última actividad --}}
                    <div class="flex items-center justify-between p-3 glass glass-subtle glass-static rounded-xl">
                        <span class="text-foreground-muted text-sm">Última actividad</span>
                        <span class="text-foreground font-medium text-sm">{{ $usuario->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna derecha: Stats y actividad --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Tarjetas de estadísticas --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-7 mx-auto max-w-4xl">
                {{-- Total de envíos --}}
                <div class="relative group">
                    <!-- Badge arriba a la derecha -->
                    <div class="!absolute -top-2 -right-4 px-3 py-1 text-m font-semibold
                                glass glass-loading glass-static glass-primary glass-strong z-30">
                        Total Envíos
                    </div>
                    <!-- Contenido del card -->
                    <div class="glass glass-static glass-strong glass-shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-center gap-4">
                            <div class="w-14 h-14 rounded-xl glass glass-primary glass-static flex items-center justify-center">
                                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-3xl font-bold text-foreground">{{ $totalEnvios }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Entregados --}}
                <div class="relative group">
                    <!-- Badge arriba a la derecha -->
                    <div class="!absolute -top-2 -right-4 px-3 py-1 text-m font-semibold
                                glass glass-loading glass-static glass-green glass-strong z-30">
                        Entregados
                    </div>
                    <!-- Contenido del card -->
                    <div class="glass glass-static glass-strong glass-shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-center gap-4">
                            <div class="w-14 h-14 rounded-xl glass glass-green glass-static flex items-center justify-center">
                                <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-3xl font-bold text-foreground">{{ $enviosEntregados }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Vehículos activos --}}
                <div class="relative group">
                    <!-- Badge arriba a la derecha -->
                    <div class="!absolute -top-2 -right-4 px-3 py-1 text-m font-semibold
                                glass glass-loading glass-static glass-amber glass-strong z-30">
                        Vehículos
                    </div>
                    <!-- Contenido del card -->
                    <div class="glass glass-static glass-strong glass-shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-center gap-4">
                            <div class="w-14 h-14 rounded-xl glass glass-amber glass-static flex items-center justify-center">
                                <svg class="w-7 h-7 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-3xl font-bold text-foreground">{{ $vehiculosActivos }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vehículos asignados --}}
            <div class="glass-card glass-strong rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Vehículos Asignados
                    </h2>
                    <span class="glass glass-subtle glass-static text-sm font-medium px-2 py-1 rounded">{{ $usuario->vehiculoAsignaciones->count() }} total</span>
                </div>

                @if($vehiculos->count() > 0)
                <div class="space-y-3">
                    @foreach($vehiculos as $asignacion)
                    <div class="flex items-center gap-4 p-4 rounded-xl glass glass-subtle glass-static hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl glass glass-blue glass-static flex items-center justify-center">
                            <svg class="w-6 h-6 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-foreground">{{ $asignacion->vehiculo->marca ?? 'N/A' }} {{ $asignacion->vehiculo->modelo ?? '' }}</p>
                            <p class="text-sm text-foreground-muted font-mono">{{ $asignacion->vehiculo->placa ?? 'Sin placa' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="glass glass-subtle glass-static glass-pill px-3 py-1.5 text-xs font-semibold 
                                {{ $asignacion->estado === 'activo' ? 'glass-green text-green-700' : '' }}
                                {{ $asignacion->estado === 'finalizado' ? 'glass-blue text-blue-700' : '' }}
                                {{ $asignacion->estado === 'cancelado' ? 'glass-red text-red-700' : '' }}">
                                {{ ucfirst($asignacion->estado) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($usuario->vehiculoAsignaciones->count() > 5 && !$mostrarTodosVehiculos)
                <div class="mt-4 text-center">
                    <button wire:click="toggleMostrarTodosVehiculos" class="glass glass-blue glass-static px-4 py-2 rounded-xl text-white text-sm font-medium hover:shadow-lg transition-all">
                        Ver todos los vehículos ({{ $usuario->vehiculoAsignaciones->count() }})
                    </button>
                </div>
                @endif
                @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl glass glass-subtle glass-static flex items-center justify-center">
                        <svg class="w-10 h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <p class="text-foreground-muted font-medium">No hay vehículos asignados</p>
                </div>
                @endif
            </div>

            {{-- Envíos recientes --}}
            <div class="glass-card glass-strong rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Envíos Recientes
                    </h2>
                </div>

                @if($enviosRecientes->count() > 0)
                <div class="space-y-3">
                    @foreach($enviosRecientes as $envio)
                    <div class="flex items-start gap-4 p-4 rounded-xl glass glass-subtle glass-static hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-lg glass glass-blue glass-static flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-foreground">Envío #{{ $envio->id }}</p>
                                    <p class="text-sm text-foreground-muted mt-0.5">{{ $envio->destinatario_direccion ?? 'Sin dirección' }}</p>
                                </div>
                                <span class="glass glass-subtle glass-static glass-pill px-2.5 py-1 text-xs font-semibold flex-shrink-0
                                    {{ $envio->estado === 'pendiente' ? 'bg-gray-500/30 text-gray-700' : '' }}
                                    {{ $envio->estado === 'en_ruta' ? 'glass-amber text-yellow-700' : '' }}
                                    {{ $envio->estado === 'entregado' ? 'glass-green text-green-700' : '' }}
                                    {{ $envio->estado === 'devuelto' ? 'glass-blue text-blue-700' : '' }}
                                    {{ $envio->estado === 'cancelado' ? 'glass-red text-red-700' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $envio->estado)) }}
                                </span>
                            </div>
                            <p class="text-xs text-foreground-muted mt-2">{{ $envio->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($usuario->envios->count() > 5 && !$mostrarTodosEnvios)
                <div class="mt-4 text-center">
                    <button wire:click="toggleMostrarTodosEnvios" class="glass glass-blue glass-static px-4 py-2 rounded-xl text-white text-sm font-medium hover:shadow-lg transition-all">
                        Ver todos los envíos ({{ $usuario->envios->count() }})
                    </button>
                </div>
                @endif
                @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl glass glass-subtle glass-static flex items-center justify-center">
                        <svg class="w-10 h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-foreground-muted font-medium">No hay envíos registrados</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>