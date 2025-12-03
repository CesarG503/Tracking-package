<div>
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl lg:text-3xl font-bold text-foreground">Mi Calendarización</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Calendario --}}
            <div class="lg:col-span-2 glass-card glass-strong rounded-2xl p-6">
                {{-- Header Calendario --}}
                <div class="flex items-center justify-between mb-6">
                    <button wire:click="mesAnterior" class="p-2 rounded-lg glass glass-subtle hover:bg-surface transition-colors">
                        <svg class="w-5 h-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h2 class="text-xl font-bold text-foreground capitalize">{{ $nombreMes }}</h2>
                    <button wire:click="mesSiguiente" class="p-2 rounded-lg glass glass-subtle hover:bg-surface transition-colors">
                        <svg class="w-5 h-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                {{-- Días Semana --}}
                <div class="grid grid-cols-7 gap-2 mb-2 text-center">
                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                        <div class="text-sm font-semibold text-foreground-muted py-2">{{ $dia }}</div>
                    @endforeach
                </div>

                {{-- Grid Días --}}
                <div class="overflow-x-auto pb-2">
                    <div class="grid grid-cols-7 gap-2 min-w-[600px] lg:min-w-0">
                        @foreach($dias as $dia)
                            @php
                                $isSelected = $diaSeleccionado && $diaSeleccionado['fecha_raw'] === $dia['fecha'];
                            @endphp
                            <div wire:click="seleccionarDia('{{ $dia['fecha'] }}')" 
                                class="relative min-h-[100px] p-2 rounded-xl border transition-all cursor-pointer hover:shadow-md
                                {{ $dia['es_mes_actual'] ? 'bg-surface text-foreground' : 'bg-surface-secondary/50 text-foreground-muted opacity-60' }}
                                {{ $dia['es_hoy'] ? 'ring-2 ring-primary ring-offset-2 ring-offset-background' : '' }}
                                {{ $isSelected ? 'ring-2 ring-primary border-primary bg-primary/5 shadow-lg scale-[1.02] z-10' : 'border-border' }}">
                                
                                <span class="text-sm font-medium mb-1 block">{{ $dia['dia'] }}</span>
                                
                                <div class="space-y-1 overflow-y-auto max-h-[70px] scrollbar-hide">
                                    @forelse($dia['eventos'] as $evento)
                                        @php
                                            $colores = [
                                                'disponible' => 'bg-emerald-200 text-emerald-900 border-emerald-300 dark:bg-emerald-200 dark:text-emerald-900 dark:border-emerald-600',
                                                'ocupado' => 'bg-rose-200 text-rose-900 border-rose-300 dark:bg-rose-500/20 dark:text-rose-300 dark:border-rose-500/30',
                                                'vacaciones' => 'bg-amber-200 text-amber-900 border-amber-300 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-500/30',
                                                'bloqueo' => 'bg-slate-200 text-slate-900 border-slate-300 dark:bg-slate-500/20 dark:text-slate-300 dark:border-slate-500/30',
                                                'asignado_envio' => 'bg-blue-200 text-blue-900 border-blue-300 dark:bg-blue-500/20 dark:text-blue-300 dark:border-blue-500/30'
                                            ];
                                            $claseColor = $colores[$evento['tipo']] ?? $colores['disponible'];
                                        @endphp
                                        <div class="text-[10px] px-1.5 py-1 rounded-md border {{ $claseColor }} truncate font-semibold shadow-sm">
                                            {{ $evento['hora_inicio'] }} - {{ $evento['hora_fin'] }}
                                        </div>
                                    @empty
                                        {{-- Espacio vacío si no hay eventos --}}
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Panel Detalles --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Detalles del Día</h3>
                
                @if($diaSeleccionado)
                    <div class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                        <div>
                            <p class="text-sm text-foreground-muted">Fecha Seleccionada</p>
                            <p class="text-xl font-bold text-foreground capitalize">{{ $diaSeleccionado['fecha'] }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-foreground-muted mb-2">Horario y Estado</p>
                            <div class="space-y-2">
                                @forelse($diaSeleccionado['eventos'] as $evento)
                                    <div class="flex flex-col gap-2 p-3 rounded-lg glass glass-subtle">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-foreground">{{ $evento['horario'] }}</p>
                                                @if($evento['descripcion'])
                                                    <p class="text-xs text-foreground-muted mt-0.5">{{ $evento['descripcion'] }}</p>
                                                @endif
                                            </div>
                                            <span class="px-2 py-1 rounded text-xs font-semibold capitalize
                                                {{ $evento['tipo'] === 'disponible' ? 'bg-emerald-200 text-emerald-900 dark:bg-emerald-500/20 dark:text-emerald-300' : '' }}
                                                {{ $evento['tipo'] === 'ocupado' ? 'bg-rose-200 text-rose-900 dark:bg-rose-500/20 dark:text-rose-300' : '' }}
                                                {{ $evento['tipo'] === 'vacaciones' ? 'bg-amber-200 text-amber-900 dark:bg-amber-500/20 dark:text-amber-300' : '' }}
                                                {{ $evento['tipo'] === 'bloqueo' ? 'bg-slate-200 text-slate-900 dark:bg-slate-500/20 dark:text-slate-300' : '' }}
                                                {{ $evento['tipo'] === 'asignado_envio' ? 'bg-blue-200 text-blue-900 dark:bg-blue-500/20 dark:text-blue-300' : '' }}">
                                                {{ $evento['tipo'] }}
                                            </span>
                                        </div>
                                        
                                        @if($evento['vehiculo'])
                                            <div class="flex items-center gap-2 text-xs text-foreground-muted border-t border-border pt-2 mt-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                                <span>{{ $evento['vehiculo']['marca'] }} {{ $evento['vehiculo']['modelo'] }} - {{ $evento['vehiculo']['placa'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-foreground-muted text-sm">No hay eventos registrados para este día.</p>
                                @endforelse
                            </div>
                        </div>

                        @if($diaSeleccionado['vehiculo'])
                            <div class="glass glass-subtle rounded-xl p-4">
                                <p class="text-sm text-foreground-muted mb-2">Vehículo Asignado</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg glass glass-blue flex items-center justify-center">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-foreground">{{ $diaSeleccionado['vehiculo']['marca'] }} {{ $diaSeleccionado['vehiculo']['modelo'] }}</p>
                                        <p class="text-xs text-foreground-muted font-mono">{{ $diaSeleccionado['vehiculo']['placa'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif


                        
                        <button wire:click="cerrarDetalle" class="w-full glass glass-subtle py-2 rounded-xl text-sm font-medium hover:bg-surface transition-colors">
                            Cerrar detalles
                        </button>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-xl glass glass-subtle flex items-center justify-center">
                            <svg class="w-8 h-8 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-foreground-muted">Selecciona un día en el calendario para ver más detalles</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
