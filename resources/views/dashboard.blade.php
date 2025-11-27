@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-20 glass-sidebar flex flex-col items-center py-6 gap-2">
        <!-- Logo -->
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>

        <!-- Nav Items -->
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center hover:bg-blue-500/20 transition-colors" title="Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </a>
            <a href="#" class="w-12 h-12 rounded-xl text-gray-400 flex items-center justify-center hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Envíos">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </a>
            <a href="#" class="w-12 h-12 rounded-xl text-gray-400 flex items-center justify-center hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Vehículos">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </a>
        </nav>

        <!-- Bottom Nav -->
        <div class="flex flex-col gap-2">
            <a href="#" class="w-12 h-12 rounded-xl text-gray-400 flex items-center justify-center hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Configuración">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-12 h-12 rounded-xl text-gray-400 flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-colors" title="Cerrar Sesión">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex overflow-hidden">
        <!-- Left Panel - Package List -->
        <div class="w-[420px] glass-sidebar border-r border-white/20 flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-800">Seguimiento de Envíos</h1>
                    <button class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-gray-900 text-white rounded-full text-sm font-medium">
                        En camino
                    </button>
                    <button class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-full text-sm font-medium transition-colors">
                        Recibidos
                    </button>
                </div>
            </div>

            <!-- Package List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                @forelse($enviosEnRuta as $envio)
                <div class="glass-card rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200 {{ $loop->first ? 'glass-card-active text-white' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-semibold {{ $loop->first ? 'text-white' : 'text-gray-800' }}">
                                {{ Str::limit($envio->remitente_direccion, 15) }} → {{ Str::limit($envio->destinatario_direccion, 15) }}
                            </h3>
                            <p class="text-sm {{ $loop->first ? 'text-blue-100' : 'text-gray-500' }}">
                                Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}-{{ rand(10000,99999) }}
                            </p>
                        </div>
                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium {{ $loop->first ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600' }}">
                            • En Ruta
                        </span>
                    </div>

                    @if($envio->repartidor)
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                            <img src="/placeholder.svg?height=40&width=40" alt="Courier" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="font-medium {{ $loop->first ? 'text-white' : 'text-gray-800' }}">{{ $envio->repartidor->nombre }}</p>
                            <p class="text-sm {{ $loop->first ? 'text-blue-100' : 'text-gray-500' }}">Repartidor</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="w-9 h-9 rounded-full {{ $loop->first ? 'bg-white/20 text-white hover:bg-white/30' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }} flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </button>
                            <button class="w-9 h-9 rounded-full {{ $loop->first ? 'bg-white/20 text-white hover:bg-white/30' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }} flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if($loop->first)
                    <div class="bg-white/10 rounded-xl p-3 mb-3">
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-white"></div>
                                <div class="w-0.5 h-8 bg-white/30 my-1"></div>
                                <div class="w-3 h-3 rounded-full border-2 border-white"></div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-blue-100 mb-1">Origen</p>
                                <p class="text-sm font-medium text-white mb-3">{{ $envio->remitente_direccion }}</p>
                                <p class="text-xs text-blue-100 mb-1">Destino</p>
                                <p class="text-sm font-medium text-white">{{ $envio->destinatario_direccion }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <button class="w-full py-2.5 {{ $loop->first ? 'bg-white/20 hover:bg-white/30 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }} rounded-xl text-sm font-medium transition-colors">
                        Ver detalles
                    </button>
                </div>
                @empty
                <!-- Empty State -->
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Sin envíos en ruta</h3>
                    <p class="text-sm text-gray-500">No hay envíos activos en este momento</p>
                </div>
                @endforelse

                @foreach($enviosPendientes->take(3) as $envio)
                <div class="glass-card rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ Str::limit($envio->remitente_direccion, 15) }} → {{ Str::limit($envio->destinatario_direccion, 15) }}
                            </h3>
                            <p class="text-sm text-gray-500">Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}-{{ rand(10000,99999) }}</p>
                        </div>
                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-600">
                            • Pendiente
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Panel - Map & Details -->
        <div class="flex-1 flex flex-col p-6 gap-4 overflow-hidden">
            <!-- Map -->
            <div class="flex-1 map-container rounded-3xl relative overflow-hidden shadow-lg">
                <!-- Map Controls -->
                <div class="absolute top-4 right-4 flex gap-2">
                    <button class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-600 hover:bg-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                    <button class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-600 hover:bg-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                    <button class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-600 hover:bg-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                </div>

                <!-- Map Placeholder with styled routes -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <img src="/placeholder.svg?height=600&width=800" alt="Map" class="w-full h-full object-cover opacity-60">
                </div>

                <!-- Destination Marker -->
                <div class="absolute top-1/4 right-1/3 transform -translate-x-1/2">
                    <div class="bg-gray-900 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-lg">
                        @if($enviosEnRuta->first())
                            {{ Str::limit($enviosEnRuta->first()->destinatario_direccion, 30) }}
                        @else
                            456 Elm Street, New York, NY 10001, USA
                        @endif
                    </div>
                    <div class="w-3 h-3 bg-gray-900 rotate-45 absolute -bottom-1.5 left-1/2 transform -translate-x-1/2"></div>
                </div>

                <!-- Current Location Marker -->
                <div class="absolute bottom-1/3 left-1/3">
                    <div class="w-4 h-4 bg-blue-500 rounded-full border-4 border-white shadow-lg"></div>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="glass-card rounded-2xl p-5">
                @if($enviosEnRuta->first())
                @php $envio = $enviosEnRuta->first(); @endphp
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-lg font-bold text-gray-800">Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}-{{ rand(10000,99999) }}</h2>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-600">• En Ruta</span>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-4">
                            @if($envio->repartidor)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden">
                                    <img src="/placeholder.svg?height=32&width=32" alt="Courier" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $envio->repartidor->nombre }}</p>
                                    <p class="text-xs text-gray-500">Repartidor</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Origen</p>
                                <p class="text-sm font-medium text-gray-800">{{ Str::limit($envio->remitente_direccion, 20) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Destino</p>
                                <p class="text-sm font-medium text-gray-800">{{ Str::limit($envio->destinatario_direccion, 20) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Estado</p>
                                <p class="text-sm font-medium text-gray-800">En Tránsito</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Fecha Estimada</p>
                                <p class="text-sm font-medium text-gray-800">{{ $envio->fecha_estimada ? $envio->fecha_estimada->format('d/m/Y') : 'Por definir' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="flex flex-col items-end gap-2">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-800" viewBox="0 0 100 100">
                                <rect x="10" y="10" width="20" height="20" fill="currentColor"/>
                                <rect x="40" y="10" width="10" height="10" fill="currentColor"/>
                                <rect x="60" y="10" width="10" height="10" fill="currentColor"/>
                                <rect x="70" y="10" width="20" height="20" fill="currentColor"/>
                                <rect x="10" y="40" width="10" height="10" fill="currentColor"/>
                                <rect x="30" y="40" width="10" height="10" fill="currentColor"/>
                                <rect x="50" y="40" width="10" height="10" fill="currentColor"/>
                                <rect x="70" y="40" width="10" height="10" fill="currentColor"/>
                                <rect x="10" y="60" width="10" height="10" fill="currentColor"/>
                                <rect x="40" y="60" width="10" height="10" fill="currentColor"/>
                                <rect x="60" y="60" width="10" height="10" fill="currentColor"/>
                                <rect x="10" y="70" width="20" height="20" fill="currentColor"/>
                                <rect x="40" y="70" width="10" height="10" fill="currentColor"/>
                                <rect x="70" y="70" width="10" height="10" fill="currentColor"/>
                                <rect x="80" y="70" width="10" height="10" fill="currentColor"/>
                            </svg>
                        </div>
                        <a href="#" class="text-sm text-blue-500 hover:text-blue-600 font-medium">Ver en app</a>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Selecciona un envío</h3>
                    <p class="text-sm text-gray-500">Haz clic en un envío para ver sus detalles</p>
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
