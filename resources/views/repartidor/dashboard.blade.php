@extends('layouts.app')

@section('title', 'Dashboard Repartidor')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar-repartidor')

    {{-- Main Content --}}
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-background">
        <livewire:repartidor.dashboard />
    </main>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
let map;
let currentTileLayer = null;
let mapInitialized = false;
let marcadores = {}; // Objeto para almacenar los marcadores por ID

const mapLayers = {
    light: {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '© OpenStreetMap contributors'
    },
    dark: {
        url: 'https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png',
        attribution: '© Stadia Maps © OpenStreetMap contributors'
    }
};

// Iconos personalizados para diferentes estados
const iconos = {
    pendiente: L.divIcon({
        html: `<div class="relative">
                <div class="w-8 h-8 bg-warning rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                    </svg>
                </div>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-white"></div>
              </div>`,
        className: 'custom-marker',
        iconSize: [32, 40],
        iconAnchor: [16, 40],
        popupAnchor: [0, -40]
    }),
    en_ruta: L.divIcon({
        html: `<div class="relative">
                <div class="w-8 h-8 bg-primary rounded-full border-4 border-white shadow-lg flex items-center justify-center animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 13l-4-4 1.5-1.5L9 12l4.5-4.5L15 9l-6 6z"/>
                    </svg>
                </div>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-white"></div>
              </div>`,
        className: 'custom-marker',
        iconSize: [32, 40],
        iconAnchor: [16, 40],
        popupAnchor: [0, -40]
    })
};

function getCurrentTheme() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

function updateMapTheme(theme = getCurrentTheme()) {
    if (!map || !currentTileLayer) return;
    map.removeLayer(currentTileLayer);
    const layerConfig = mapLayers[theme];
    currentTileLayer = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(map);
}

function centerMap() {
    if (map) {
        map.setView([13.7, -89.2], 12);
    }
}

function initializeMap() {
    if (mapInitialized) {
        console.log('Map already initialized');
        return;
    }

    const mapContainer = document.getElementById('ruta-map');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }

    mapContainer.innerHTML = '';
    
    map = L.map('ruta-map').setView([13.7, -89.2], 12);
    
    const currentTheme = getCurrentTheme();
    const layerConfig = mapLayers[currentTheme];
    currentTileLayer = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(map);
    
    mapInitialized = true;
    
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                updateMapTheme(getCurrentTheme());
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    window.addEventListener('themeChanged', (event) => {
        updateMapTheme(event.detail.theme);
    });

    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

// Cargar marcadores iniciales
function cargarMarcadoresIniciales(envios) {
    if (!map) {
        console.error('Map not initialized');
        return;
    }

    // Limpiar marcadores existentes
    Object.values(marcadores).forEach(marker => marker.remove());
    marcadores = {};

    // Agregar nuevos marcadores
    envios.forEach(envio => {
        agregarMarcador(envio);
    });

    // Ajustar vista si hay marcadores
    if (envios.length > 0) {
        ajustarVistaATodosLosMarcadores();
    }

    // Actualizar stats
    actualizarStatsMap();
}

// Agregar o actualizar un marcador
function agregarMarcador(envio) {
    if (!map) return;

    const { id, lat, lng, estado, destinatario, direccion, telefono } = envio;

    // Si el marcador ya existe, eliminarlo primero
    if (marcadores[id]) {
        marcadores[id].remove();
    }

    // Crear nuevo marcador
    const icono = iconos[estado] || iconos.pendiente;
    const marker = L.marker([lat, lng], { icon: icono }).addTo(map);

    // Crear popup
    const estadoTexto = estado === 'pendiente' ? 'Pendiente' : 'En Ruta';
    const estadoColor = estado === 'pendiente' ? 'warning' : 'primary';
    
    const popupContent = `
        <div class="p-2 min-w-[200px]">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-foreground">Envío #${id}</h3>
                <span class="glass glass-${estadoColor} glass-subtle px-2 py-0.5 rounded text-xs font-semibold">
                    ${estadoTexto}
                </span>
            </div>
            <div class="space-y-1 text-sm">
                <p class="text-foreground"><strong>Destinatario:</strong> ${destinatario}</p>
                <p class="text-foreground-muted"><strong>Dirección:</strong> ${direccion}</p>
                ${telefono ? `<p class="text-foreground-muted"><strong>Teléfono:</strong> ${telefono}</p>` : ''}
            </div>
            <div class="mt-3 flex gap-2">
                <button onclick="verDetalleEnvio(${id})" class="flex-1 glass glass-blue px-3 py-1.5 rounded-lg text-xs font-medium hover:shadow-md transition-all">
                    Ver detalles
                </button>
                <button onclick="iniciarNavegacion(${lat}, ${lng})" class="flex-1 glass glass-green px-3 py-1.5 rounded-lg text-xs font-medium hover:shadow-md transition-all">
                    Navegar
                </button>
            </div>
        </div>
    `;

    marker.bindPopup(popupContent, {
        maxWidth: 300,
        className: 'custom-popup'
    });

    // Guardar marcador
    marcadores[id] = marker;

    // Actualizar stats
    actualizarStatsMap();
}

// Eliminar un marcador
function eliminarMarcador(envioId) {
    if (marcadores[envioId]) {
        marcadores[envioId].remove();
        delete marcadores[envioId];
        actualizarStatsMap();
    }
}

// Ajustar vista para mostrar todos los marcadores
function ajustarVistaATodosLosMarcadores() {
    if (!map || Object.keys(marcadores).length === 0) return;

    const bounds = L.latLngBounds(
        Object.values(marcadores).map(marker => marker.getLatLng())
    );
    
    map.fitBounds(bounds, { padding: [50, 50] });
}

// Actualizar estadísticas del mapa
function actualizarStatsMap() {
    const keys = Object.keys(marcadores);
    const total = keys.length;
    
    let pendientes = 0;
    let enRuta = 0;
    
    // Contar por estado
    keys.forEach(id => {
        const marker = marcadores[id];
    });

    document.getElementById('map-stats-total').textContent = total;
    document.getElementById('map-stats-pendientes').textContent = pendientes;
    document.getElementById('map-stats-enruta').textContent = enRuta;
}

// Funciones auxiliares
function verDetalleEnvio(envioId) {
    window.location.href = `/repartidor/envios/${envioId}`;
}

function iniciarNavegacion(lat, lng) {
    // Abrir en Google Maps o la app de mapas predeterminada
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
}

// Escuchar eventos de Livewire para actualizar marcadores
document.addEventListener('livewire:initialized', () => {
    // Actualizar marcador
    Livewire.on('actualizar-marcador-mapa', (event) => {
        const data = event[0] || event;
        console.log('Actualizando marcador:', data);
        agregarMarcador(data);
        
        // Animar hacia el nuevo marcador
        if (data.accion === 'nuevo') {
            map.setView([data.lat, data.lng], 15, {
                animate: true,
                duration: 1
            });
            
            // Abrir popup automáticamente
            setTimeout(() => {
                if (marcadores[data.id]) {
                    marcadores[data.id].openPopup();
                }
            }, 500);
        }
    });

    // Eliminar marcador
    Livewire.on('eliminar-marcador-mapa', (event) => {
        const data = event[0] || event;
        console.log('Eliminando marcador:', data.id);
        eliminarMarcador(data.id);
    });
});

// Inicializar
document.addEventListener('DOMContentLoaded', initializeMap);
document.addEventListener('livewire:navigated', initializeMap);

window.addEventListener('resize', () => {
    if (map) {
        map.invalidateSize();
    }
});
</script>

<style>
.custom-marker {
    background: transparent;
    border: none;
}

.custom-popup .leaflet-popup-content-wrapper {
    background: var(--glass-bg, rgba(255, 255, 255, 0.9));
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.custom-popup .leaflet-popup-tip {
    background: var(--glass-bg, rgba(255, 255, 255, 0.9));
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endpush
@endsection