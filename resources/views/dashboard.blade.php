@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col-reverse lg:flex-row overflow-hidden">

        
        <!-- Left Panel - Package List -->
        <div id="shipment-panel" class="w-full lg:w-[420px] glass-sidebar border-r border-white/20 flex flex-col overflow-hidden h-[27vh] max-h-[27vh] lg:h-full lg:max-h-full transition-all duration-300 ease-out">
            <button id="toggle-shipments" class="lg:hidden w-full flex items-center justify-center b active:scale-95">`
                <div class="w-12 h-1 bg-foreground-muted/40 rounded-full"></div>
            </button>    
            <!-- Header -->
            <div class="px-4 lg:px-6 pb-3 transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-foreground dark:text-foreground">Seguimiento de Envios</h1>
                    <button class="w-10 h-10 rounded-xl bg-surface-secondary dark:bg-surface-secondary flex items-center justify-center text-foreground-muted dark:text-foreground-muted hover:bg-border dark:hover:bg-border transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Livewire Component -->
            @livewire('shipment-list')
        </div>

        <!-- Right Panel - Map & Details -->
        <div class="flex-1 flex flex-col p-3 overflow-hidden">
            <!-- Map Container with Overlay Details -->
            <div class="map-container rounded-2xl lg:rounded-3xl relative overflow-hidden shadow-lg lg:h-[100vh] min-h-[70vh]  lg:min-h-[70vh]">
                <div id="map" class="w-full h-full rounded-2xl lg:rounded-3xl"></div>
                
                <!-- Map Controls -->
                <div class="absolute top-3 right-3 lg:top-4 lg:right-4 flex gap-2 z-[1000]">
                    <button onclick="centerMap()" class="w-10 h-10 glass-advanced rounded-xl flex items-center justify-center text-foreground hover:bg-surface transition-colors shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Order Details Card - Livewire Component -->
                @livewire('order-details')

            </div>
        </div>
    </main>
</div>
@endsection

<script>
let map;
let routeLine;
let companyMarker;
let destinationMarker;
let currentDestLat = null;
let currentDestLng = null;
let routingControl = null;
let currentTileLayer = null;

// Map tile layers for different themes
const mapLayers = {
    light: {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

    },
    dark: {
        url: 'https://tiles.stadiamaps.com/tiles/stamen_toner_dark/{z}/{x}/{y}{r}.png',

    }
};

function getCurrentTheme() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

function updateMapTheme(theme = getCurrentTheme()) {
    if (!map || !currentTileLayer) return;
    
    // Remove current layer
    map.removeLayer(currentTileLayer);
    
    // Add new layer based on theme
    const layerConfig = mapLayers[theme];
    currentTileLayer = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(map);
}

function initMap() {
    // Coordenadas de la empresa
    const empresaLat = {{ $empresaCoordenadas['lat'] }};
    const empresaLng = {{ $empresaCoordenadas['lng'] }};
    
    console.log('Empresa coords:', empresaLat, empresaLng);
    
    // Inicializar el mapa centrado en la empresa
    map = L.map('map').setView([empresaLat, empresaLng], 13);
    
    // Agregar capa inicial basada en el tema actual
    const currentTheme = getCurrentTheme();
    const layerConfig = mapLayers[currentTheme];
    currentTileLayer = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 19,
    }).addTo(map);
    
    // Listen for theme changes via MutationObserver
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const newTheme = getCurrentTheme();
                updateMapTheme(newTheme);
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    // Listen for custom theme change events
    window.addEventListener('themeChanged', (event) => {
        updateMapTheme(event.detail.theme);
    });
    
    // Marcador de la empresa (icono por defecto)
    companyMarker = L.marker([empresaLat, empresaLng])
        .addTo(map) 
        .bindPopup(`
            <div class="text-center">
                <h3 class="font-bold text-lg mb-1">TrackFlow</h3>
                <p class="text-sm text-gray-600">Centro de Distribución</p>
            </div>
        `);
    
    // Hacer el mapa responsive
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

function centerMap() {
    const empresaLat = {{ $empresaCoordenadas['lat'] }};
    const empresaLng = {{ $empresaCoordenadas['lng'] }};
    if (currentDestLat !== null && currentDestLng !== null) {
        const bounds = L.latLngBounds([
            [empresaLat, empresaLng],
            [currentDestLat, currentDestLng]
        ]);
        map.fitBounds(bounds, { padding: [50, 50] });
    } else {
        map.setView([empresaLat, empresaLng], 13);
    }
}

// Inicializar el mapa cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initMap);

// Reinicializar el mapa cuando se redimensiona la ventana
window.addEventListener('resize', () => {
    if (map) {
        map.invalidateSize();
    }
});

function confirmLogout() {
    Swal.fire({
        title: 'Cerrar Sesion',
        text: 'Estas seguro que deseas cerrar sesion?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Si, cerrar sesion',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}


</script>

<script>
// Toggle acordeón de envíos
document.addEventListener('DOMContentLoaded', () => {
    const empresaLat = {{ $empresaCoordenadas['lat'] }};
    const empresaLng = {{ $empresaCoordenadas['lng'] }};

    const updateRoute = (lat, lng, nombre, direccion) => {
        if (!map) return;

        currentDestLat = lat;
        currentDestLng = lng;

        // Usar Leaflet Routing Machine con OSRM para indicaciones en español
        if (routingControl) {
            routingControl.setWaypoints([
                L.latLng(empresaLat, empresaLng),
                L.latLng(lat, lng)
            ]);
        } else if (L.Routing && L.Routing.control) {
            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(empresaLat, empresaLng),
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
                show: false
            }).addTo(map);
        } else {
            console.warn('Leaflet Routing Machine no cargó; usando línea directa como fallback.');
            if (routeLine) {
                map.removeLayer(routeLine);
                routeLine = null;
            }
            routeLine = L.polyline([
                [empresaLat, empresaLng],
                [lat, lng]
            ], { color: '#3b82f6', weight: 4, opacity: 0.9 }).addTo(map);
            const bounds = L.latLngBounds([[empresaLat, empresaLng], [lat, lng]]);
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    };

    const toggle = (id) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('hidden');
    };

    // Usar delegación de eventos para soportar contenido dinámico de Livewire
    document.addEventListener('click', (e) => {
        // Manejar clicks en envio-card
        const envioCard = e.target.closest('.envio-card');
        if (envioCard && !e.target.closest('.toggle-details')) {
            const target = envioCard.getAttribute('data-target');
            if (target) toggle(target);
            return;
        }

        // Manejar clicks en toggle-details (Ver Ruta)
        const toggleBtn = e.target.closest('.toggle-details');
        if (toggleBtn) {
            e.stopPropagation();
            // Cargar ruta en el mapa
            const lat = parseFloat(toggleBtn.getAttribute('data-lat'));
            const lng = parseFloat(toggleBtn.getAttribute('data-lng'));
            const nombre = toggleBtn.getAttribute('data-nombre');
            const direccion = toggleBtn.getAttribute('data-direccion');
            
            console.log('Ver Ruta clicked - Coords:', lat, lng, '| Nombre:', nombre);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                updateRoute(lat, lng, nombre, direccion);
            } else {
                console.warn('Coordenadas inválidas del envío seleccionado', lat, lng);
            }
        }
    });


    // Filtrado por tabs (en_ruta vs pendiente vs entregados)
    const tabEnRutas = document.getElementById('tab-en-rutas');
    const tabSinAsignar = document.getElementById('tab-sin-asignar');
    const tabEntregados = document.getElementById('tab-entregados');
    const listaEnRuta = document.getElementById('lista-en-ruta');
    const listaPendiente = document.getElementById('lista-pendiente');
    const listaEntregados = document.getElementById('lista-entregados');

    const setActiveTab = (active) => {
        // Remover estilos activos de todos los tabs
        [tabEnRutas, tabSinAsignar, tabEntregados].forEach(tab => {
            tab?.classList.remove('bg-foreground', 'text-background');
            tab?.classList.add('text-foreground-muted');
        });
        
        // Ocultar todas las listas
        listaEnRuta?.classList.add('hidden');
        listaPendiente?.classList.add('hidden');
        listaEntregados?.classList.add('hidden');
        
        // Activar el tab seleccionado
        if (active === 'en_ruta') {
            tabEnRutas?.classList.add('bg-foreground', 'text-background');
            tabEnRutas?.classList.remove('text-foreground-muted');
            listaEnRuta?.classList.remove('hidden');
        } else if (active === 'entregado') {
            tabEntregados?.classList.add('bg-foreground', 'text-background');
            tabEntregados?.classList.remove('text-foreground-muted');
            listaEntregados?.classList.remove('hidden');
        } else {
            tabSinAsignar?.classList.add('bg-foreground', 'text-background');
            tabSinAsignar?.classList.remove('text-foreground-muted');
            listaPendiente?.classList.remove('hidden');
        }
    };

    // Estado inicial: mostrar pendientes (Sin Asignar ya tiene estilo activo) -> listas ya configuradas
    setActiveTab('pendiente');

    tabEnRutas?.addEventListener('click', () => setActiveTab('en_ruta'));
    tabSinAsignar?.addEventListener('click', () => setActiveTab('pendiente'));
    tabEntregados?.addEventListener('click', () => setActiveTab('entregado'));
    
    // Toggle Shipment Panel Height (Mobile Only)
    const toggleBtn = document.getElementById('toggle-shipments');
    const shipmentPanel = document.getElementById('shipment-panel');
    let isExpanded = false;
    
    // Siempre agregar el event listener, solo verificar el ancho al hacer click
    if (toggleBtn && shipmentPanel) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 1024) return; // Solo ejecutar en móvil
            
            isExpanded = !isExpanded;
            
            if (isExpanded) {
                shipmentPanel.style.height = '70vh';
                shipmentPanel.style.maxHeight = '70vh';
            } else {
                shipmentPanel.style.height = '27vh';
                shipmentPanel.style.maxHeight = '27vh';
            }
        });
        
        // Reset en resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                isExpanded = false;
                shipmentPanel.style.height = '';
                shipmentPanel.style.maxHeight = '';
            }
        });
    }
});
</script>
