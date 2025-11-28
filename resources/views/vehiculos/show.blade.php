@extends('layouts.app')

@section('title', 'Detalle Vehículo')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('vehiculos.index') }}" class="w-10 h-10 rounded-xl bg-surface-secondary border border-border flex items-center justify-center text-foreground-muted hover:text-primary hover:border-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-foreground">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h1>
                        <p class="text-foreground-muted mt-1">Placa: <span class="font-mono bg-surface-secondary px-2 py-0.5 rounded text-foreground">{{ $vehiculo->placa }}</span></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="px-4 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium flex items-center gap-2 transition-colors shadow-lg shadow-primary/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" onsubmit="return confirm('¿Eliminar este vehículo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 rounded-xl font-medium flex items-center gap-2 bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Gallery --}}
                <div class="lg:col-span-2 bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4">Galería de Fotos</h2>
                    @php
                        $fotos = json_decode($vehiculo->foto, true) ?? [];
                    @endphp
                    @if(count($fotos) > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($fotos as $index => $foto)
                        <div class="relative group {{ $index === 0 ? 'col-span-2 aspect-video' : 'aspect-square' }} rounded-xl overflow-hidden bg-surface-secondary">
                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto vehículo" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="aspect-video rounded-xl bg-surface-secondary flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-foreground-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-foreground-muted mb-2">Sin fotos disponibles</p>
                        <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="text-primary text-sm hover:text-primary-hover">Agregar fotos</a>
                    </div>
                    @endif
                </div>

                {{-- Info Panel --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4">Información</h2>
                    
                    @php
                        $statusColors = [
                            'disponible' => 'bg-success',
                            'en_uso' => 'bg-primary',
                            'mantenimiento' => 'bg-warning',
                            'inactivo' => 'bg-danger',
                        ];
                        $statusLabels = [
                            'disponible' => 'Disponible',
                            'en_uso' => 'En uso',
                            'mantenimiento' => 'Mantenimiento',
                            'inactivo' => 'Inactivo',
                        ];
                    @endphp

                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-border">
                            <span class="text-foreground-muted">Estado</span>
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $statusColors[$vehiculo->estado] ?? 'bg-foreground-muted' }}"></span>
                                <span class="text-foreground">{{ $statusLabels[$vehiculo->estado] ?? $vehiculo->estado }}</span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-border">
                            <span class="text-foreground-muted">Marca</span>
                            <span class="text-foreground font-medium">{{ $vehiculo->marca }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-border">
                            <span class="text-foreground-muted">Modelo</span>
                            <span class="text-foreground font-medium">{{ $vehiculo->modelo }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-border">
                            <span class="text-foreground-muted">Placa</span>
                            <span class="font-mono text-foreground bg-surface-secondary px-2 py-1 rounded">{{ $vehiculo->placa }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-border">
                            <span class="text-foreground-muted">Año</span>
                            <span class="text-foreground font-medium">{{ $vehiculo->anio ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-foreground-muted">Capacidad</span>
                            <span class="text-foreground font-medium">{{ $vehiculo->capacidad ?? '-' }}</span>
                        </div>
                    </div>

                    @if($vehiculo->observaciones)
                    <div class="mt-6 pt-6 border-t border-border">
                        <h3 class="text-sm font-medium text-foreground-muted mb-2">Observaciones</h3>
                        <p class="text-foreground text-sm">{{ $vehiculo->observaciones }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Assignment --}}
            @if($asignacion = $vehiculo->asignacionActiva())
            <div class="bg-surface rounded-2xl p-6 mt-6 shadow-sm border border-border">
                <h2 class="text-lg font-semibold text-foreground mb-4">Asignación Actual</h2>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr($asignacion->repartidor->nombre ?? 'U', 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-foreground">{{ $asignacion->repartidor->nombre ?? 'Sin nombre' }}</p>
                        <p class="text-sm text-foreground-muted">{{ $asignacion->repartidor->email ?? '' }}</p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-sm text-foreground-muted">Asignado desde</p>
                        <p class="text-foreground font-medium">{{ $asignacion->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
