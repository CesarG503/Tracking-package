<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Tracking Info --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Header --}}
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-foreground">Seguimiento de Envío</h1>
                        <p class="text-foreground-muted">Código: <span class="font-mono font-bold text-primary">{{ $envio->codigo }}</span></p>
                    </div>
                    <div class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($envio->estado == 'pendiente') bg-warning/20 text-warning
                        @elseif($envio->estado == 'en_ruta') bg-info/20 text-info
                        @elseif($envio->estado == 'entregado') bg-success/20 text-success
                        @else bg-danger/20 text-danger @endif">
                        {{ ucfirst(str_replace('_', ' ', $envio->estado)) }}
                    </div>
                </div>
                
                {{-- Map --}}
                <div id="map" wire:ignore class="h-64 w-full rounded-xl border border-border z-0"></div>
            </div>

            {{-- Timeline --}}
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                <h2 class="text-lg font-semibold text-foreground mb-6">Historial de Estados</h2>
                <div class="relative pl-4 border-l-2 border-border space-y-8">
                    @foreach($envio->historial as $evento)
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-primary border-2 border-surface"></div>
                        <div>
                            <p class="font-semibold text-foreground">{{ ucfirst($evento->estado) }}</p>
                            <p class="text-sm text-foreground-muted">{{ $evento->descripcion }}</p>
                            <p class="text-xs text-foreground-muted mt-1">{{ $evento->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-surface-secondary border-2 border-primary"></div>
                        <div>
                            <p class="font-semibold text-foreground">Creado</p>
                            <p class="text-sm text-foreground-muted">Envío registrado en el sistema</p>
                            <p class="text-xs text-foreground-muted mt-1">{{ $envio->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                <h2 class="text-lg font-semibold text-foreground mb-4">Detalles del Paquete</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-foreground-muted">Destinatario</p>
                        <p class="font-medium text-foreground">{{ $envio->destinatario_nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-foreground-muted">Dirección de Entrega</p>
                        <p class="font-medium text-foreground">{{ $envio->destinatario_direccion }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-foreground-muted">Tipo de Envío</p>
                        <p class="font-medium text-foreground">{{ ucfirst($envio->tipo_envio) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-foreground-muted">Fecha Estimada</p>
                        <p class="font-medium text-foreground">{{ $envio->fecha_estimada ? $envio->fecha_estimada->format('d/m/Y') : 'Pendiente' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Chat --}}
        <div class="lg:col-span-1">
            <div class="bg-surface rounded-2xl shadow-sm border border-border h-[600px] flex flex-col sticky top-6">
                <div class="p-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isRepartidor()))
                            Chat con Cliente
                        @else
                            Chat con Repartidor
                        @endif
                    </h2>
                    @if($envio->repartidor)
                        <p class="text-xs text-foreground-muted mt-1">
                            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isRepartidor()))
                                Cliente: {{ $envio->destinatario_nombre }}
                            @else
                                Repartidor: {{ $envio->repartidor->nombre }}
                            @endif
                        </p>
                    @else
                        <p class="text-xs text-warning mt-1">Esperando asignación de repartidor...</p>
                    @endif
                </div>

                {{-- Messages Area --}}
                <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4" wire:poll.3s>
                    @php
                        $isRepartidorOrAdmin = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isRepartidor());
                    @endphp
                    @forelse($mensajes as $msg)
                        @php
                            // Si soy repartidor/admin, mis mensajes (es_repartidor=true) van a la derecha.
                            // Si soy cliente (no logueado o rol user), mis mensajes (es_repartidor=false) van a la derecha.
                            $isMyMessage = $isRepartidorOrAdmin ? $msg->es_repartidor : !$msg->es_repartidor;
                        @endphp
                        <div class="flex {{ $isMyMessage ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[80%] rounded-xl p-3 {{ $isMyMessage ? 'bg-primary text-white' : 'bg-surface-secondary text-foreground' }}">
                                <p class="text-sm">{{ $msg->mensaje }}</p>
                                <p class="text-[10px] opacity-70 mt-1 text-right">{{ $msg->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-foreground-muted text-sm py-10">
                            No hay mensajes. Inicia la conversación si tienes dudas.
                        </div>
                    @endforelse
                </div>

                {{-- Input Area --}}
                <div class="p-4 border-t border-border">
                    <form x-data="{ message: @entangle('nuevoMensaje') }" 
                          @submit.prevent="if(message) { $wire.sendMessage(message); message = ''; }" 
                          class="flex gap-2">
                        <input type="text" x-model="message" 
                            class="flex-1 px-4 py-2 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Escribe un mensaje..." required>
                        <button type="submit" class="p-2 bg-primary hover:bg-primary-hover text-white rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Map Initialization
            const lat = {{ $envio->lat ?? 13.4834 }};
            const lng = {{ $envio->lng ?? -88.1833 }};
            
            const mapContainer = document.getElementById('map');
            if (mapContainer && !mapContainer._leaflet_id) {
                const map = L.map('map').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
                
                L.marker([lat, lng]).addTo(map)
                    .bindPopup('{{ $envio->destinatario_direccion }}')
                    .openPopup();
            }

            // Chat Scroll
            const chatContainer = document.getElementById('chat-messages');
            
            function scrollToBottom() {
                if(chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            }

            Livewire.on('scroll-chat', () => {
                setTimeout(scrollToBottom, 50);
            });

            // Initial scroll
            scrollToBottom();
        });
    </script>
</div>
