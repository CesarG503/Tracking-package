{{-- resources/views/repartidor/mis-envios.blade.php --}}
@extends('layouts.app')

@section('title', 'Mis Envíos')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar-repartidor')

    {{-- Main Content --}}
<<<<<<< HEAD
    <main class="flex-1 overflow-auto bg-background">
        <livewire:repartidor.mis-envios />
    </main>
=======
    <main class="flex-1 flex flex-col-reverse lg:flex-row overflow-hidden">
    
    {{-- Panel Izquierdo - Lista de Envíos --}}
    <div id="shipment-panel" 
        class="w-full lg:w-[420px] glass-card border-r border-foreground/10 flex flex-col overflow-hidden h-[30vh] max-h-[30vh] lg:h-full lg:max-h-full transition-all duration-300 ease-out"
    data-expanded="false">

        
        {{-- Toggle para móvil --}}
        <button id="toggle-shipments" class="lg:hidden w-full flex items-center justify-center py-2 active:scale-95">
            <div class="w-12 h-1 bg-foreground-muted/40 rounded-full"></div>
        </button>

        {{-- Header --}}
        <div class="px-4 lg:px-6 py-3 border-b border-foreground/10"> 
            <h2 class="text-xl font-bold text-foreground mb-2">Mis Envíos de Hoy</h2>
        </div>

        {{-- Componente Livewire (contiene búsqueda, tabs, lista y footer) --}}
        @livewire('repartidor.mis-envios')
    </div>

    {{-- Panel Derecho - Mapa --}}
    <div class="flex-1 flex flex-col p-1 overflow-hidden relative z-0">
        <div class="map-container rounded-2xl lg:rounded-3xl relative overflow-hidden shadow-lg h-[55vh] lg:h-full min-h-[55vh]">
            <div id="mis-envios-map" class="w-full h-full rounded-2xl lg:rounded-3xl" wire:ignore></div>
            
            {{-- Controles del mapa con z-index menor al modal --}}
            <div class="absolute top-3 right-3 lg:top-4 lg:right-4 flex gap-2 z-[400]">
                <button onclick="centerMapMisEnvios()" 
                        class="w-10 h-10 glass-advanced rounded-xl flex items-center justify-center text-foreground hover:bg-surface transition-colors shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</main>
>>>>>>> 4eb51d597af60ca34882f9f23996a872f30f4f68
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

<script>
let mapMisEnvios;
let currentTileLayerMisEnvios = null;
let marcadorActual = null;
let marcadorEmpresa = null;
let routingControl = null;

// Coordenadas de la empresa (San Miguel)
const empresaCoords = [13.439624, -88.157400];

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

function getCurrentTheme() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

function updateMapTheme(theme = getCurrentTheme()) {
    if (!mapMisEnvios || !currentTileLayerMisEnvios) return;
    mapMisEnvios.removeLayer(currentTileLayerMisEnvios);
    const layerConfig = mapLayers[theme];
    currentTileLayerMisEnvios = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(mapMisEnvios);
}

function centerMapMisEnvios() {
    if (mapMisEnvios) {
        mapMisEnvios.setView(empresaCoords, 13);
        // Limpiar ruta si existe
        if (routingControl) {
            mapMisEnvios.removeControl(routingControl);
            routingControl = null;
        }
        if (marcadorActual) {
            mapMisEnvios.removeLayer(marcadorActual);
            marcadorActual = null;
        }
    }
}

function mostrarRutaEnMapa(lat, lng, nombre, direccion, envioId, codigo) {
    console.log('Mostrando ruta:', { lat, lng, nombre, direccion, envioId, codigo });
    
    if (!mapMisEnvios || !lat || !lng) {
        console.error('Mapa no inicializado o coordenadas inválidas');
        return;
    }
    
    // Remover marcador anterior
    if (marcadorActual) {
        mapMisEnvios.removeLayer(marcadorActual);
    }
    
    // Crear nuevo marcador de destino
    marcadorActual = L.marker([lat, lng]).addTo(mapMisEnvios);
    
    // Popup con información
    marcadorActual.bindPopup(`
        <div class="p-1">
            <h3 class="font-bold text-foreground mb-1">Envío ${codigo}</h3>
            <p class="text-sm text-foreground"><strong>${nombre}</strong></p>
            <p class="text-sm text-foreground-muted">${direccion}</p>
        </div>
    `).openPopup();
    
    // Actualizar o crear la ruta
    if (routingControl) {
        routingControl.setWaypoints([
            L.latLng(empresaCoords[0], empresaCoords[1]),
            L.latLng(lat, lng)
        ]);
    } else if (L.Routing && L.Routing.control) {
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(empresaCoords[0], empresaCoords[1]),
                L.latLng(lat, lng)
            ],
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1'
            }),
            lineOptions: {
                styles: [
                    { color: '#4f46e5', weight: 6, opacity: 0.8 },
                    { color: '#a78bfa', weight: 3, opacity: 0.9 }
                ]
            },
            showAlternatives: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            routeWhileDragging: false,
            autoRoute: true,
            language: 'es',
            show: false,
            createMarker: function() { return null; } // No crear marcadores adicionales
        }).addTo(mapMisEnvios);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando mapa...');
    
    // Inicializar mapa
    mapMisEnvios = L.map('mis-envios-map').setView(empresaCoords, 13);
    
    const currentTheme = getCurrentTheme();
    const layerConfig = mapLayers[currentTheme];
    currentTileLayerMisEnvios = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(mapMisEnvios);
    
    // Marcador de la empresa
    marcadorEmpresa = L.marker(empresaCoords)
        .addTo(mapMisEnvios)
        .bindPopup(`
            <div class="text-center">
                <h3 class="font-bold text-lg mb-1">TrackFlow</h3>
                <p class="text-sm text-gray-600">Centro de Distribución</p>
            </div>
        `);
    
    // Observer para cambios de tema
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
        mapMisEnvios.invalidateSize();
    }, 100);
    
    console.log('Mapa inicializado correctamente');
});

// Escuchar evento de Livewire cuando se selecciona un envío
document.addEventListener('livewire:initialized', () => {
    console.log('Livewire initialized, escuchando eventos...');
    
    Livewire.on('shipment-selected', (event) => {
        console.log('Evento shipment-selected recibido:', event);
        const data = Array.isArray(event) ? event[0] : event;
        mostrarRutaEnMapa(data.lat, data.lng, data.nombre, data.direccion, data.id, data.codigo);
    });
});

window.addEventListener('resize', () => {
    if (mapMisEnvios) {
        mapMisEnvios.invalidateSize();
    }
});
</script>
@endpush
@endsection