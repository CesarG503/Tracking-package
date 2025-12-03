<div wire:poll.5s class="h-full flex flex-col">
    <div class="flex flex-col h-full min-h-0">
        {{-- Header con Tabs --}}
        <div class="mb-4 lg:mb-6 shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold text-foreground">Envíos</h1>
                    <p class="text-foreground-muted text-sm mt-1 hidden sm:block">Gestiona y monitorea todos los envíos</p>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="toggleFilters" class="p-2 lg:p-3 rounded-xl text-foreground-muted hover:text-foreground hover:bg-surface-secondary transition-colors {{ $showFilters ? 'bg-primary/10 text-primary' : '' }}">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                    </button>
                    <a href="{{ route('envios.create') }}" class="px-4 py-2 lg:px-6 lg:py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium flex items-center gap-2 transition-colors shadow-lg shadow-primary/20">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="hidden sm:inline">Nuevo Envío</span>
                    </a>
                </div>
            </div>
            
            {{-- Time Tabs --}}
            <div class="flex items-center gap-1 bg-surface-secondary p-1 rounded-xl w-fit">
                <button wire:click="setTimeTab('hoy')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTimeTab === 'hoy' ? 'bg-white dark:bg-surface text-foreground shadow-sm' : 'text-foreground-muted hover:text-foreground' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Hoy
                </button>
                <button wire:click="setTimeTab('semana')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTimeTab === 'semana' ? 'bg-white dark:bg-surface text-foreground shadow-sm' : 'text-foreground-muted hover:text-foreground' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z"/>
                    </svg>
                    Esta Semana
                </button>
                <button wire:click="setTimeTab('todos')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 {{ $activeTimeTab === 'todos' ? 'bg-white dark:bg-surface text-foreground shadow-sm' : 'text-foreground-muted hover:text-foreground' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                    </svg>
                    Todos
                </button>
            </div>
        </div>

        {{-- Filtros Mejorados --}}
        @if($showFilters)
        <div class="bg-surface rounded-2xl p-4 lg:p-6 mb-4 lg:mb-6 shadow-sm border border-border shrink-0 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-foreground flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                    </svg>
                    Filtros Avanzados
                </h3>
                <button wire:click="toggleFilters" class="p-1 text-foreground-muted hover:text-foreground">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Búsqueda --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-foreground flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               placeholder="Código, remitente, destinatario..." 
                               class="w-full pl-10 pr-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Estado --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-foreground flex items-center gap-2">
                        <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Estado
                    </label>
                    <select wire:model.live="filterEstado" class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                        <option value="">Todos los estados</option>
                        @foreach($estados as $valor => $etiqueta)
                            <option value="{{ $valor }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Elementos por página --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-foreground flex items-center gap-2">
                        <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Mostrar
                    </label>
                    <select wire:model.live="perPage" class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                        <option value="5">5 elementos</option>
                        <option value="10">10 elementos</option>
                        <option value="25">25 elementos</option>
                        <option value="50">50 elementos</option>
                    </select>
                </div>
            </div>

            {{-- Acciones de filtros --}}
            @if($search || $filterEstado)
            <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-border">
                <button wire:click="clearFilters" class="px-4 py-2 bg-surface-secondary border border-border rounded-xl text-foreground-muted hover:text-danger hover:border-danger transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Limpiar filtros
                </button>
            </div>
            @endif
        </div>
        @else
        {{-- Barra de búsqueda rápida --}}
        <div class="bg-surface rounded-2xl p-3 mb-4 lg:mb-6 shadow-sm border border-border shrink-0">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Búsqueda rápida por código, remitente, destinatario..." 
                       class="w-full pl-12 pr-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        @endif

        {{-- Table --}}
        <div class="bg-surface rounded-2xl flex-1 overflow-hidden shadow-sm border border-border min-h-0">
            <div class="flex flex-col h-full">
                <div class="overflow-x-auto flex-1 overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-surface-secondary border-b border-border sticky top-0 z-10">
                            <tr>
                                <th class="px-3 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Código</th>
                                <th class="px-3 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted hidden sm:table-cell">Remitente</th>
                                <th class="px-3 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Destinatario</th>
                                <th class="px-3 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Estado</th>
                                <th class="px-3 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted hidden lg:table-cell">Repartidor</th>
                                <th class="px-3 lg:px-6 py-3 lg:py-4 text-right text-xs font-semibold uppercase tracking-wider text-foreground-muted">Acciones</th>
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
                            <td class="px-3 lg:px-6 py-3 lg:py-4">
                                <div class="flex items-center gap-2 lg:gap-3 min-w-0">
                                    <span class="w-2 h-2 lg:w-3 lg:h-3 rounded-full shrink-0 {{ $statusColors[$envio->estado] ?? 'bg-foreground-muted' }} ring-2 lg:ring-4 ring-opacity-20 {{ str_replace('bg-', 'ring-', $statusColors[$envio->estado] ?? 'ring-foreground-muted') }}"></span>
                                    <span class="font-medium text-foreground text-sm lg:text-base truncate max-w-20 sm:max-w-none" title="{{ $envio->codigo ?? 'Sin codigo' }}">{{ $envio->codigo ?? "Sin codigo" }}</span>
                                </div>
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 hidden sm:table-cell">
                                <div class="text-sm font-medium text-foreground truncate max-w-[120px] lg:max-w-none" title="{{ $envio->remitente_nombre }}">{{ $envio->remitente_nombre }}</div>
                                @if($envio->remitente_telefono)
                                    <div class="text-xs lg:text-sm text-foreground-muted truncate max-w-[120px] lg:max-w-none" title="{{ $envio->remitente_telefono }}">{{ $envio->remitente_telefono }}</div>
                                @endif
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4">
                                <div class="text-sm font-medium text-foreground truncate max-w-[100px] sm:max-w-[150px] lg:max-w-none" title="{{ $envio->destinatario_nombre }}">{{ $envio->destinatario_nombre }}</div>
                                <div class="text-xs lg:text-sm text-foreground-muted truncate max-w-[100px] sm:max-w-[150px] lg:max-w-none" title="{{ $envio->destinatario_email }}">{{ $envio->destinatario_email }}</div>
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 text-center">
                                @php
                                    $estadoClasses = [
                                        'pendiente' => 'bg-warning/10 text-warning',
                                        'en_ruta' => 'bg-primary/10 text-primary',
                                        'entregado' => 'bg-success/10 text-success',
                                        'devuelto' => 'bg-danger/10 text-danger',
                                        'cancelado' => 'bg-surface-secondary text-foreground-muted'
                                    ];
                                    $estadoTexto = $estados[$envio->estado] ?? $envio->estado;
                                @endphp
                                <span class="inline-block px-1.5 lg:px-3 py-1 rounded-lg font-medium text-xs lg:text-sm {{ $estadoClasses[$envio->estado] ?? 'bg-surface-secondary text-foreground-muted' }} max-w-20 lg:max-w-none truncate" title="{{ $estadoTexto }}">
                                    <span class="sm:hidden">{{ Str::limit($estadoTexto, 8, '') }}</span>
                                    <span class="hidden sm:inline">{{ $estadoTexto }}</span>
                                </span>
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 text-foreground-muted hidden lg:table-cell">
                                @if($envio->repartidor)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 lg:w-8 lg:h-8 rounded-full bg-primary flex items-center justify-center">
                                            <span class="text-xs lg:text-sm font-semibold text-white">
                                                {{ strtoupper(substr($envio->repartidor->nombre, 0, 2)) }}
                                            </span>
                                        </div>
                                        <span class="text-xs lg:text-sm font-medium text-foreground hidden xl:inline">{{ $envio->repartidor->nombre }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 lg:w-8 lg:h-8 rounded-full bg-surface-secondary flex items-center justify-center">
                                            <span class="text-xs lg:text-sm font-semibold text-foreground-muted">
                                                --
                                            </span>
                                        </div>
                                        <span class="text-foreground-muted italic text-xs lg:text-sm hidden xl:inline">Sin asignar</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-1 sm:px-3 lg:px-6 py-3 lg:py-4">
                                <div class="flex items-center justify-end gap-0.5 sm:gap-1">
                                    <a href="{{ route('envios.show', $envio) }}" class="p-0.5 sm:p-1 lg:p-1.5 xl:p-2 rounded-lg text-foreground-muted hover:text-primary hover:bg-primary/10 transition-colors" onclick="event.stopPropagation()" title="Ver">
                                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('envios.edit', $envio) }}" class="p-0.5 sm:p-1 lg:p-1.5 xl:p-2 rounded-lg text-foreground-muted hover:text-warning hover:bg-warning/10 transition-colors" onclick="event.stopPropagation()" title="Editar">
                                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($envio->estado !== 'entregado')
                                        <button type="button" onclick="event.stopPropagation(); confirmCancel({{ $envio->id }}, '{{ $envio->codigo }}')" class="p-0.5 sm:p-1 lg:p-1.5 xl:p-2 rounded-lg text-foreground-muted hover:text-danger hover:bg-danger/10 transition-colors" title="Cancelar">
                                            <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 lg:px-6 py-8 lg:py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl bg-surface-secondary flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 lg:w-10 lg:h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="px-3 lg:px-6 py-3 lg:py-4 border-t border-border bg-surface-secondary shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($envios->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-foreground-muted bg-surface border border-border cursor-default leading-5 rounded-md">
                                    Anterior
                                </span>
                            @else
                                <button wire:click="previousPage" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-foreground bg-surface border border-border leading-5 rounded-md hover:text-primary focus:outline-none focus:ring ring-primary focus:border-primary active:bg-surface-secondary active:text-foreground transition ease-in-out duration-150">
                                    Anterior
                                </button>
                            @endif

                            @if ($envios->hasMorePages())
                                <button wire:click="nextPage" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-foreground bg-surface border border-border leading-5 rounded-md hover:text-primary focus:outline-none focus:ring ring-primary focus:border-primary active:bg-surface-secondary active:text-foreground transition ease-in-out duration-150">
                                    Siguiente
                                </button>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-foreground-muted bg-surface border border-border cursor-default leading-5 rounded-md">
                                    Siguiente
                                </span>
                            @endif
                        </div>

                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-foreground-muted leading-5">
                                    Mostrando
                                    <span class="font-medium">{{ $envios->firstItem() }}</span>
                                    a
                                    <span class="font-medium">{{ $envios->lastItem() }}</span>
                                    de
                                    <span class="font-medium">{{ $envios->total() }}</span>
                                    resultados
                                </p>
                            </div>

                            <div>
                                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                    {{-- Previous Page Link --}}
                                    @if ($envios->onFirstPage())
                                        <span aria-disabled="true" aria-label="Anterior">
                                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-foreground-muted bg-surface border border-border cursor-default rounded-l-md leading-5" aria-hidden="true">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </span>
                                    @else
                                        <button wire:click="previousPage" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-foreground bg-surface border border-border rounded-l-md leading-5 hover:text-primary focus:z-10 focus:outline-none focus:ring ring-primary focus:border-primary active:bg-surface-secondary active:text-foreground transition ease-in-out duration-150" aria-label="Anterior">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($envios->getUrlRange(1, $envios->lastPage()) as $page => $url)
                                        @if ($page == $envios->currentPage())
                                            <span aria-current="page">
                                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-primary border border-primary cursor-default leading-5">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-foreground bg-surface border border-border leading-5 hover:text-primary focus:z-10 focus:outline-none focus:ring ring-primary focus:border-primary active:bg-surface-secondary active:text-foreground transition ease-in-out duration-150" aria-label="Ir a la página {{ $page }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($envios->hasMorePages())
                                        <button wire:click="nextPage" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-foreground bg-surface border border-border rounded-r-md leading-5 hover:text-primary focus:z-10 focus:outline-none focus:ring ring-primary focus:border-primary active:bg-surface-secondary active:text-foreground transition ease-in-out duration-150" aria-label="Siguiente">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @else
                                        <span aria-disabled="true" aria-label="Siguiente">
                                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-foreground-muted bg-surface border border-border cursor-default rounded-r-md leading-5" aria-hidden="true">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>