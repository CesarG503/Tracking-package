{{-- resources/views/repartidor/mis-envios.blade.php --}}
@extends('layouts.app')

@section('title', 'Mis Envíos')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar-repartidor')

    {{-- Main Content --}}
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-background">
        <livewire:repartidor.mis-envios />
    </main>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
let mapMisEnvios;
let currentTileLayerMisEnvios = null;
let marcadorActual = null;

// Coordenadas de San Miguel
const sanMiguelCoords = [13.4833, -88.1833];

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
        mapMisEnvios.setView(sanMiguelCoords, 13);
    }
}

function mostrarEnMapa(lat, lng, nombre, direccion, envioId) {
    if (!mapMisEnvios || !lat || !lng) return;
    
    // Remover marcador anterior
    if (marcadorActual) {
        mapMisEnvios.removeLayer(marcadorActual);
    }
    
    // Crear nuevo marcador
    marcadorActual = L.marker([lat, lng]).addTo(mapMisEnvios);
    
    // Popup
    marcadorActual.bindPopup(`
        <div class="p-2">
            <h3 class="font-bold text-foreground mb-1">Envío #${envioId}</h3>
            <p class="text-sm text-foreground"><strong>${nombre}</strong></p>
            <p class="text-sm text-foreground-muted">${direccion}</p>
        </div>
    `).openPopup();
    
    // Centrar mapa en el marcador
    mapMisEnvios.setView([lat, lng], 15);
}

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa
    mapMisEnvios = L.map('mis-envios-map').setView(sanMiguelCoords, 13);
    
    const currentTheme = getCurrentTheme();
    const layerConfig = mapLayers[currentTheme];
    currentTileLayerMisEnvios = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(mapMisEnvios);
    
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
});

window.addEventListener('resize', () => {
    if (mapMisEnvios) {
        mapMisEnvios.invalidateSize();
    }
});
</script>
@endpush
@endsection