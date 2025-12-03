{{-- resources/views/livewire/repartidor/mis-envios.blade.php --}}
<div wire:poll.5s class="flex flex-col overflow-hidden h-full">
    {{-- Búsqueda y Tabs --}}
    <div class="px-2 lg:px-4 py-1 space-y-3">
        {{-- Búsqueda --}}
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="busqueda"
                placeholder="Buscar envío..."
                class="w-full px-4 py-2 pl-10 glass-subtle rounded-xl text-foreground placeholder-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary/50">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Filtros por Estado (Tabs) --}}
        <div class="flex gap-1 flex-wrap justify-center">
            <button 
                wire:click="$set('estadoSeleccionado', 'todos')"
                class="px-2 py-1 rounded-full text-xs lg:text-sm font-medium transition-all {{ $estadoSeleccionado === 'todos' ? 'bg-foreground text-background' : 'text-foreground-muted hover:text-foreground' }}">
                Todos
            </button>
            <button 
                wire:click="$set('estadoSeleccionado', 'pendiente')"
                class="px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium transition-all {{ $estadoSeleccionado === 'pendiente' ? 'bg-foreground text-background' : 'text-foreground-muted hover:text-foreground' }}">
                Pendientes
            </button>
            <button 
                wire:click="$set('estadoSeleccionado', 'en_ruta')"
                class="px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium transition-all {{ $estadoSeleccionado === 'en_ruta' ? 'bg-foreground text-background' : 'text-foreground-muted hover:text-foreground' }}">
                En Ruta
            </button>
            <button 
                wire:click="$set('estadoSeleccionado', 'entregado')"
                class="px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium transition-all {{ $estadoSeleccionado === 'entregado' ? 'bg-foreground text-background' : 'text-foreground-muted hover:text-foreground' }}">
                Entregados
            </button>
        </div>
    </div>

    {{-- Lista de Envíos con Scroll --}}
    <div class="flex-1 min-h-0 overflow-y-auto p-3 lg:p-4 space-y-3">
        @forelse($this->enviosHoy as $envio)
            <div class="glass-card rounded-2xl p-4 cursor-pointer hover:shadow-lg transition-all duration-200 {{ $envioSeleccionado === $envio->id ? 'ring-2 ring-primary' : '' }}"
                 wire:click="selectShipment({{ $envio->id }})">
                
                {{-- Header del Envío --}}
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-semibold text-foreground">Envío {{ $envio->codigo }}</h3>
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $envio->estado === 'pendiente' ? 'bg-warning-light text-warning' : '' }}
                                {{ $envio->estado === 'en_ruta' ? 'bg-primary/20 text-primary' : '' }}
                                {{ $envio->estado === 'entregado' ? 'bg-success-light text-success' : '' }}
                                {{ $envio->estado === 'devuelto' ? 'bg-danger/20 text-danger' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $envio->estado)) }}
                            </span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg 
                        {{ $envio->estado === 'entregado' ? 'bg-success-light' : '' }}
                        {{ $envio->estado === 'en_ruta' ? 'bg-primary/20' : '' }}
                        {{ $envio->estado === 'pendiente' ? 'bg-warning-light' : '' }}
                        {{ $envio->estado === 'devuelto' ? 'bg-danger/20' : '' }}
                        flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                </div>

                {{-- Información del Destinatario --}}
                <div class="space-y-2 mb-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-foreground-muted mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <p class="text-sm text-foreground">{{ $envio->destinatario_nombre }}</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-foreground-muted mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm text-foreground-muted line-clamp-2">{{ $envio->destinatario_direccion }}</p>
                    </div>
                    @if($envio->destinatario_telefono)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <p class="text-sm text-foreground-muted">{{ $envio->destinatario_telefono }}</p>
                        </div>
                    @endif
                </div>

                {{-- Acciones Rápidas --}}
                <div class="flex gap-2 pt-3 border-t border-foreground/5" onclick="event.stopPropagation()">
                    @if($envio->estado === 'pendiente')
                        <button 
                            wire:click.stop="cambiarEstado({{ $envio->id }}, 'en_ruta')"
                            class="flex-1 bg-primary text-white hover:bg-primary-hover px-3 py-2 rounded-xl text-sm font-medium transition-all">
                            Iniciar Ruta
                        </button>
                    @elseif($envio->estado === 'en_ruta')
                        <button 
                            wire:click.stop="cambiarEstado({{ $envio->id }}, 'entregado')"
                            class="flex-1 bg-success text-white hover:bg-success/90 px-3 py-2 rounded-xl text-sm font-medium transition-all">
                            Entregado
                        </button>
                        <button 
                            wire:click.stop="cambiarEstado({{ $envio->id }}, 'devuelto')"
                            class="flex-1 bg-danger text-white hover:bg-danger/90 px-3 py-2 rounded-xl text-sm font-medium transition-all">
                            Devolver
                        </button>
                    @else
                        <div class="flex-1 text-center text-sm text-foreground-muted py-2">
                            Estado: {{ ucfirst($envio->estado) }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="glass-card rounded-2xl p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-surface-secondary rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-foreground mb-1">No se han encontrado envíos</h3>
                <p class="text-sm text-foreground-muted">Cuando tengas envíos asignados aparecerán aquí</p>
            </div>
        @endforelse
    </div>

    {{-- Modal para Actualizar Estado con z-index MUY ALTO --}}
    @if($mostrarModal)
    @teleport('body')
        <div class="fixed inset-0 z-[99999] flex items-end sm:items-center justify-center" 
             wire:click="cerrarModal">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            
            {{-- Contenedor del Modal --}}
            <div class="relative z-[100000] w-full max-w-md mx-4 mb-0 sm:mb-4 max-h-[90vh] flex flex-col" 
                 wire:click.stop>
                
                {{-- Contenido Scrolleable --}}
                <div class="glass-card rounded-t-3xl sm:rounded-2xl overflow-hidden flex flex-col max-h-[90vh]">
                    
                    {{-- Header Fijo --}}
                    <div class="px-6 pt-6 pb-4 border-b border-foreground/10 flex-shrink-0">
                        <h3 class="text-xl font-bold text-foreground">
                            {{ $nuevoEstado === 'entregado' ? 'Confirmar Entrega' : 'Confirmar Devolución' }}
                        </h3>
                    </div>

                    {{-- Contenido con Scroll --}}
                    <div class="overflow-y-auto flex-1 px-6 py-4">
                        <form wire:submit.prevent="actualizarEstado" id="form-actualizar-estado">
                            {{-- Foto de Entrega --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-foreground mb-2">
                                    Foto de Entrega {{ $nuevoEstado === 'entregado' ? '(Requerida)' : '(Opcional)' }}
                                </label>
                                <input 
                                    type="file" 
                                    wire:model="foto_entrega"
                                    accept="image/*"
                                    capture="environment"
                                    class="w-full px-4 py-2 glass-subtle rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 text-sm">
                                @error('foto_entrega') 
                                    <span class="text-danger text-xs mt-1 block">{{ $message }}</span> 
                                @enderror

                                @if ($foto_entrega)
                                    <div class="mt-3">
                                        <p class="text-sm text-foreground-muted mb-2">Vista previa:</p>
                                        <img src="{{ $foto_entrega->temporaryUrl() }}" 
                                             class="w-full h-48 object-cover rounded-xl">
                                    </div>
                                @endif
                            </div>

                            {{-- Observaciones --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-foreground mb-2">
                                    Observaciones
                                </label>
                                <textarea 
                                    wire:model="observaciones"
                                    rows="3"
                                    placeholder="Agregar comentarios sobre la entrega..."
                                    class="w-full px-4 py-2 glass-subtle rounded-xl text-foreground placeholder-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary/50 resize-none text-sm"></textarea>
                                @error('observaciones') 
                                    <span class="text-danger text-xs mt-1 block">{{ $message }}</span> 
                                @enderror
                            </div>
                        </form>
                    </div>

                    {{-- Footer con Botones Fijos --}}
                    <div class="px-6 py-4 border-t border-foreground/10 flex-shrink-0 bg-surface/50 backdrop-blur-sm">
                        <div class="flex gap-3">
                            <button 
                                type="button"
                                wire:click="cerrarModal"
                                class="flex-1 glass-subtle text-foreground px-4 py-3 rounded-xl font-medium hover:shadow-md transition-all active:scale-95">
                                Cancelar
                            </button>
                            <button 
                                type="submit"
                                form="form-actualizar-estado"
                                class="flex-1 {{ $nuevoEstado === 'entregado' ? 'bg-success' : 'bg-danger' }} text-white px-4 py-3 rounded-xl font-medium hover:shadow-md transition-all active:scale-95">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif

    {{-- Toast de notificación --}}
    <div x-data="{ 
            show: false, 
            mensaje: '',
            tipo: ''
        }"
        @envio-actualizado.window="
            show = true;
            mensaje = $event.detail.mensaje;
            tipo = $event.detail.tipo;
            setTimeout(() => { show = false }, 3000);
        "
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-4 right-4 z-[100500] glass glass-strong rounded-xl p-4 shadow-2xl max-w-sm"
        style="display: none;">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-success-light flex items-center justify-center">
                <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="font-semibold text-foreground" x-text="mensaje"></p>
        </div>
    </div>

    {{-- Script del toggle --}}
    <script>
    // Toggle del panel de envíos en móvil - Persistente durante actualizaciones de Livewire
    function initTogglePanel() {
        const toggleBtn = document.getElementById('toggle-shipments');
        const shipmentPanel = document.getElementById('shipment-panel');
        
        if (!toggleBtn || !shipmentPanel) return;
        
        // Función para aplicar el estado actual
        const applyState = () => {
            if (window.innerWidth >= 1024) {
                shipmentPanel.style.height = '';
                shipmentPanel.style.maxHeight = '';
                return;
            }
            
            const isExpanded = shipmentPanel.getAttribute('data-expanded') === 'true';
            if (isExpanded) {
                shipmentPanel.style.height = '70vh';
                shipmentPanel.style.maxHeight = '70vh';
            } else {
                shipmentPanel.style.height = '40vh';
                shipmentPanel.style.maxHeight = '40vh';
            }
        };
        
        // Remover listeners anteriores si existen
        const newToggleBtn = toggleBtn.cloneNode(true);
        toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);
        
        // Evento de click en el nuevo botón
        newToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            if (window.innerWidth >= 1024) return;
            
            const currentState = shipmentPanel.getAttribute('data-expanded') === 'true';
            const newState = !currentState;
            shipmentPanel.setAttribute('data-expanded', newState);
            
            applyState();
        });
        
        // Aplicar estado al cargar
        applyState();
    }

    // Evento para resize
    function setupResize() {
        window.addEventListener('resize', () => {
            const shipmentPanel = document.getElementById('shipment-panel');
            if (shipmentPanel && window.innerWidth >= 1024) {
                shipmentPanel.setAttribute('data-expanded', 'false');
                shipmentPanel.style.height = '';
                shipmentPanel.style.maxHeight = '';
            }
        });
    }

    // Inicializar al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        initTogglePanel();
        setupResize();
    });

    // Hook para Livewire v3
    document.addEventListener('livewire:initialized', () => {
        // Re-inicializar después de cada actualización
        Livewire.hook('morph.updated', ({ el, component }) => {
            setTimeout(() => {
                const shipmentPanel = document.getElementById('shipment-panel');
                if (shipmentPanel && window.innerWidth < 1024) {
                    const isExpanded = shipmentPanel.getAttribute('data-expanded') === 'true';
                    if (isExpanded) {
                        shipmentPanel.style.height = '70vh';
                        shipmentPanel.style.maxHeight = '70vh';
                    }
                }
                initTogglePanel();
            }, 10);
        });
        
        Livewire.hook('commit', ({ component, commit, respond }) => {
            setTimeout(() => {
                const shipmentPanel = document.getElementById('shipment-panel');
                if (shipmentPanel && window.innerWidth < 1024) {
                    const isExpanded = shipmentPanel.getAttribute('data-expanded') === 'true';
                    if (isExpanded) {
                        shipmentPanel.style.height = '70vh';
                        shipmentPanel.style.maxHeight = '70vh';
                    }
                }
            }, 10);
        });
    });
    </script>
</div>