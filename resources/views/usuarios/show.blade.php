@extends('layouts.app')

@section('title', 'Detalle Usuario')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            {{-- Actualizado colores para modo claro --}}
            <a href="{{ route('usuarios.index') }}" class="w-10 h-10 rounded-xl liquid-btn-secondary flex items-center justify-center hover:text-indigo-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                    <span class="text-white font-bold text-xl">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $usuario->nombre }}</h1>
                    <p class="text-slate-500 mt-1">{{ $usuario->email }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('usuarios.edit', $usuario) }}" class="liquid-btn-primary px-4 py-2.5 rounded-xl font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Card --}}
        <div class="liquid-glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Información</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-black/5 dark:border-white/5">
                    <span class="text-slate-500">Estado</span>
                    <span class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium {{ $usuario->activo ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-500/20 text-slate-600 dark:text-slate-400' }}">
                        <span class="status-dot {{ $usuario->activo ? 'status-dot-success' : 'status-dot-danger' }}" style="width: 6px; height: 6px;"></span>
                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-black/5 dark:border-white/5">
                    <span class="text-slate-500">Rol</span>
                    @if($usuario->rol === 'admin')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                        Admin
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 dark:bg-cyan-500/20 text-cyan-700 dark:text-cyan-300">
                        Repartidor
                    </span>
                    @endif
                </div>
                <div class="flex items-center justify-between py-3 border-b border-black/5 dark:border-white/5">
                    <span class="text-slate-500">Teléfono</span>
                    <span class="text-slate-800 dark:text-white font-medium">{{ $usuario->telefono ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-black/5 dark:border-white/5">
                    <span class="text-slate-500">Licencia</span>
                    <span class="font-mono text-slate-800 dark:text-white {{ $usuario->licencia ? 'bg-slate-100 dark:bg-slate-700/50 px-2 py-0.5 rounded' : '' }}">{{ $usuario->licencia ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-slate-500">Registrado</span>
                    <span class="text-slate-800 dark:text-white font-medium">{{ $usuario->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Stats & Activity --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="liquid-glass-card rounded-2xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $usuario->envios->count() }}</p>
                            <p class="text-sm text-slate-500">Envíos</p>
                        </div>
                    </div>
                </div>
                <div class="liquid-glass-card rounded-2xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $usuario->envios->where('estado', 'entregado')->count() }}</p>
                            <p class="text-sm text-slate-500">Entregados</p>
                        </div>
                    </div>
                </div>
                <div class="liquid-glass-card rounded-2xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $usuario->vehiculoAsignaciones->where('estado', 'activo')->count() }}</p>
                            <p class="text-sm text-slate-500">Vehículos</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Assigned Vehicles --}}
            @if($usuario->vehiculoAsignaciones->count() > 0)
            <div class="liquid-glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Vehículos Asignados</h2>
                <div class="space-y-3">
                    @foreach($usuario->vehiculoAsignaciones->take(5) as $asignacion)
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-black/5 dark:border-white/5">
                        <div class="w-10 h-10 rounded-lg bg-slate-200 dark:bg-slate-600/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800 dark:text-white">{{ $asignacion->vehiculo->marca ?? 'N/A' }} {{ $asignacion->vehiculo->modelo ?? '' }}</p>
                            <p class="text-sm text-slate-500 font-mono">{{ $asignacion->vehiculo->placa ?? 'Sin placa' }}</p>
                        </div>
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $asignacion->estado === 'activo' ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-500/20 text-slate-600 dark:text-slate-400' }}">
                            <span class="status-dot {{ $asignacion->estado === 'activo' ? 'status-dot-success' : 'status-dot-danger' }}" style="width: 5px; height: 5px;"></span>
                            {{ ucfirst($asignacion->estado) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="liquid-glass-card rounded-2xl p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <p class="text-slate-500">Sin vehículos asignados</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
