<div wire:poll.5s class="h-full">
    <div class="flex flex-col h-full">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-foreground">Envíos</h1>
                <p class="text-foreground-muted text-sm mt-1">Gestiona y monitorea todos los envíos</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-secondary flex items-center justify-center">
                    <svg class="w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-surface rounded-2xl p-4 mb-6 shadow-sm border border-border">
            <div class="flex items-center gap-4 flex-wrap">
                {{-- Search --}}
                <div class="flex-1 min-w-[200px] relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por remitente, destinatario, email..." 
                        class="w-full pl-12 pr-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                {{-- Status Filter --}}
                <select wire:model.live="filterEstado" class="px-4 py-3 bg-surface-secondary border border-border rounded-xl min-w-40 text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $valor => $etiqueta)
                        <option value="{{ $valor }}">{{ $etiqueta }}</option>
                    @endforeach
                </select>

                {{-- Per Page --}}
                <select wire:model.live="perPage" class="px-4 py-3 bg-surface-secondary border border-border rounded-xl min-w-20 text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>

                {{-- Clear Filters --}}
                @if($search || $filterEstado)
                    <button wire:click="clearFilters" class="p-3 bg-surface-secondary border border-border rounded-xl text-foreground-muted hover:text-danger hover:border-danger transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif

                {{-- Add Button --}}
                <a href="{{ route('envios.create') }}" class="px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium flex items-center gap-2 transition-colors shadow-lg shadow-primary/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nuevo Envío
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-surface rounded-2xl flex-1 overflow-hidden shadow-sm border border-border">
            <div class="overflow-x-auto h-full">
                <table class="w-full">
                    <thead class="bg-surface-secondary border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Código</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Remitente</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Destinatario</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Repartidor</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-foreground-muted">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($envios as $envio)
                        @php
                            $statusColors = [
                                'pendiente' => 'bg-warning',
                                'en_ruta' => 'bg-primary',
                                'entregado' => 'bg-success',
                                'devuelto' => 'bg-danger',
                                'cancelado' => 'bg-foreground-muted',
                            ];
                        @endphp
                        <tr class="cursor-pointer envio-row hover:bg-surface-secondary/50 transition-colors" 
                            data-id="{{ $envio->id }}" 
                            onclick="selectEnvio({{ $envio->id }}, '{{ $envio->codigo }}', '{{ $envio->remitente_nombre }}', '{{ $envio->destinatario_nombre }}', '{{ $envio->destinatario_email }}', '{{ $envio->estado }}', '{{ addslashes($envio->descripcion ?? '') }}', '{{ $envio->repartidor?->nombre ?? '' }}', '{{ $envio->fecha_creacion->format('d/m/Y H:i') }}', '{{ $envio->peso ?? '' }}', '{{ $envio->tipo_envio ?? '' }}', '{{ $envio->foto_paquete ?? '' }}')">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full {{ $statusColors[$envio->estado] ?? 'bg-foreground-muted' }} ring-4 ring-opacity-20 {{ str_replace('bg-', 'ring-', $statusColors[$envio->estado] ?? 'ring-foreground-muted') }}"></span>
                                    <span class="font-medium text-foreground">{{ $envio->codigo ?? "Sin codigo" }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-foreground">{{ $envio->remitente_nombre }}</div>
                                @if($envio->remitente_telefono)
                                    <div class="text-sm text-foreground-muted">{{ $envio->remitente_telefono }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-foreground">{{ $envio->destinatario_nombre }}</div>
                                <div class="text-sm text-foreground-muted">{{ $envio->destinatario_email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $estadoClasses = [
                                        'pendiente' => 'bg-warning/10 text-warning',
                                        'en_ruta' => 'bg-primary/10 text-primary',
                                        'entregado' => 'bg-success/10 text-success',
                                        'devuelto' => 'bg-danger/10 text-danger',
                                        'cancelado' => 'bg-surface-secondary text-foreground-muted'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-lg font-medium text-sm {{ $estadoClasses[$envio->estado] ?? 'bg-surface-secondary text-foreground-muted' }}">
                                    {{ $estados[$envio->estado] ?? $envio->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-foreground-muted">
                                @if($envio->repartidor)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                                            <span class="text-sm font-semibold text-white">
                                                {{ strtoupper(substr($envio->repartidor->nombre, 0, 2)) }}
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-foreground">{{ $envio->repartidor->nombre }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-surface-secondary flex items-center justify-center">
                                            <span class="text-sm font-semibold text-foreground-muted">
                                                --
                                            </span>
                                        </div>
                                        <span class="text-foreground-muted italic text-sm">Sin asignar</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-2 rounded-lg text-foreground-muted hover:text-primary hover:bg-primary/10 transition-colors" onclick="event.stopPropagation()" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button class="p-2 rounded-lg text-foreground-muted hover:text-warning hover:bg-warning/10 transition-colors" onclick="event.stopPropagation()" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if($envio->estado !== 'entregado')
                                        <button type="button" onclick="event.stopPropagation(); confirmCancel({{ $envio->id }}, '{{ $envio->codigo }}')" class="p-2 rounded-lg text-foreground-muted hover:text-danger hover:bg-danger/10 transition-colors" title="Cancelar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-2xl bg-surface-secondary flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-foreground-muted mb-4">
                                        @if($search || $filterEstado)
                                            No se encontraron envíos que coincidan con los filtros aplicados
                                        @else
                                            No hay envíos registrados
                                        @endif
                                    </p>
                                    <a href="{{ route('envios.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-medium transition-colors">Crear envío</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($envios->hasPages())
            <div class="px-6 py-4 border-t border-border bg-surface-secondary">
                {{ $envios->links() }}
            </div>
            @endif
        </div>
    </div>
</div>