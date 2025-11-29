@extends('layouts.app')

@section('title', 'Detalle Usuario')

@section('content')
<div class="container-xl py-8">
    {{-- Sidebar
    @include('partials.sidebar') --}}


    {{-- Header con ruta de navegación y acciones --}}
    <div class="mb-8">
        {{-- ruta de navegación --}}
        <nav class="flex items-center gap-2 text-sm mb-6">
            <a href="{{ route('usuarios.index') }}" class="text-foreground-muted hover:text-primary transition-colors">
                Usuarios
            </a>
            <svg class="w-4 h-4 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-foreground">{{ $usuario->nombre }}</span>
        </nav>

        {{-- Header principal --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Avatar --}}
                <div class="relative">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-3xl">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</span>
                    </div>
                    {{-- Indicador de estado --}}
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-4 border-background {{ $usuario->activo ? 'bg-success' : 'bg-gray-400' }}"></div>
                </div>

                {{-- Inforrmación --}}
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

            {{-- Acciones (volver, editar) --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('usuarios.index') }}" class="px-4 py-2.5 rounded-xl border border-border hover:bg-surface-secondary text-foreground font-medium transition-all hover:shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
                <a href="{{ route('usuarios.edit', $usuario) }}" class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white font-medium transition-all hover:shadow-lg flex items-center gap-2">
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
                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <span class="text-foreground-muted text-sm font-medium">Estado</span>
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $usuario->activo ? 'bg-success-light text-success' : 'bg-gray-100 text-gray-600' }}">
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    {{-- Rol --}}
                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <span class="text-foreground-muted text-sm font-medium">Rol</span>
                        @if($usuario->rol === 'admin')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                            Administrador
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Repartidor
                        </span>
                        @endif
                    </div>

                    {{-- Teléfono --}}
                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <span class="text-foreground-muted text-sm font-medium">Teléfono</span>
                        <span class="text-foreground font-medium">{{ $usuario->telefono ?? '-' }}</span>
                    </div>

                    {{-- Licencia --}}
                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <span class="text-foreground-muted text-sm font-medium">Licencia</span>
                        <span class="font-mono text-sm text-foreground {{ $usuario->licencia ? 'bg-surface-secondary px-2.5 py-1 rounded-lg' : '' }}">
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
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Resumen de Entregas</h3>
                <div class="space-y-3">
                    
                    <div class="space-y-3">
                        {{-- Tasa de éxito --}}
                        <div class="flex items-center justify-between p-3 bg-surface-secondary rounded-xl">
                            <span class="text-foreground-muted text-sm">Tasa de éxito</span>
                            <span class="text-foreground font-bold">
                                @php
                                    $finalizados = $usuario->envios->whereIn('estado', ['entregado', 'devuelto', 'cancelado']);
                                    $exitosos = $usuario->envios->where('estado', 'entregado');
                                    $tasa = $finalizados->count() > 0 ? round(($exitosos->count() / $finalizados->count()) * 100) : 0;
                                @endphp
                                {{ $tasa }}%
                            </span>
                        </div>

                        {{-- En proceso --}}
                        <div class="flex items-center justify-between p-3 bg-surface-secondary rounded-xl">
                            <span class="text-foreground-muted text-sm">En proceso</span>
                            <span class="text-foreground font-medium">
                                {{ $usuario->envios->whereIn('estado', ['pendiente', 'en_ruta'])->count() }}
                            </span>
                        </div>

                        {{-- Última actividad (utima actualiacion datos del usuario) --}}
                        <div class="flex items-center justify-between p-3 bg-surface-secondary rounded-xl">
                            <span class="text-foreground-muted text-sm">Última actividad</span>
                            <span class="text-foreground font-medium text-sm">
                                {{ $usuario->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Columna derecha: Stats y actividad --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Tarjetas de estadísticas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Total de envíos --}}
                <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-foreground">{{ $usuario->envios->count() }}</p>
                            <p class="text-sm text-foreground-muted font-medium">Total Envíos</p>
                        </div>
                    </div>
                </div>

                {{-- Entregados --}}
                <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-success/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-foreground">{{ $usuario->envios->where('estado', 'entregado')->count() }}</p>
                            <p class="text-sm text-foreground-muted font-medium">Entregados</p>
                        </div>
                    </div>
                </div>

                {{-- Vehículos activos --}}
                <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-warning/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-foreground">{{ $usuario->vehiculoAsignaciones->where('estado', 'activo')->count() }}</p>
                            <p class="text-sm text-foreground-muted font-medium">Vehículos Activos</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vehículos asignados --}}
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Vehículos Asignados
                    </h2>
                    <span class="text-sm text-foreground-muted font-medium">
                        {{ $usuario->vehiculoAsignaciones->count() }} total
                    </span>
                </div>

                
                @if($usuario->vehiculoAsignaciones->count() > 0)
                <div class="space-y-3">
                    @foreach($usuario->vehiculoAsignaciones->take(5) as $asignacion)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-surface border border-border hover:border-primary/30 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-surface-secondary flex items-center justify-center">
                            <svg class="w-6 h-6 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-foreground">
                                {{ $asignacion->vehiculo->marca ?? 'N/A' }} {{ $asignacion->vehiculo->modelo ?? '' }}
                            </p>
                            <p class="text-sm text-foreground-muted font-mono">
                                {{ $asignacion->vehiculo->placa ?? 'Sin placa' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            
                            {{-- TODO: colores sujeto a cambios --}}
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold 
                                {{ $asignacion->estado === 'activo' ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                                {{ $asignacion->estado === 'finalizado' ? 'bg-blue-100 text-blue-700 border border-blue-300' : '' }}
                                {{ $asignacion->estado === 'cancelado' ? 'bg-red-100 text-red-700 border border-red-300' : '' }}">

                                {{ ucfirst($asignacion->estado) }}
                            </span>
                            <button class="w-8 h-8 rounded-lg hover:bg-surface-secondary flex items-center justify-center text-foreground-muted transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>


                

                @if($usuario->vehiculoAsignaciones->count() > 5)
                <div class="mt-4 text-center">
                    <button class="text-primary hover:text-primary-hover font-medium text-sm transition-colors">
                        Ver todos los vehículos ({{ $usuario->vehiculoAsignaciones->count() }})
                    </button>
                </div>
                @endif


                @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-surface-secondary flex items-center justify-center">
                        <svg class="w-10 h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <p class="text-foreground-muted font-medium">No hay vehículos asignados</p>
                    <p class="text-sm text-foreground-muted mt-1">Este usuario aún no tiene vehículos asignados</p>
                </div>
                @endif
            </div>

            {{-- Actividad reciente --}}
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Envíos Recientes
                    </h2>
                </div>

                @if($usuario->envios->count() > 0)
                <div class="space-y-3">
                    @foreach($usuario->envios()->latest()->take(5)->get() as $envio)
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-surface border border-border hover:border-primary/30 transition-all">
                        <div class="w-10 h-10 rounded-lg bg-surface-secondary flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-foreground">Envío #{{ $envio->id }}</p>
                                    <p class="text-sm text-foreground-muted mt-0.5">
                                        {{ $envio->destinatario_direccion ?? 'Sin dirección' }}
                                    </p>
                                </div>
                                {{-- TODO: colores sujeto a cambios --}}
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0
                                    {{ $envio->estado === 'pendiente' ? 'bg-gray-100 text-gray-700 border border-gray-300' : '' }}
                                    {{ $envio->estado === 'en_ruta' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : '' }}
                                    {{ $envio->estado === 'entregado' ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                                    {{ $envio->estado === 'devuelto' ? 'bg-blue-100 text-blue-700 border border-blue-300' : '' }}
                                    {{ $envio->estado === 'cancelado' ? 'bg-red-100 text-red-700 border border-red-300' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $envio->estado)) }}
                                </span>
                            </div>
                            <p class="text-xs text-foreground-muted mt-2">
                                {{ $envio->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-surface-secondary flex items-center justify-center">
                        <svg class="w-10 h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-foreground-muted font-medium">No hay envíos registrados</p>
                    <p class="text-sm text-foreground-muted mt-1">Este usuario aún no tiene envíos asignados</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection