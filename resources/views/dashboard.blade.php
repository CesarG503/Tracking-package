@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col lg:flex-row overflow-hidden">
        <!-- Left Panel - Package List -->
        <div class="order-2 lg:order-1 w-full lg:w-[420px] glass-sidebar dark:glass-sidebar-dark border-r border-white/20 dark:border-white/10 flex flex-col overflow-hidden h-auto lg:h-full transition-colors duration-300">
            <!-- Mobile Toggle Button -->
            <button id="mobile-toggle" class="lg:hidden w-full p-3 flex items-center justify-center gap-2 border-b border-border dark:border-border bg-surface-secondary/50 dark:bg-surface-secondary/50 backdrop-blur-sm transition-colors duration-300">
                <svg class="w-5 h-5 text-foreground dark:text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                <span class="text-sm font-medium text-foreground dark:text-foreground">Ver Envíos</span>
            </button>
            
            <!-- Collapsible Content -->
            <div id="mobile-content" class="flex flex-col overflow-hidden max-h-0 lg:max-h-full lg:flex-1 transition-none lg:transition-all duration-300">
            <!-- Header -->
            <div class="p-4 lg:p-6 border-b border-border dark:border-border transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-foreground dark:text-foreground">Seguimiento de Envios</h1>
                    <button class="w-10 h-10 rounded-xl bg-surface-secondary dark:bg-surface-secondary flex items-center justify-center text-foreground-muted dark:text-foreground-muted hover:bg-border dark:hover:bg-border transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-foreground dark:bg-foreground text-background dark:text-background rounded-full text-sm font-medium">
                        En camino
                    </button>
                    <button class="px-4 py-2 text-foreground-muted dark:text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary rounded-full text-sm font-medium transition-colors">
                        Recibidos
                    </button>
                </div>
            </div>

            <!-- Package List -->
            <div class="flex-1 overflow-y-auto p-3 lg:p-4 space-y-3">
                @forelse($enviosEnRuta as $envio)
                <div class="glass-card dark:glass-card-dark rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200 {{ $loop->first ? 'glass-card-active dark:glass-card-active-dark text-white' : '' }} envio-card" data-target="details-envio-{{ $envio->id }}">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-semibold {{ $loop->first ? 'text-white' : 'text-foreground dark:text-foreground' }}">
                                {{ Str::limit($envio->remitente_direccion, 15) }} → {{ Str::limit($envio->destinatario_direccion, 15) }}
                            </h3>
                            <p class="text-sm {{ $loop->first ? 'text-blue-100' : 'text-foreground-muted dark:text-foreground-muted' }}">
                                Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}-{{ rand(10000,99999) }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="status-badge px-3 py-1 rounded-full text-xs font-medium {{ $loop->first ? 'bg-white/20 text-white' : 'bg-warning-light dark:bg-warning-light text-warning dark:text-warning' }}">
                                En Ruta
                            </span>
                            @if(!$envio->repartidor)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $loop->first ? 'bg-white/20 text-white' : 'bg-surface-secondary text-foreground-muted' }}">
                                    Sin asignar
                                </span>
                            @endif
                        </div>
                    </div>
                    <div id="details-envio-{{ $envio->id }}" class="mt-3 hidden">
                        @if($envio->repartidor)
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-surface-secondary overflow-hidden">
                                <img src="/placeholder.svg?height=40&width=40" alt="Courier" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <p class="font-medium {{ $loop->first ? 'text-white' : 'text-foreground' }}">{{ $envio->repartidor->nombre }}</p>
                                <p class="text-sm {{ $loop->first ? 'text-blue-100' : 'text-foreground-muted' }}">Repartidor</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-surface-secondary flex items-center justify-center">
                                <svg class="w-6 h-6 {{ $loop->first ? 'text-white' : 'text-foreground-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium {{ $loop->first ? 'text-white' : 'text-foreground' }}">Sin asignar</p>
                                <p class="text-sm {{ $loop->first ? 'text-blue-100' : 'text-foreground-muted' }}">Repartidor</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    @php
                        // Ajuste: en DB los campos vienen invertidos, calculamos lat/lng correctos
                        $latReal = $envio->lng; // latitud real
                        $lngReal = $envio->lat; // longitud real
                    @endphp
                    <button
                        type="button"
                        class="toggle-details w-full py-2.5 {{ $loop->first ? 'bg-white/20 hover:bg-white/30 text-white' : 'bg-surface-secondary hover:bg-foreground-muted/35 text-foreground' }} rounded-xl text-sm font-medium transition-colors"
                        data-target="details-envio-{{ $envio->id }}"
                        data-lat="{{ $latReal }}"
                        data-lng="{{ $lngReal }}"
                        data-nombre="{{ $envio->destinatario_nombre }}"
                        data-direccion="{{ $envio->destinatario_direccion }}"
                    >
                        Ver Ruta
                    </button>
                </div>
                @empty
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-surface-secondary rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-foreground mb-1">Sin envios en ruta</h3>
                    <p class="text-sm text-foreground-muted">No hay envios activos en este momento</p>
                </div>
                @endforelse

                @foreach($enviosPendientes->take(3) as $envio)
                <div class="glass-card rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h3 class="font-semibold text-foreground">
                                {{ Str::limit($envio->remitente_direccion, 15) }} → {{ Str::limit($envio->destinatario_direccion, 15) }}
                            </h3>
                            <p class="text-sm text-foreground-muted">Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}-{{ rand(10000,99999) }}</p>
                        </div>
                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium bg-warning-light text-warning">
                            Pendiente
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            </div>
        </div>

        <!-- Right Panel - Map & Details -->
        <div class="order-1 lg:order-2 flex-1 flex flex-col p-3 lg:p-6 gap-4 overflow-hidden">
            <!-- Map -->
            <div class="flex-1 map-container rounded-2xl lg:rounded-3xl relative overflow-hidden shadow-lg h-full">
                <div id="map" class="w-full h-full rounded-2xl lg:rounded-3xl"></div>
                
                <div class="absolute top-3 right-3 lg:top-4 lg:right-4 flex gap-2 z-[1000]">
                    <button onclick="centerMap()" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-foreground hover:bg-surface transition-colors shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="hidden lg:block glass-card rounded-2xl p-5">
                @if($enviosEnRuta->first())
                @php $envio = $enviosEnRuta->first(); @endphp
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-lg font-bold text-foreground">Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}</h2>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-warning-light text-warning">En Ruta</span>
                        </div>
                        
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-foreground-muted mb-1">Origen</p>
                                <p class="text-sm font-medium text-foreground">{{ Str::limit($envio->remitente_direccion, 20) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-foreground-muted mb-1">Destino</p>
                                <p class="text-sm font-medium text-foreground">{{ Str::limit($envio->destinatario_direccion, 20) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-foreground-muted mb-1">Estado</p>
                                <p class="text-sm font-medium text-foreground">En Transito</p>
                            </div>
                            <div>
                                <p class="text-xs text-foreground-muted mb-1">Fecha Estimada</p>
                                <p class="text-sm font-medium text-foreground">{{ $envio->fecha_estimada ? $envio->fecha_estimada->format('d/m/Y') : 'Por definir' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-foreground-muted">Selecciona un envio para ver los detalles</p>
                </div>
                @endif
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

function initMap() {
    // Coordenadas de la empresa
    const empresaLat = {{ $empresaCoordenadas['lat'] }};
    const empresaLng = {{ $empresaCoordenadas['lng'] }};
    
    console.log('Empresa coords:', empresaLat, empresaLng);
    
    // Inicializar el mapa centrado en la empresa
    map = L.map('map').setView([empresaLat, empresaLng], 13);
    
    // Agregar capa de OpenStreetMap con estilo personalizado
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);
    
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

// Mobile toggle for shipment list
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('mobile-toggle');
    const mobileContent = document.getElementById('mobile-content');
    let isExpanded = false;

    if (toggleBtn && mobileContent) {
        toggleBtn.addEventListener('click', () => {
            // Solo funciona en móvil (pantallas menores a 1024px)
            if (window.innerWidth >= 1024) return;
            
            isExpanded = !isExpanded;
            
            if (isExpanded) {
                mobileContent.style.maxHeight = '60vh';
                toggleBtn.querySelector('svg').style.transform = 'rotate(180deg)';
                toggleBtn.querySelector('span').textContent = 'Ocultar Envíos';
            } else {
                mobileContent.style.maxHeight = '0';
                toggleBtn.querySelector('svg').style.transform = 'rotate(0deg)';
                toggleBtn.querySelector('span').textContent = 'Ver Envíos';
            }
        });
        
        // Resetear estilos inline cuando se redimensiona a desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                mobileContent.style.maxHeight = '';
                toggleBtn.querySelector('svg').style.transform = '';
                isExpanded = false;
            }
        });
    }
});
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

    document.querySelectorAll('.envio-card').forEach(card => {
        const target = card.getAttribute('data-target');
        card.addEventListener('click', (e) => {
            const isToggleButton = e.target.closest('.toggle-details');
            if (isToggleButton) return; // lo manejará el botón
            if (target) toggle(target);
        });
    });

    document.querySelectorAll('.toggle-details').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Cargar ruta en el mapa
            const lat = parseFloat(e.currentTarget.getAttribute('data-lat'));
            const lng = parseFloat(e.currentTarget.getAttribute('data-lng'));
            const nombre = e.currentTarget.getAttribute('data-nombre');
            const direccion = e.currentTarget.getAttribute('data-direccion');
            if (!isNaN(lat) && !isNaN(lng)) {
                updateRoute(lat, lng, nombre, direccion);
            } else {
                console.warn('Coordenadas inválidas del envío seleccionado', lat, lng);
            }
        });
    });
});
</script>
