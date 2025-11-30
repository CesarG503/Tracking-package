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
                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="px-4 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <button type="button" onclick="confirmDeleteVehiculo()" class="px-4 py-2.5 bg-danger hover:bg-danger/90 text-white rounded-xl font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                    <form id="delete-vehiculo-form" action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Info Card --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Vehicle Images --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        @php
                            $fotos = json_decode($vehiculo->foto, true) ?? [];
                        @endphp
                        
                        @if(!empty($fotos))
                            {{-- Imagen Principal --}}
                            <div class="relative aspect-video rounded-xl overflow-hidden bg-gradient-to-br from-primary/10 to-primary/5 mb-4">
                                <img id="main-image" 
                                     src="{{ asset('storage/' . $fotos[0]) }}" 
                                     alt="{{ $vehiculo->marca }} {{ $vehiculo->modelo }}" 
                                     class="w-full h-full object-cover transition-opacity duration-300">
                                <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-medium">
                                    <span id="current-image-index">1</span> / {{ count($fotos) }}
                                </div>
                            </div>

                            {{-- Miniaturas --}}
                            @if(count($fotos) > 1)
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($fotos as $index => $foto)
                                <button type="button" 
                                        onclick="changeMainImage('{{ asset('storage/' . $foto) }}', {{ $index + 1 }})"
                                        class="aspect-video rounded-lg overflow-hidden border-2 transition-all hover:border-primary focus:border-primary focus:outline-none thumbnail-btn {{ $index === 0 ? 'border-primary' : 'border-border' }}"
                                        data-index="{{ $index }}">
                                    <img src="{{ asset('storage/' . $foto) }}" 
                                         alt="Foto {{ $index + 1 }}" 
                                         class="w-full h-full object-cover">
                                </button>
                                @endforeach
                            </div>
                            @endif
                        @else
                            <div class="relative aspect-video rounded-xl overflow-hidden bg-gradient-to-br from-primary/10 to-primary/5 flex flex-col items-center justify-center">
                                <svg class="w-24 h-24 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-foreground-muted text-sm mt-3">Sin imágenes</p>
                            </div>
                        @endif
                    </div>

                    {{-- Details Card --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Información del Vehículo
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-sm text-foreground-muted">Marca</label>
                                <p class="text-foreground font-medium">{{ $vehiculo->marca }}</p>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm text-foreground-muted">Modelo</label>
                                <p class="text-foreground font-medium">{{ $vehiculo->modelo }}</p>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm text-foreground-muted">Placa</label>
                                <p class="font-mono bg-surface-secondary px-3 py-1.5 rounded-lg text-foreground inline-block">{{ $vehiculo->placa }}</p>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm text-foreground-muted">Año</label>
                                <p class="text-foreground font-medium">{{ $vehiculo->anio }}</p>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm text-foreground-muted">Capacidad</label>
                                <p class="text-foreground font-medium">{{ $vehiculo->capacidad }}</p>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm text-foreground-muted">Estado</label>
                                @php
                                    $estadoConfig = [
                                        'disponible' => ['color' => 'success', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'Disponible'],
                                        'asignado' => ['color' => 'warning', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'Asignado'],
                                        'mantenimiento' => ['color' => 'info', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'text' => 'Mantenimiento'],
                                        'fuera_servicio' => ['color' => 'danger', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'text' => 'Fuera de Servicio']
                                    ];
                                    $estado = $estadoConfig[$vehiculo->estado] ?? $estadoConfig['disponible'];
                                @endphp
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-{{ $estado['color'] }}-light text-{{ $estado['color'] }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="{{ $estado['icon'] }}" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $estado['text'] }}
                                </span>
                            </div>
                        </div>

                        @if($vehiculo->observaciones)
                        <div class="mt-6 pt-6 border-t border-border">
                            <label class="text-sm text-foreground-muted block mb-2">Observaciones</label>
                            <p class="text-foreground bg-surface-secondary p-4 rounded-xl text-sm leading-relaxed">{{ $vehiculo->observaciones }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Assignment History --}}
                    @if($vehiculo->asignaciones->count() > 0)
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Historial de Asignaciones
                        </h2>

                        <div class="space-y-3">
                            @foreach($vehiculo->asignaciones->sortByDesc('created_at')->take(5) as $asignacion)
                            <div class="flex items-center gap-4 p-4 bg-surface-secondary rounded-xl hover:bg-border transition-colors">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center shadow-lg shadow-primary/20">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($asignacion->repartidor->nombre, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-foreground font-medium">{{ $asignacion->repartidor->nombre }}</p>
                                    <p class="text-xs text-foreground-muted">{{ $asignacion->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                                <a href="{{ route('usuarios.show', $asignacion->repartidor) }}" class="text-primary hover:underline text-sm font-medium">
                                    Ver perfil
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar Stats --}}
                <div class="space-y-6">
                    {{-- Quick Stats --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Estadísticas</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-surface-secondary rounded-xl">
                                <span class="text-sm text-foreground-muted">Total Asignaciones</span>
                                <span class="text-xl font-bold text-primary">{{ $vehiculo->asignaciones->count() }}</span>
                            </div>
                            
                            @if($vehiculo->disponibilidades->count() > 0)
                            <div class="flex items-center justify-between p-3 bg-surface-secondary rounded-xl">
                                <span class="text-sm text-foreground-muted">Días Disponibles</span>
                                <span class="text-xl font-bold text-success">{{ $vehiculo->disponibilidades->count() }}</span>
                            </div>
                            @endif

                            <div class="flex items-center justify-between p-3 bg-surface-secondary rounded-xl">
                                <span class="text-sm text-foreground-muted">Antigüedad</span>
                                <span class="text-xl font-bold text-foreground">{{ date('Y') - $vehiculo->anio }} años</span>
                            </div>
                        </div>
                    </div>

                    {{-- Current Assignment --}}
                    @if($vehiculo->estado === 'asignado' && $vehiculo->asignaciones->where('activo', true)->first())
                    @php
                        $asignacionActual = $vehiculo->asignaciones->where('activo', true)->first();
                    @endphp
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Asignación Actual</h2>
                        <div class="flex items-center gap-3 p-4 bg-gradient-to-br from-primary/5 to-primary/10 rounded-xl border border-primary/20">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center shadow-lg shadow-primary/20">
                                <span class="text-white font-bold">{{ strtoupper(substr($asignacionActual->repartidor->nombre, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-foreground">{{ $asignacionActual->repartidor->nombre }}</p>
                                <p class="text-xs text-foreground-muted">{{ $asignacionActual->repartidor->email }}</p>
                                <p class="text-xs text-foreground-muted mt-1">Desde: {{ $asignacionActual->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('usuarios.show', $asignacionActual->repartidor) }}" class="block mt-3 text-center px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-medium transition-colors">
                            Ver Repartidor
                        </a>
                    </div>
                    @endif

                    {{-- Metadata --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Información Adicional</h2>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-foreground-muted">Creado</span>
                                <span class="text-foreground font-medium">{{ $vehiculo->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-foreground-muted">Actualizado</span>
                                <span class="text-foreground font-medium">{{ $vehiculo->updated_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-foreground-muted">ID</span>
                                <span class="font-mono text-foreground text-xs bg-surface-secondary px-2 py-1 rounded">#{{ $vehiculo->id }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Acciones Rápidas</h2>
                        <div class="space-y-2">
                            <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="flex items-center gap-3 px-4 py-3 bg-surface-secondary hover:bg-border rounded-xl text-foreground transition-colors group">
                                <svg class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span class="text-sm font-medium">Editar Vehículo</span>
                            </a>
                            
                            <button type="button" onclick="window.print()" class="w-full flex items-center gap-3 px-4 py-3 bg-surface-secondary hover:bg-border rounded-xl text-foreground transition-colors group">
                                <svg class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                <span class="text-sm font-medium">Imprimir Detalles</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
function confirmDeleteVehiculo() {
    Swal.fire({
        title: 'Eliminar Vehículo',
        html: '<p class="text-gray-600">¿Estás seguro que deseas eliminar este vehículo?</p><p class="text-sm text-gray-500 mt-2">Esta acción no se puede deshacer.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-vehiculo-form').submit();
        }
    });
}

// Cambiar imagen principal
function changeMainImage(url, index) {
    const mainImage = document.getElementById('main-image');
    const currentIndex = document.getElementById('current-image-index');
    
    // Fade out
    mainImage.style.opacity = '0';
    
    setTimeout(() => {
        mainImage.src = url;
        currentIndex.textContent = index;
        
        // Fade in
        mainImage.style.opacity = '1';
        
        // Update thumbnail borders
        document.querySelectorAll('.thumbnail-btn').forEach((btn, i) => {
            if (i === index - 1) {
                btn.classList.remove('border-border');
                btn.classList.add('border-primary');
            } else {
                btn.classList.remove('border-primary');
                btn.classList.add('border-border');
            }
        });
    }, 300);
}
</script>
@endpush
@endsection