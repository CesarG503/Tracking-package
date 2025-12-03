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
            
            {{-- QR Code Section --}}
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-foreground">Código de Seguimiento</h2>
                        <p class="text-sm text-foreground-muted mt-1">Escanea para ver el estado en tiempo real</p>
                        <a href="{{ route('tracking', $envio->codigo) }}" target="_blank" class="text-primary text-sm hover:underline mt-2 inline-block break-all">
                            {{ route('tracking', $envio->codigo) }}
                        </a>
                        
                        {{-- Botones QR --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mt-4">
                            <button onclick="openQRModal()" class="w-full sm:w-auto px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Vista Previa QR
                            </button>
                            <button onclick="downloadQR()" class="w-full sm:w-auto px-4 py-2 bg-success hover:bg-success-hover text-white rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar QR
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-center lg:justify-end">
                        <div class="bg-white p-2 rounded-xl border border-border cursor-pointer hover:shadow-md transition-shadow" onclick="openQRModal()">
                            <div class="w-[100px] h-[100px] flex items-center justify-center">
                                {!! QrCode::size(100)->generate(route('tracking', $envio->codigo)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             {{-- Información de Entrega --}}
            @if($envio->estado === 'entregado' && ($envio->foto_entrega || $envio->observaciones))
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border mb-6">
                <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Confirmación de Entrega
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Foto de Entrega --}}
                    @if($envio->foto_entrega)
                    <div>
                        <label class="block text-sm font-medium text-foreground-muted mb-3">Foto de Entrega</label>
                        <div class="relative group">
                            <img src="{{ Storage::url($envio->foto_entrega) }}" alt="Foto de entrega" 
                                 class="w-full h-64 object-cover rounded-xl border border-border cursor-pointer hover:opacity-90 transition-all duration-200 shadow-sm group-hover:shadow-md"
                                 onclick="openImageModal('{{ Storage::url($envio->foto_entrega) }}', 'Foto de Entrega')">
                        </div>
                        <p class="text-xs text-foreground-muted mt-2 text-center">Click para ampliar imagen</p>
                    </div>
                    @endif
                    
                    {{-- Observaciones de Entrega --}}
                    @if($envio->observaciones)
                    <div class="{{ $envio->foto_entrega ? '' : 'lg:col-span-2' }}">
                        <label class="block text-sm font-medium text-foreground-muted mb-3">Observaciones del Repartidor</label>
                        <div class="bg-surface-secondary rounded-xl p-4 border border-border">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-success/10 flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-foreground leading-relaxed">{{ $envio->observaciones }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                {{-- Información adicional de entrega --}}
                <div class="mt-6 pt-4 border-t border-border">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-foreground-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Entregado el {{ $envio->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($envio->repartidor)
                        <div class="flex items-center gap-2 text-foreground-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Por {{ $envio->repartidor->nombre }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

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
@if($envio->foto_paquete || ($envio->estado === 'entregado' && $envio->foto_entrega))
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

{{-- Modal para QR Code --}}
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl p-8 max-w-md mx-auto shadow-2xl">
        <button onclick="closeQRModal()" class="absolute -top-4 -right-4 bg-white rounded-full p-2 text-gray-600 hover:text-gray-800 transition-colors shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <div class="text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Código QR de Seguimiento</h3>
            <div id="qrCodeLarge" class="flex justify-center mb-4">
                {!! QrCode::size(250)->generate(route('tracking', $envio->codigo)) !!}
            </div>
            <p class="text-sm text-gray-600 mb-4">Código: {{ $envio->codigo }}</p>
            <div class="flex justify-center gap-3">
                <button onclick="downloadQR()" class="px-4 py-2 bg-success hover:bg-success-hover text-white rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
@if($envio->foto_paquete || ($envio->estado === 'entregado' && $envio->foto_entrega))
function openImageModal(imageSrc, altText = 'Imagen') {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageSrc;
    modalImage.alt = altText;
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
@endif

// Funciones QR Modal
function openQRModal() {
    const modal = document.getElementById('qrModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeQRModal() {
    const modal = document.getElementById('qrModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Función para descargar QR
function downloadQR() {
    // Crear canvas temporal para generar la imagen
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Obtener el SVG del QR
    const qrSvg = document.querySelector('#qrCodeLarge svg') || document.querySelector('.bg-white svg');
    if (!qrSvg) return;
    
    // Configurar el canvas con más altura para incluir texto
    canvas.width = 350;
    canvas.height = 400;
    
    // Crear imagen desde SVG
    const svgData = new XMLSerializer().serializeToString(qrSvg);
    const img = new Image();
    const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
    const url = URL.createObjectURL(svgBlob);
    
    img.onload = function() {
        // Fondo blanco
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Configurar fuente
        ctx.fillStyle = '#1f2937'; // Color gris oscuro
        ctx.textAlign = 'center';
        
        // Título principal
        ctx.font = 'bold 24px Arial';
        ctx.fillText('Código de Paquete', canvas.width / 2, 40);
        
        // Línea decorativa
        ctx.strokeStyle = '#e5e7eb';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(50, 60);
        ctx.lineTo(canvas.width - 50, 60);
        ctx.stroke();
        
        // Dibujar QR centrado
        const qrSize = 250;
        const qrX = (canvas.width - qrSize) / 2;
        const qrY = 80;
        ctx.drawImage(img, qrX, qrY, qrSize, qrSize);
        
        // Texto del código debajo del QR
        ctx.font = 'bold 18px Arial';
        ctx.fillText('Código: {{ $envio->codigo }}', canvas.width / 2, qrY + qrSize + 40);
        
        // Texto adicional más pequeño
        ctx.font = '14px Arial';
        ctx.fillStyle = '#6b7280'; // Color gris más claro
        ctx.fillText('Escanea para seguimiento en tiempo real', canvas.width / 2, qrY + qrSize + 65);
        
        // Descargar
        const link = document.createElement('a');
        link.download = 'qr-envio-{{ $envio->codigo }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        URL.revokeObjectURL(url);
    };
    
    img.src = url;
}

// Cerrar modales con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        @if($envio->foto_paquete || ($envio->estado === 'entregado' && $envio->foto_entrega))
        closeImageModal();
        @endif
        closeQRModal();
    }
});

// Cerrar modales al hacer click fuera
@if($envio->foto_paquete || ($envio->estado === 'entregado' && $envio->foto_entrega))
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
@endif

document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});
</script>
@endpush
@endsection