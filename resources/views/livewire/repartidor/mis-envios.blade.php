{{-- resources/views/livewire/repartidor/mis-envios.blade.php --}}
{{-- TODO: cambiar mejores estilos y evitar que se cierre al abrir la ventana
    de envios al estar en moviles --}}
<div wire:poll.5s class="flex h-screen overflow-hidden">
    <div class="flex-1 flex flex-col-reverse lg:flex-row overflow-hidden">
        {{-- Panel Izquierdo - Lista de Envíos --}}
        <div id="shipment-panel" class="w-full lg:w-[420px] glass-sidebar flex flex-col overflow-hidden h-[40vh] max-h-[40vh] lg:h-full lg:max-h-full transition-all duration-300 ease-out">
            {{-- Toggle para móvil --}}
            <button id="toggle-shipments" class="lg:hidden w-full flex items-center justify-center py-2 active:scale-95">
                <div class="w-12 h-1 bg-foreground-muted/40 rounded-full"></div>
            </button>

            {{-- Header --}}
            <div class="p-4 lg:p-6 border-b border-foreground/10">
                <h2 class="text-xl font-bold text-foreground mb-4">Mis Envíos de Hoy</h2>
                
                {{-- Búsqueda --}}
                <div class="relative mb-4">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="busqueda"
                        placeholder="Buscar envío..."
                        class="w-full px-4 py-2 pl-10 glass-subtle rounded-xl text-foreground placeholder-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary/50">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                {{-- Filtros por Estado --}}
                <div class="flex gap-2 flex-wrap">
                    <button 
                        wire:click="$set('estadoSeleccionado', 'todos')"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $estadoSeleccionado === 'todos' ? 'bg-foreground text-background' : 'glass-subtle text-foreground-muted hover:text-foreground' }}">
                        Todos
                    </button>
                    <button 
                        wire:click="$set('estadoSeleccionado', 'pendiente')"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $estadoSeleccionado === 'pendiente' ? 'bg-warning text-white' : 'glass-subtle text-foreground-muted hover:text-foreground' }}">
                        Pendientes
                    </button>
                    <button 
                        wire:click="$set('estadoSeleccionado', 'en_ruta')"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $estadoSeleccionado === 'en_ruta' ? 'bg-primary text-white' : 'glass-subtle text-foreground-muted hover:text-foreground' }}">
                        En Ruta
                    </button>
                    <button 
                        wire:click="$set('estadoSeleccionado', 'entregado')"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $estadoSeleccionado === 'entregado' ? 'bg-success text-white' : 'glass-subtle text-foreground-muted hover:text-foreground' }}">
                        Entregados
                    </button>
                </div>
            </div>

            {{-- Lista de Envíos --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                @forelse($this->enviosHoy as $envio)
                    <div class="glass-subtle rounded-xl p-4 hover:shadow-md transition-all cursor-pointer border border-foreground/10 bg-gradient-to-r from-primary/5 to-transparent"
                         onclick="mostrarEnMapa({{ $envio->lat }}, {{ $envio->lng }}, '{{ addslashes($envio->destinatario_nombre) }}', '{{ addslashes($envio->destinatario_direccion) }}', {{ $envio->id }})">
                        
                        {{-- Header del Envío --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-foreground">Envío #{{ $envio->id }}</h3>
                                    <span class="glass-subtle px-2 py-0.5 rounded text-xs font-semibold
                                        {{ $envio->estado === 'pendiente' ? 'text-warning' : '' }}
                                        {{ $envio->estado === 'en_ruta' ? 'text-primary' : '' }}
                                        {{ $envio->estado === 'entregado' ? 'text-success' : '' }}
                                        {{ $envio->estado === 'devuelto' ? 'text-danger' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $envio->estado)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-foreground-muted">{{ $envio->codigo }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-lg 
                                {{ $envio->estado === 'entregado' ? 'glass-green' : '' }}
                                {{ $envio->estado === 'en_ruta' ? 'glass-blue' : '' }}
                                {{ $envio->estado === 'pendiente' ? 'glass-amber' : '' }}
                                {{ $envio->estado === 'devuelto' ? 'glass-red' : '' }}
                                flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Información del Destinatario --}}
                        <div class="space-y-2 mb-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-foreground-muted mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-sm text-foreground">{{ $envio->destinatario_nombre }}</p>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-foreground-muted mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-sm text-foreground-muted line-clamp-2">{{ $envio->destinatario_direccion }}</p>
                            </div>
                            @if($envio->destinatario_telefono)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <p class="text-sm text-foreground-muted">{{ $envio->destinatario_telefono }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Acciones Rápidas --}}
                        <div class="flex gap-2 pt-3 border-t border-foreground/5" onclick="event.stopPropagation()">
                            {{-- Botón de Mensajes --}}
                            <a href="{{ route('tracking', $envio->codigo) }}" 
                               class="relative flex items-center justify-center w-10 h-10 rounded-lg glass-subtle text-foreground hover:bg-primary/10 hover:text-primary transition-all"
                               title="Mensajes con el cliente">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                @if($envio->mensajes_cliente_count > 0)
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-danger text-white text-[10px] font-bold flex items-center justify-center rounded-full">
                                        {{ $envio->mensajes_cliente_count }}
                                    </span>
                                @endif
                            </a>

                            @if($envio->estado === 'pendiente')
                                <button 
                                    wire:click="cambiarEstado({{ $envio->id }}, 'en_ruta')"
                                    class="flex-1 glass-blue text-primary px-3 py-2 rounded-lg text-sm font-medium hover:shadow-md transition-all">
                                    Iniciar Ruta
                                </button>
                            @elseif($envio->estado === 'en_ruta')
                                <button 
                                    wire:click="cambiarEstado({{ $envio->id }}, 'entregado')"
                                    class="flex-1 glass-green text-success px-3 py-2 rounded-lg text-sm font-medium hover:shadow-md transition-all">
                                    Entregado
                                </button>
                                <button 
                                    wire:click="cambiarEstado({{ $envio->id }}, 'devuelto')"
                                    class="flex-1 glass-red text-danger px-3 py-2 rounded-lg text-sm font-medium hover:shadow-md transition-all">
                                    Devolver
                                </button>
                            @else
                                <div class="flex-1 text-center text-sm text-foreground-muted py-2">
                                    Estado: {{ ucfirst($envio->estado) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-xl glass-subtle flex items-center justify-center">
                            <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-foreground-muted">No hay envíos para hoy</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            @if($this->enviosHoy->hasPages())
                <div class="p-4 border-t border-foreground/10">
                    {{ $this->enviosHoy->links() }}
                </div>
            @endif


        </div>

        {{-- Panel Derecho - Mapa --}}
        <div class="flex-1 flex flex-col p-3 overflow-hidden">
            <div class="relative h-full rounded-xl overflow-hidden" wire:ignore>
                <div id="mis-envios-map" class="w-full h-full rounded-xl"></div>
                
                {{-- Controles del mapa --}}
                <div class="absolute top-3 right-3 flex gap-2 z-[1000]">
                    <button onclick="centerMapMisEnvios()" class="w-10 h-10 glass-advanced rounded-xl flex items-center justify-center text-foreground hover:bg-surface transition-colors shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Actualizar Estado --}}
    @if($mostrarModal)
        @teleport('body')
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4" wire:click="cerrarModal">
                <div class="glass-card rounded-2xl p-6 max-w-md w-full" wire:click.stop onclick="event.stopPropagation()">
                    <h3 class="text-xl font-bold text-foreground mb-4">
                        {{ $nuevoEstado === 'entregado' ? 'Confirmar Entrega' : 'Confirmar Devolución' }}
                    </h3>

                    <form wire:submit.prevent="actualizarEstado">
                        {{-- Foto de Entrega --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-2">
                                Foto de Entrega {{ $nuevoEstado === 'entregado' ? '(Requerida)' : '(Opcional)' }}
                            </label>
                            <input 
                                type="file" 
                                wire:model="foto_entrega"
                                accept="image/*"
                                capture="environment"
                                class="w-full px-4 py-2 glass-subtle rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50">
                            @error('foto_entrega') 
                                <span class="text-danger text-xs mt-1">{{ $message }}</span> 
                            @enderror

                            @if ($foto_entrega)
                                <div class="mt-3">
                                    <p class="text-sm text-foreground-muted mb-2">Vista previa:</p>
                                    <img src="{{ $foto_entrega->temporaryUrl() }}" class="w-full h-48 object-cover rounded-xl">
                                </div>
                            @endif
                        </div>

                        {{-- Observaciones --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-2">
                                Observaciones
                            </label>
                            <textarea 
                                wire:model="observaciones"
                                rows="3"
                                placeholder="Agregar comentarios sobre la entrega..."
                                class="w-full px-4 py-2 glass-subtle rounded-xl text-foreground placeholder-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary/50 resize-none"></textarea>
                            @error('observaciones') 
                                <span class="text-danger text-xs mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-3">
                            <button 
                                type="button"
                                wire:click="cerrarModal"
                                class="flex-1 glass-subtle text-foreground px-4 py-2 rounded-xl font-medium hover:shadow-md transition-all">
                                Cancelar
                            </button>
                            <button 
                                type="submit"
                                class="flex-1 {{ $nuevoEstado === 'entregado' ? 'glass-green text-success' : 'glass-red text-danger' }} px-4 py-2 rounded-xl font-medium hover:shadow-md transition-all">
                                Confirmar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endteleport
    @endif

    {{-- TODO: cambiar notificacion --}}
    {{-- Test de notificación --}}
    <div x-data="{ 
            show: false, 
            mensaje: '',
            tipo: ''
         }"
         @envio-actualizado.window="
            show = true;
            mensaje = $event.detail.mensaje;
            tipo = $event.detail.tipo;
            setTimeout(() => { show = false }, 3000);
         "
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-[9999] glass glass-strong rounded-xl p-4 shadow-2xl max-w-sm"
         style="display: none;">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg glass glass-green flex items-center justify-center">
                <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="font-semibold text-foreground" x-text="mensaje"></p>
        </div>
    </div>
</div>

<script>
let mapMisEnvios;
let routeLineMisEnvios;
let companyMarkerMisEnvios;
let destinationMarkerMisEnvios;
let currentDestLatMisEnvios = null;
let currentDestLngMisEnvios = null;
let routingControlMisEnvios = null;
let currentTileLayerMisEnvios = null;

// Map tile layers for different themes
const mapLayersMisEnvios = {
    light: {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    },
    dark: {
        url: 'https://tiles.stadiamaps.com/tiles/stamen_toner_dark/{z}/{x}/{y}{r}.png',
        attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>'
    }
};

function getCurrentThemeMisEnvios() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

function updateMapThemeMisEnvios(theme = getCurrentThemeMisEnvios()) {
    if (!mapMisEnvios || !currentTileLayerMisEnvios) return;
    
    // Remove current layer
    mapMisEnvios.removeLayer(currentTileLayerMisEnvios);
    
    // Add new layer based on theme
    const layerConfig = mapLayersMisEnvios[theme];
    currentTileLayerMisEnvios = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(mapMisEnvios);
}

function initMapMisEnvios() {
    // Coordenadas de la empresa
    const empresaLat = {{ $empresaCoordenadas['lat'] }};
    const empresaLng = {{ $empresaCoordenadas['lng'] }};
    
    console.log('Empresa coords (Mis Envíos):', empresaLat, empresaLng);
    
    // Inicializar el mapa centrado en la empresa
    mapMisEnvios = L.map('mis-envios-map').setView([empresaLat, empresaLng], 13);
    
    // Agregar capa inicial basada en el tema actual
    const currentTheme = getCurrentThemeMisEnvios();
    const layerConfig = mapLayersMisEnvios[currentTheme];
    currentTileLayerMisEnvios = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(mapMisEnvios);
    
    // Listen for theme changes via MutationObserver
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const newTheme = getCurrentThemeMisEnvios();
                updateMapThemeMisEnvios(newTheme);
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    // Listen for custom theme change events
    window.addEventListener('themeChanged', (event) => {
        updateMapThemeMisEnvios(event.detail.theme);
    });
    
    // Marcador de la empresa
    companyMarkerMisEnvios = L.marker([empresaLat, empresaLng])
        .addTo(mapMisEnvios)
        .bindPopup(`
            <div class="text-center">
                <h3 class="font-bold text-lg mb-1">TrackFlow</h3>
                <p class="text-sm text-gray-600">Centro de Distribución</p>
            </div>
        `);
    
    // Hacer el mapa responsive
    setTimeout(() => {
        mapMisEnvios.invalidateSize();
    }, 100);
}

function centerMapMisEnvios() {
    const empresaLat = {{ $empresaCoordenadas['lat'] }};
    const empresaLng = {{ $empresaCoordenadas['lng'] }};
    if (currentDestLatMisEnvios !== null && currentDestLngMisEnvios !== null) {
        const bounds = L.latLngBounds([
            [empresaLat, empresaLng],
            [currentDestLatMisEnvios, currentDestLngMisEnvios]
        ]);
        mapMisEnvios.fitBounds(bounds, { padding: [50, 50] });
    } else {
        mapMisEnvios.setView([empresaLat, empresaLng], 13);
    }
}

function mostrarEnMapa(lat, lng, nombre, direccion, envioId) {
    if (!mapMisEnvios) return;

    const empresaLat = {{ $empresaCoordenadas['lat'] }};
    const empresaLng = {{ $empresaCoordenadas['lng'] }};

    currentDestLatMisEnvios = lat;
    currentDestLngMisEnvios = lng;

    // Remover marcador de destino anterior si existe
    if (destinationMarkerMisEnvios) {
        mapMisEnvios.removeLayer(destinationMarkerMisEnvios);
    }

    // Agregar marcador de destino
    destinationMarkerMisEnvios = L.marker([lat, lng])
        .addTo(mapMisEnvios)
        .bindPopup(`
            <div class="text-center">
                <h3 class="font-bold text-lg mb-1">${nombre}</h3>
                <p class="text-sm text-gray-600">${direccion}</p>
            </div>
        `);

    // Usar Leaflet Routing Machine con OSRM para mostrar la ruta
    if (routingControlMisEnvios) {
        routingControlMisEnvios.setWaypoints([
            L.latLng(empresaLat, empresaLng),
            L.latLng(lat, lng)
        ]);
    } else if (L.Routing && L.Routing.control) {
        routingControlMisEnvios = L.Routing.control({
            waypoints: [
                L.latLng(empresaLat, empresaLng),
                L.latLng(lat, lng)
            ],
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1'
            }),
            lineOptions: {
                styles: [
                    { color: '#06b6d4', weight: 6, opacity: 0.8 },
                    { color: '#22d3ee', weight: 3, opacity: 0.9 }
                ]
            },
            showAlternatives: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            routeWhileDragging: false,
            autoRoute: true,
            language: 'es',
            show: false
        }).addTo(mapMisEnvios);
    } else {
        console.warn('Leaflet Routing Machine no cargó; usando línea directa como fallback.');
        if (routeLineMisEnvios) {
            mapMisEnvios.removeLayer(routeLineMisEnvios);
            routeLineMisEnvios = null;
        }
        routeLineMisEnvios = L.polyline([
            [empresaLat, empresaLng],
            [lat, lng]
        ], { color: '#06b6d4', weight: 4, opacity: 0.9 }).addTo(mapMisEnvios);
        const bounds = L.latLngBounds([[empresaLat, empresaLng], [lat, lng]]);
        mapMisEnvios.fitBounds(bounds, { padding: [50, 50] });
    }
}

// Inicializar el mapa cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    initMapMisEnvios();
    
    // Toggle del panel de envíos en móvil
    const toggleBtn = document.getElementById('toggle-shipments');
    const shipmentPanel = document.getElementById('shipment-panel');
    let isExpanded = false;
    
    if (toggleBtn && shipmentPanel && window.innerWidth < 1024) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 1024) return;
            
            isExpanded = !isExpanded;
            
            if (isExpanded) {
                shipmentPanel.style.height = '70vh';
                shipmentPanel.style.maxHeight = '70vh';
            } else {
                shipmentPanel.style.height = '40vh';
                shipmentPanel.style.maxHeight = '40vh';
            }
        });
        
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                isExpanded = false;
                shipmentPanel.style.height = '';
                shipmentPanel.style.maxHeight = '';
            }
        });
    }
});

// Reinicializar el mapa cuando se redimensiona la ventana
window.addEventListener('resize', () => {
    if (mapMisEnvios) {
        mapMisEnvios.invalidateSize();
    }
});
</script>