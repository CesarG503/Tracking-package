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
let rutaMap;
let currentTileLayer = null;
let marcadoresEnvios = {}; 
let marcadorEmpresa = null;

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

// Iconos personalizados para cada estado
const iconos = {
    pendiente: L.divIcon({
        html: '<div class="w-8 h-8 rounded-full bg-warning shadow-lg flex items-center justify-center text-white font-bold border-2 border-white">📦</div>',
        className: 'custom-div-icon',
        iconSize: [32, 32],
        iconAnchor: [16, 16]
    }),
    en_ruta: L.divIcon({
        html: '<div class="w-8 h-8 rounded-full bg-primary shadow-lg flex items-center justify-center text-white font-bold border-2 border-white">🚚</div>',
        className: 'custom-div-icon',
        iconSize: [32, 32],
        iconAnchor: [16, 16]
    })
};

function getCurrentTheme() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

function updateMapTheme(theme = getCurrentTheme()) {
    if (!rutaMap || !currentTileLayer) return;
    rutaMap.removeLayer(currentTileLayer);
    const layerConfig = mapLayers[theme];
    currentTileLayer = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(rutaMap);
}

function centerMap() {
    if (rutaMap) {
        rutaMap.setView(empresaCoords, 13);
    }
}

function ajustarVistaATodosLosMarcadores() {
    if (!rutaMap) return;
    
    const marcadores = Object.values(marcadoresEnvios);
    if (marcadores.length === 0) {
        centerMap();
        return;
    }
    
    const group = L.featureGroup([marcadorEmpresa, ...marcadores]);
    rutaMap.fitBounds(group.getBounds().pad(0.1));
}

function cargarMarcadoresIniciales(envios) {
    console.log('Cargando marcadores iniciales:', envios);
    
    if (!rutaMap) {
        console.error('Mapa no inicializado');
        return;
    }
    
    // Limpiar marcadores existentes
    Object.values(marcadoresEnvios).forEach(marker => rutaMap.removeLayer(marker));
    marcadoresEnvios = {};
    
    // Agregar nuevos marcadores
    envios.forEach(envio => {
        agregarMarcador(envio);
    });
    
    actualizarEstadisticasMapa(envios);
    ajustarVistaATodosLosMarcadores();
}

function agregarMarcador(envio) {
    if (!rutaMap || !envio.lat || !envio.lng) return;
    
    console.log('Agregando marcador:', envio);
    
    const icono = iconos[envio.estado] || iconos.pendiente;
    const marker = L.marker([envio.lat, envio.lng], { icon: icono })
        .addTo(rutaMap);
    
    marker.bindPopup(`
        <div class="p-3">
            <h3 class="font-bold text-foreground mb-2">Envío ${envio.codigo || envio.id}</h3>
            <div class="space-y-1 text-sm">
                <p class="text-foreground"><strong>Cliente:</strong> ${envio.destinatario}</p>
                <p class="text-foreground-muted">${envio.direccion}</p>
                ${envio.telefono ? `<p class="text-foreground-muted">📞 ${envio.telefono}</p>` : ''}
                <span class="inline-block mt-2 px-2 py-1 rounded text-xs font-semibold ${
                    envio.estado === 'pendiente' ? 'bg-warning text-white' : 
                    envio.estado === 'en_ruta' ? 'bg-primary text-white' : 'bg-gray-500 text-white'
                }">
                    ${envio.estado === 'pendiente' ? 'Pendiente' : envio.estado === 'en_ruta' ? 'En Ruta' : envio.estado}
                </span>
            </div>
        </div>
    `);
    
    marcadoresEnvios[envio.id] = marker;
}

function eliminarMarcador(envioId) {
    console.log('Eliminando marcador:', envioId);
    
    if (marcadoresEnvios[envioId]) {
        rutaMap.removeLayer(marcadoresEnvios[envioId]);
        delete marcadoresEnvios[envioId];
    }
}

function actualizarMarcador(envio) {
    console.log('Actualizando marcador:', envio);
    
    // Eliminar marcador antiguo y crear uno nuevo con el estado actualizado
    eliminarMarcador(envio.id);
    agregarMarcador(envio);
}

function actualizarEstadisticasMapa(envios) {
    const pendientes = envios.filter(e => e.estado === 'pendiente').length;
    const enRuta = envios.filter(e => e.estado === 'en_ruta').length;
    const total = envios.length;
    
    document.getElementById('map-stats-pendientes').textContent = pendientes;
    document.getElementById('map-stats-enruta').textContent = enRuta;
    document.getElementById('map-stats-total').textContent = total;
}

// Inicializar mapa
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando mapa de rutas...');
    
    rutaMap = L.map('ruta-map').setView(empresaCoords, 13);
    
    const currentTheme = getCurrentTheme();
    const layerConfig = mapLayers[currentTheme];
    currentTileLayer = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(rutaMap);
    
    // Marcador de la empresa
    marcadorEmpresa = L.marker(empresaCoords)
        .addTo(rutaMap)
        .bindPopup(`
            <div class="text-center p-2">
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
        rutaMap.invalidateSize();
    }, 100);
    
    console.log('Mapa inicializado correctamente');
});

// Escuchar eventos de Livewire
document.addEventListener('livewire:initialized', () => {
    console.log('Livewire initialized - escuchando eventos del mapa...');
    
    // Nuevo envío asignado
    Livewire.on('nuevo-envio-mapa', (event) => {
        console.log('Nuevo envío detectado:', event);
        const envio = Array.isArray(event) ? event[0] : event;
        agregarMarcador(envio);
        
        // Actualizar estadísticas
        const enviosActuales = Object.values(marcadoresEnvios).map(m => ({
            estado: m._icon.querySelector('.bg-warning') ? 'pendiente' : 'en_ruta'
        }));
        actualizarEstadisticasMapa([...enviosActuales, envio]);
        
        // Notificación visual
        mostrarNotificacion('Nuevo envío asignado', 'success');
    });
    
    // Envío eliminado (entregado, cancelado, etc)
    Livewire.on('eliminar-envio-mapa', (event) => {
        console.log('Envío eliminado:', event);
        const data = Array.isArray(event) ? event[0] : event;
        eliminarMarcador(data.id);
        
        mostrarNotificacion('Envío actualizado', 'info');
    });
    
    // Estado actualizado
    Livewire.on('actualizar-estado-envio-mapa', (event) => {
        console.log('Estado actualizado:', event);
        const envio = Array.isArray(event) ? event[0] : event;
        actualizarMarcador(envio);
        
        mostrarNotificacion(`Envío #${envio.codigo || envio.id} → ${envio.estado}`, 'info');
    });
});

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'info') {
    const colores = {
        success: 'bg-success',
        info: 'bg-primary',
        warning: 'bg-warning',
        error: 'bg-danger'
    };
    
    const notif = document.createElement('div');
    notif.className = `fixed top-4 right-4 z-[9999] ${colores[tipo]} text-white px-4 py-3 rounded-xl shadow-lg animate-in fade-in slide-in-from-right-4 duration-300`;
    notif.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">${mensaje}</span>
        </div>
    `;
    
    document.body.appendChild(notif);
    
    setTimeout(() => {
        notif.remove();
    }, 3000);
}

window.addEventListener('resize', () => {
    if (rutaMap) {
        rutaMap.invalidateSize();
    }
});
</script>
@endpush
@endsection