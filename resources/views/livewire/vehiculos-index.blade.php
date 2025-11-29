<div wire:poll.5s class="flex gap-6 h-full">
    {{-- Main Table Section --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-foreground">Vehiculos</h1>
                <p class="text-foreground-muted text-sm mt-1">Gestiona tu flota de vehiculos</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-secondary flex items-center justify-center">
                    <svg class="w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar vehiculo..." 
                        class="w-full pl-12 pr-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                {{-- Status Filter --}}
                <select wire:model.live="estado" class="px-4 py-3 bg-surface-secondary border border-border rounded-xl min-w-[160px] text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                    <option value="">Todos los estados</option>
                    <option value="disponible">Disponible</option>
                    <option value="asignado">Asignado</option>
                    <option value="mantenimiento">Mantenimiento</option>
                    <option value="inactivo">Inactivo</option>
                </select>

                {{-- Filter/Sort Toggle --}}
                <button type="button" class="p-3 bg-surface-secondary border border-border rounded-xl text-foreground-muted hover:text-primary hover:border-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                </button>

                {{-- Add Button --}}
                <a href="{{ route('vehiculos.create') }}" class="px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium flex items-center gap-2 transition-colors shadow-lg shadow-primary/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Agregar
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-surface rounded-2xl flex-1 overflow-hidden shadow-sm border border-border">
            <div class="overflow-x-auto h-full">
                <table class="w-full">
                    <thead class="bg-surface-secondary border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Marca</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Año</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-foreground-muted">Placa</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-foreground-muted">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($vehiculos as $vehiculo)
                        @php
                            $statusColors = [
                                'disponible' => 'bg-success',
                                'asignado' => 'bg-primary',
                                'mantenimiento' => 'bg-warning',
                                'inactivo' => 'bg-danger',
                            ];
                        @endphp
                        <tr class="cursor-pointer vehiculo-row hover:bg-surface-secondary/50 transition-colors" 
                            data-id="{{ $vehiculo->id }}" 
                            onclick="selectVehiculo({{ $vehiculo->id }}, '{{ $vehiculo->marca }}', '{{ $vehiculo->modelo }}', '{{ $vehiculo->placa }}', '{{ $vehiculo->anio }}', '{{ $vehiculo->estado }}', '{{ addslashes($vehiculo->observaciones) }}', {{ json_encode(json_decode($vehiculo->foto, true) ?? []) }}, '{{ route('vehiculos.edit', $vehiculo) }}', '{{ route('vehiculos.show', $vehiculo) }}')"
                            ondblclick="window.location.href='{{ route('vehiculos.show', $vehiculo) }}'">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full {{ $statusColors[$vehiculo->estado] ?? 'bg-foreground-muted' }} ring-4 ring-opacity-20 {{ str_replace('bg-', 'ring-', $statusColors[$vehiculo->estado] ?? 'ring-foreground-muted') }}"></span>
                                    <span class="font-medium text-foreground">{{ $vehiculo->modelo }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-foreground-muted">{{ $vehiculo->marca }}</td>
                            <td class="px-6 py-4 text-foreground-muted">{{ $vehiculo->anio ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-surface-secondary text-foreground rounded-lg font-mono text-sm">{{ $vehiculo->placa }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('vehiculos.show', $vehiculo) }}" class="p-2 rounded-lg text-foreground-muted hover:text-primary hover:bg-primary/10 transition-colors" onclick="event.stopPropagation()" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="p-2 rounded-lg text-foreground-muted hover:text-warning hover:bg-warning/10 transition-colors" onclick="event.stopPropagation()" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="event.stopPropagation(); confirmDelete({{ $vehiculo->id }}, '{{ $vehiculo->modelo }}')" class="p-2 rounded-lg text-foreground-muted hover:text-danger hover:bg-danger/10 transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <form id="delete-form-{{ $vehiculo->id }}" action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-2xl bg-surface-secondary flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                    <p class="text-foreground-muted mb-4">No hay vehiculos registrados</p>
                                    <a href="{{ route('vehiculos.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-medium transition-colors">Agregar vehiculo</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vehiculos->hasPages())
            <div class="px-6 py-4 border-t border-border bg-surface-secondary">
                {{ $vehiculos->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Preview Panel --}}
    <div class="w-80 flex-shrink-0">
        <div class="bg-surface rounded-2xl p-6 h-full flex flex-col shadow-sm border border-border" id="preview-panel">
            {{-- Default state --}}
            <div id="preview-empty" class="flex-1 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 rounded-2xl bg-surface-secondary flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <p class="text-foreground-muted text-sm">Selecciona un vehiculo para ver sus detalles</p>
            </div>

            {{-- Vehicle details --}}
            <div id="preview-content" class="hidden flex-1 flex flex-col">
                {{-- Image --}}
                <div class="aspect-[4/3] rounded-xl overflow-hidden bg-surface-secondary mb-4">
                    <img id="preview-image" src="/placeholder.svg?height=200&width=300" alt="" class="w-full h-full object-cover hidden">
                    <div id="preview-no-image" class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                {{-- Info --}}
                <h3 id="preview-name" class="text-xl font-bold text-foreground mb-2"></h3>
                <p id="preview-desc" class="text-foreground-muted text-sm mb-4 line-clamp-3"></p>

                {{-- Details --}}
                <div class="space-y-3 mb-6 flex-1">
                    <div class="flex items-center justify-between text-sm py-2 border-b border-border">
                        <span class="text-foreground-muted">Marca</span>
                        <span id="preview-marca" class="text-foreground font-medium"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm py-2 border-b border-border">
                        <span class="text-foreground-muted">Año</span>
                        <span id="preview-anio" class="text-foreground font-medium"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm py-2 border-b border-border">
                        <span class="text-foreground-muted">Placa</span>
                        <span id="preview-placa" class="text-foreground font-mono bg-surface-secondary px-2 py-0.5 rounded"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm py-2">
                        <span class="text-foreground-muted">Estado</span>
                        <span id="preview-estado" class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" id="preview-status-dot"></span>
                            <span class="text-foreground" id="preview-status-text"></span>
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <a id="preview-edit-btn" href="#" class="flex-1 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl text-center text-sm font-medium transition-colors">
                        Editar
                    </a>
                    <a id="preview-view-btn" href="#" class="flex-1 py-2.5 bg-surface-secondary hover:bg-border text-foreground rounded-xl text-center text-sm font-medium transition-colors">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
