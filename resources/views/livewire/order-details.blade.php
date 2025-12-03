<div>
    @if($showCard && $selectedShipment)
        <!-- Order Details Card - Original Design -->
        <div id="order-details-card" class="bottom-3 left-3 right-3 lg:bottom-4 glass-advanced lg:left-4 lg:right-4 rounded-2xl p-4 lg:p-5 z-[999] max-w-full rounded-xl text-foreground transition-colors shadow-lg !absolute glass-card dark:glass-card-dark" style="touch-action: none;">
            
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 lg:gap-3 mb-3 lg:mb-4 flex-wrap">
                        <h2 class="text-base lg:text-lg font-bold text-foreground truncate">
                            #{{ str_pad($selectedShipment->codigo, 5, '0', STR_PAD_LEFT) }}
                        </h2>
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $selectedShipment->estado === 'en_ruta' ? 'bg-warning-light text-warning' : '' }}
                            {{ $selectedShipment->estado === 'pendiente' ? 'bg-warning-light text-warning' : '' }}
                            {{ $selectedShipment->estado === 'entregado' ? 'bg-success-light text-success' : '' }}
                            whitespace-nowrap">
                            {{ $selectedShipment->estado === 'en_ruta' ? 'En Ruta' : ucfirst($selectedShipment->estado) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
                        <div class="min-w-0">
                            <p class="text-xs text-foreground-muted mb-1">Origen</p>
                            <p class="text-sm font-medium text-foreground truncate" title="{{ $selectedShipment->remitente_direccion }}">
                                {{ Str::limit($selectedShipment->remitente_direccion, 20) }}
                            </p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-foreground-muted mb-1">Destino</p>
                            <p class="text-sm font-medium text-foreground truncate" title="{{ $selectedShipment->destinatario_direccion }}">
                                {{ Str::limit($selectedShipment->destinatario_direccion, 20) }}
                            </p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-foreground-muted mb-1">Estado</p>
                            <p class="text-sm font-medium text-foreground">
                                {{ $selectedShipment->estado === 'en_ruta' ? 'En Transito' : ucfirst(str_replace('_', ' ', $selectedShipment->estado)) }}
                            </p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-foreground-muted mb-1">Fecha Estimada</p>
                            <p class="text-sm font-medium text-foreground">
                                {{ $selectedShipment->fecha_estimada ? $selectedShipment->fecha_estimada->format('d/m/Y') : 'Por definir' }}
                            </p>
                        </div>
                    </div>

                    @if($selectedShipment->repartidor)
                    <!-- Información del Repartidor -->
                    <div class="mt-4 pt-4 border-t border-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-foreground">Repartidor: {{ $selectedShipment->repartidor->nombre }}</p>
                                <p class="text-xs text-foreground-muted truncate">{{ $selectedShipment->repartidor->email ?? 'Sin email' }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Sin Repartidor Asignado -->
                    <div class="mt-4 pt-4 border-t border-border">
                        <div class="flex items-center gap-3 text-foreground-muted">
                            <div class="w-10 h-10 rounded-full bg-surface-secondary flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Sin repartidor asignado</p>
                                <p class="text-xs">Pendiente de asignación</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- Empty State - Compact Design -->
        <div class="bottom-3 left-3 right-3 lg:bottom-4 glass-advanced lg:left-4 lg:right-4 rounded-2xl p-4 lg:p-5 z-[999] max-w-full rounded-xl text-foreground transition-colors shadow-lg !absolute glass-card dark:glass-card-dark text-center">
            <div class="w-12 h-12 mx-auto mb-3 bg-surface-secondary rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-foreground mb-1">Selecciona una ruta</h3>
            <p class="text-xs text-foreground-muted">Haz clic en "Ver Ruta" para ver detalles</p>
        </div>
    @endif
</div>

