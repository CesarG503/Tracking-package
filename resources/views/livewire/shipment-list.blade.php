<div wire:poll.5s="loadShipments" class="flex-1 flex flex-col overflow-hidden">
    <!-- Tabs -->
    <div class="px-4 lg:px-6 pb-3 border-b border-border dark:border-border transition-colors duration-300">
        <div class="flex gap-2">
            <button 
                wire:click="setActiveTab('pendiente')"
                class="px-3 py-1.5 lg:px-4 lg:py-2 rounded-full text-xs lg:text-sm font-medium transition-colors {{ $activeTab === 'pendiente' ? 'bg-foreground text-background' : 'text-foreground-muted' }}">
                Pendientes
            </button>
            <button 
                wire:click="setActiveTab('en_ruta')"
                class="px-3 py-1.5 lg:px-4 lg:py-2 rounded-full text-xs lg:text-sm font-medium transition-colors {{ $activeTab === 'en_ruta' ? 'bg-foreground text-background' : 'text-foreground-muted' }}">
                Rutas
            </button>
            <button 
                wire:click="setActiveTab('entregado')"
                class="px-3 py-1.5 lg:px-4 lg:py-2 rounded-full text-xs lg:text-sm font-medium transition-colors {{ $activeTab === 'entregado' ? 'bg-foreground text-background' : 'text-foreground-muted' }}">
                Entregados
            </button>
        </div>
    </div>

    <!-- Package List -->
    <div class="flex-1 min-h-0 !overflow-y-auto p-3 lg:p-4 space-y-3">
        <!-- Lista En Ruta -->
        <div class="space-y-3 {{ $activeTab !== 'en_ruta' ? 'hidden' : '' }}">
            @forelse($enviosEnRuta as $envio)
            <div class="glass-card dark:glass-card-dark rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200 envio-card" data-target="details-envio-{{ $envio->id }}">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-foreground dark:text-foreground">
                            {{ Str::limit($envio->remitente_direccion, 15) }} → {{ Str::limit($envio->destinatario_direccion, 15) }}
                        </h3>
                        <p class="text-sm text-foreground-muted dark:text-foreground-muted">
                            Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}-{{ rand(10000,99999) }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium bg-warning-light dark:bg-warning-light text-warning dark:text-warning">
                            En Ruta
                        </span>
                        @if(!$envio->repartidor)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-surface-secondary text-foreground-muted">
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
                            <p class="font-medium text-foreground">{{ $envio->repartidor->nombre }}</p>
                            <p class="text-sm text-foreground-muted">Repartidor</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-surface-secondary flex items-center justify-center">
                            <svg class="w-6 h-6 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-foreground">Sin asignar</p>
                            <p class="text-sm text-foreground-muted">Repartidor</p>
                        </div>
                    </div>
                    @endif
                </div>

                @php
                    $latReal = $envio->lat;
                    $lngReal = $envio->lng;
                @endphp
                <button
                    type="button"
                    wire:click="selectShipment({{ $envio->id }})"
                    class="toggle-details w-full py-2.5 bg-primary text-white hover:bg-primary-hover rounded-xl text-sm font-medium transition-colors"
                    data-lat="{{ $latReal }}"
                    data-lng="{{ $lngReal }}"
                    data-nombre="{{ $envio->destinatario_nombre }}"
                    data-direccion="{{ $envio->destinatario_direccion }}">
                    Ver Ruta
                </button>
            </div>
            @empty
            <div class="glass-card rounded-2xl p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-surface-secondary rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 00-.707.293h-3.172a1 1 0 00-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-foreground mb-1">Sin envios en ruta</h3>
                <p class="text-sm text-foreground-muted">No hay envios activos en este momento</p>
            </div>
            @endforelse
        </div>

        <!-- Lista Pendiente -->
        <div class="space-y-3 {{ $activeTab !== 'pendiente' ? 'hidden' : '' }}">
            @foreach($enviosPendientes->take(10) as $envio)
            <div class="glass-card rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
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
                
                <button
                    type="button"
                    class="w-full py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2"
                    data-envio-id="{{ $envio->id }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Asignar Repartidor
                </button>
            </div>
            @endforeach
        </div>
        
        <!-- Lista Entregados -->
        <div class="space-y-3 {{ $activeTab !== 'entregado' ? 'hidden' : '' }}">
            @forelse($enviosEntregados as $envio)
            <div class="glass-card rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200 envio-card" data-target="details-entregado-{{ $envio->id }}">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-foreground">
                            {{ Str::limit($envio->remitente_direccion, 15) }} → {{ Str::limit($envio->destinatario_direccion, 15) }}
                        </h3>
                        <p class="text-sm text-foreground-muted">Order ID #{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}-{{ rand(10000,99999) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium bg-success-light text-success">
                            Entregado
                        </span>
                    </div>
                </div>
                
                <div id="details-entregado-{{ $envio->id }}" class="mt-3 pt-3 border-t border-border hidden">
                    @if($envio->repartidor)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-secondary flex items-center justify-center">
                            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-foreground">{{ $envio->repartidor->nombre }}</p>
                            <p class="text-sm text-foreground-muted">Repartidor</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-foreground-muted">Entregado</p>
                            <p class="text-sm font-medium text-foreground">{{ $envio->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-secondary flex items-center justify-center">
                            <svg class="w-6 h-6 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-foreground">Sin repartidor asignado</p>
                            <p class="text-sm text-foreground-muted">Entregado sin asignación</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-foreground-muted">Entregado</p>
                            <p class="text-sm font-medium text-foreground">{{ $envio->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="glass-card rounded-2xl p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-surface-secondary rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-foreground mb-1">Sin envíos entregados</h3>
                <p class="text-sm text-foreground-muted">No hay envíos entregados recientemente</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
