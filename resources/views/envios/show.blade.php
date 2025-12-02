@extends('layouts.app')

@section('title', 'Detalles del Envío')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-foreground">Detalles del Envío</h1>
                    <p class="text-foreground-muted text-sm mt-1">Código: <span class="font-medium">{{ $envio->codigo }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('envios.edit', $envio) }}" class="px-4 py-2 bg-warning hover:bg-warning-hover text-white rounded-xl transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('envios.index') }}" class="px-4 py-2 bg-surface-secondary border border-border rounded-xl text-foreground hover:bg-border transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            {{-- Estado del Envío --}}
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-foreground">Estado del Envío</h2>
                    @php
                        $estadoColors = [
                            'pendiente' => 'bg-warning/10 text-warning border-warning/20',
                            'en_ruta' => 'bg-primary/10 text-primary border-primary/20',
                            'entregado' => 'bg-success/10 text-success border-success/20',
                            'devuelto' => 'bg-danger/10 text-danger border-danger/20',
                            'cancelado' => 'bg-surface-secondary text-foreground-muted border-border'
                        ];
                        $estadoLabels = [
                            'pendiente' => 'Pendiente',
                            'en_ruta' => 'En Ruta',
                            'entregado' => 'Entregado',
                            'devuelto' => 'Devuelto',
                            'cancelado' => 'Cancelado'
                        ];
                    @endphp
                    <span class="px-4 py-2 rounded-xl border font-medium {{ $estadoColors[$envio->estado] ?? 'bg-surface-secondary text-foreground-muted border-border' }}">
                        {{ $estadoLabels[$envio->estado] ?? $envio->estado }}
                    </span>
                </div>
                <div class="mt-4 text-sm text-foreground-muted">
                    <p><strong>Fecha de creación:</strong> {{ $envio->fecha_creacion->format('d/m/Y H:i') }}</p>
                    <p><strong>Fecha estimada de entrega:</strong> {{ $envio->fecha_estimada->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Información del Remitente --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Remitente
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Nombre</label>
                            <p class="text-foreground">{{ $envio->remitente_nombre }}</p>
                        </div>
                        @if($envio->remitente_telefono)
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Teléfono</label>
                            <p class="text-foreground">{{ $envio->remitente_telefono }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Dirección</label>
                            <p class="text-foreground">{{ $envio->remitente_direccion }}</p>
                        </div>
                    </div>
                </div>

                {{-- Información del Destinatario --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Destinatario
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Nombre</label>
                            <p class="text-foreground">{{ $envio->destinatario_nombre }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Email</label>
                            <p class="text-foreground break-all">{{ $envio->destinatario_email }}</p>
                        </div>
                        @if($envio->destinatario_telefono)
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Teléfono</label>
                            <p class="text-foreground">{{ $envio->destinatario_telefono }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Dirección de Entrega</label>
                            <p class="text-foreground">{{ $envio->destinatario_direccion }}</p>
                        </div>
                    </div>
                </div>

                {{-- Información del Paquete --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Paquete
                    </h2>
                    <div class="space-y-3">
                        @if($envio->descripcion)
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Descripción</label>
                            <p class="text-foreground">{{ $envio->descripcion }}</p>
                        </div>
                        @endif
                        @if($envio->peso)
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Peso</label>
                            <p class="text-foreground">{{ $envio->peso }} kg</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Tipo de Envío</label>
                            @php
                                $tipoLabels = [
                                    'express' => 'Express (24h)',
                                    'normal' => 'Normal (2-3 días)',
                                    'economico' => 'Económico (5-7 días)'
                                ];
                            @endphp
                            <p class="text-foreground">{{ $tipoLabels[$envio->tipo_envio] ?? $envio->tipo_envio }}</p>
                        </div>
                        @if($envio->foto_paquete)
                        <div>
                            <label class="block text-sm font-medium text-foreground-muted mb-1">Foto del Paquete</label>
                            <div class="mt-2">
                                <img src="{{ Storage::url($envio->foto_paquete) }}" alt="Foto del paquete" 
                                     class="w-full h-40 object-cover rounded-xl border border-border cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openImageModal('{{ Storage::url($envio->foto_paquete) }}')">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Información de Asignación --}}
            @if($envio->repartidor || $envio->vehiculoAsignacion)
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border mt-6">
                <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1l-1 1v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4L3 9V8a2 2 0 012-2h3z"/>
                    </svg>
                    Asignación de Entrega
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($envio->repartidor)
                    <div>
                        <label class="block text-sm font-medium text-foreground-muted mb-1">Repartidor Asignado</label>
                        <a href="{{ route('usuarios.show', $envio->repartidor) }}" class="block">
                            <div class="flex items-center gap-3 p-3 bg-surface-secondary rounded-xl hover:bg-border transition-colors cursor-pointer group">
                                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center group-hover:bg-primary-hover transition-colors">
                                    <span class="text-white font-semibold text-sm">
                                        {{ strtoupper(substr($envio->repartidor->nombre, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-foreground font-medium group-hover:text-primary transition-colors">{{ $envio->repartidor->nombre }}</p>
                                    @if($envio->repartidor->email)
                                    <p class="text-foreground-muted text-sm">{{ $envio->repartidor->email }}</p>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-foreground-muted group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    @if($envio->vehiculoAsignacion?->vehiculo)
                    <div>
                        <label class="block text-sm font-medium text-foreground-muted mb-1">Vehículo Asignado</label>
                        <a href="{{ route('vehiculos.show', $envio->vehiculoAsignacion->vehiculo) }}" class="block">
                            <div class="p-3 bg-surface-secondary rounded-xl hover:bg-border transition-colors cursor-pointer group flex items-center justify-between">
                                <div>
                                    <p class="text-foreground font-medium group-hover:text-primary transition-colors">{{ $envio->vehiculoAsignacion->vehiculo->marca }} {{ $envio->vehiculoAsignacion->vehiculo->modelo }}</p>
                                    <p class="text-foreground-muted text-sm">Placa: {{ $envio->vehiculoAsignacion->vehiculo->placa }}</p>
                                </div>
                                <svg class="w-4 h-4 text-foreground-muted group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

{{-- Modal para imagen --}}
@if($envio->foto_paquete)
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Foto del paquete" class="max-w-full max-h-full rounded-xl">
    </div>
</div>
@endif

@push('scripts')
<script>
@if($envio->foto_paquete)
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Cerrar modal al hacer click fuera de la imagen
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
@endif
</script>
@endpush
@endsection