@extends('layouts.app')

@section('title', 'Editar Envío #' . $envio->codigo)

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-foreground">Editar Envío #{{ $envio->codigo }}</h1>
                    <p class="text-foreground-muted text-sm mt-1">Modifica los detalles del envío</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('envios.show', $envio) }}" class="px-4 py-2 bg-surface-secondary border border-border rounded-xl text-foreground hover:bg-border transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </a>
                    <a href="{{ route('envios.index') }}" class="px-4 py-2 bg-surface-secondary border border-border rounded-xl text-foreground hover:bg-border transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('envios.update', $envio) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Información del Paquete --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Información del Paquete
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Número de Seguimiento *</label>
                            <input type="text" 
                                   name="codigo" 
                                   value="{{ old('codigo', $envio->codigo) }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('codigo') border-danger @enderror" 
                                   disabled
                                   required>
                            @error('codigo')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Estado *</label>
                            <select name="estado" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer @error('estado') border-danger @enderror" 
                                    required>
                                <option value="">Seleccionar Estado</option>
                                <option value="pendiente" {{ old('estado', $envio->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_ruta" {{ old('estado', $envio->estado) == 'en_ruta' ? 'selected' : '' }}>En Ruta</option>
                                <option value="entregado" {{ old('estado', $envio->estado) == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ old('estado', $envio->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Peso (kg)</label>
                            <input type="number" step="0.01" min="0" 
                                   name="peso" 
                                   value="{{ old('peso', $envio->peso) }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('peso') border-danger @enderror" 
                                   placeholder="0.00">
                            @error('peso')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Tipo de Envío</label>
                            <select name="tipo_envio" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer @error('tipo_envio') border-danger @enderror">
                                <option value="">Seleccione el tipo</option>
                                <option value="express" {{ old('tipo_envio', $envio->tipo_envio) == 'express' ? 'selected' : '' }}>Express (24h)</option>
                                <option value="normal" {{ old('tipo_envio', $envio->tipo_envio) == 'normal' ? 'selected' : '' }}>Normal (2-3 días)</option>
                                <option value="economico" {{ old('tipo_envio', $envio->tipo_envio) == 'economico' ? 'selected' : '' }}>Económico (5-7 días)</option>
                            </select>
                            @error('tipo_envio')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-foreground mb-2">Descripción del Contenido</label>
                            <textarea name="descripcion" rows="3" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none @error('descripcion') border-danger @enderror" 
                                placeholder="Describa el contenido del paquete">{{ old('descripcion', $envio->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Información del Remitente --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Información del Remitente
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Nombre Completo *</label>
                            <input type="text" 
                                   name="remitente_nombre" 
                                   value="{{ old('remitente_nombre', $envio->remitente_nombre) }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('remitente_nombre') border-danger @enderror" 
                                   placeholder="Ingrese el nombre del remitente" 
                                   required>
                            @error('remitente_nombre')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Teléfono</label>
                            <input type="tel" 
                                   name="remitente_telefono" 
                                   value="{{ old('remitente_telefono', $envio->remitente_telefono) }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('remitente_telefono') border-danger @enderror" 
                                   placeholder="Número de teléfono">
                            @error('remitente_telefono')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-foreground mb-2">Dirección *</label>
                            <textarea name="remitente_direccion" rows="3" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none @error('remitente_direccion') border-danger @enderror" 
                                placeholder="Dirección completa del remitente" 
                                required>{{ old('remitente_direccion', $envio->remitente_direccion) }}</textarea>
                            @error('remitente_direccion')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Información del Destinatario --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Información del Destinatario
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Nombre Completo *</label>
                            <input type="text" 
                                   name="destinatario_nombre" 
                                   value="{{ old('destinatario_nombre', $envio->destinatario_nombre) }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('destinatario_nombre') border-danger @enderror" 
                                   placeholder="Ingrese el nombre del destinatario" 
                                   required>
                            @error('destinatario_nombre')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Email *</label>
                            <input type="email" 
                                   name="destinatario_email" 
                                   value="{{ old('destinatario_email', $envio->destinatario_email) }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('destinatario_email') border-danger @enderror" 
                                   placeholder="correo@ejemplo.com" 
                                   required>
                            @error('destinatario_email')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Teléfono</label>
                            <input type="tel" 
                                   name="destinatario_telefono" 
                                   value="{{ old('destinatario_telefono', $envio->destinatario_telefono) }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('destinatario_telefono') border-danger @enderror" 
                                   placeholder="Número de teléfono">
                            @error('destinatario_telefono')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-foreground mb-2">Dirección de Entrega *</label>
                            <textarea name="destinatario_direccion" rows="3" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none @error('destinatario_direccion') border-danger @enderror" 
                                placeholder="Dirección completa de entrega" 
                                required>{{ old('destinatario_direccion', $envio->destinatario_direccion) }}</textarea>
                            @error('destinatario_direccion')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Programación y Asignación --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1l-1 1v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4L3 9V8a2 2 0 012-2h3z"/>
                        </svg>
                        Programación y Asignación
                    </h2>
                    
                    {{-- Información de Asignación Actual --}}
                    <div class="bg-gradient-to-r from-primary/5 to-info/5 border border-primary/10 rounded-xl p-4 mb-6">
                        <h3 class="text-sm font-semibold text-foreground mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Asignación Actual
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-foreground-muted">Fecha Estimada:</span>
                                <p class="font-medium text-foreground">{{ $envio->fecha_estimada ? $envio->fecha_estimada->format('d/m/Y') : 'No definida' }}</p>
                            </div>
                            <div>
                                <span class="text-foreground-muted">Repartidor:</span>
                                <p class="font-medium text-foreground">{{ $envio->repartidor ? $envio->repartidor->nombre : 'Sin asignar' }}</p>
                            </div>
                            <div>
                                <span class="text-foreground-muted">Vehículo:</span>
                                <p class="font-medium text-foreground">
                                    @if($envio->vehiculoAsignacion?->vehiculo)
                                        {{ $envio->vehiculoAsignacion->vehiculo->marca }} {{ $envio->vehiculoAsignacion->vehiculo->modelo }} ({{ $envio->vehiculoAsignacion->vehiculo->placa }})
                                    @else
                                        Sin asignar
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Fecha Estimada de Entrega *</label>
                            <input type="date" 
                                   name="fecha_estimada" 
                                   id="fecha_estimada" 
                                   value="{{ old('fecha_estimada', $envio->fecha_estimada ? $envio->fecha_estimada->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer @error('fecha_estimada') border-danger @enderror" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('fecha_estimada')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Hora para Disponibilidad</label>
                            <input type="time" id="hora_disponibilidad" value="08:00" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                            <small class="text-foreground-muted text-xs">Solo para verificar disponibilidad de recursos</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">Repartidor y Vehículo</label>
                            <select name="vehiculo_asignacion_id" 
                                    id="vehiculo_asignacion_id" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer @error('vehiculo_asignacion_id') border-danger @enderror">
                                <option value="">Primero selecciona una fecha</option>
                            </select>
                            @error('vehiculo_asignacion_id')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-3">
                            <div id="availability-info" class="bg-info/10 border border-info/20 rounded-xl p-3">
                                <p class="text-info text-sm">
                                    <strong>Selecciona una fecha</strong> para ver los repartidores y vehículos disponibles para ese día.
                                </p>
                            </div>
                            <div id="loading-resources" class="hidden bg-warning/10 border border-warning/20 rounded-xl p-3 mt-2">
                                <p class="text-warning text-sm flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Cargando recursos disponibles...
                                </p>
                            </div>
                            <div id="no-resources" class="hidden bg-warning/10 border border-warning/20 rounded-xl p-3 mt-2">
                                <p class="text-warning text-sm">
                                    <strong>No hay repartidores disponibles</strong> para la fecha seleccionada.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Foto del Paquete --}}
                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                    <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Foto del Paquete
                    </h2>
                    <div class="grid grid-cols-1 gap-4">
                        @if($envio->foto_paquete)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-foreground mb-2">Foto actual:</p>
                                <img src="{{ Storage::url($envio->foto_paquete) }}" 
                                     alt="Foto del paquete" 
                                     class="w-32 h-32 object-cover rounded-xl border border-border cursor-pointer hover:opacity-75 transition-opacity"
                                     onclick="openImageModal('{{ Storage::url($envio->foto_paquete) }}')">
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">{{ $envio->foto_paquete ? 'Cambiar Foto del Paquete' : 'Foto del Paquete' }}</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="foto_paquete" class="flex flex-col items-center justify-center w-full h-32 border-2 border-border border-dashed rounded-xl cursor-pointer bg-surface-secondary hover:bg-border transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-foreground-muted"><span class="font-semibold">Haz clic para subir</span> o arrastra la imagen</p>
                                        <p class="text-xs text-foreground-muted">PNG, JPG o GIF (MAX. 2MB)</p>
                                    </div>
                                    <input id="foto_paquete" name="foto_paquete" type="file" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            @error('foto_paquete')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-border">
                    <a href="{{ route('envios.show', $envio) }}" class="px-6 py-3 bg-surface-secondary border border-border rounded-xl text-foreground hover:bg-border transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors shadow-lg shadow-primary/20 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Actualizar Envío
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Modal para ver imágenes -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="max-w-4xl max-h-full p-4">
        <div class="relative">
            <img id="modalImage" src="" alt="Imagen del envío" class="max-w-full max-h-screen rounded-xl">
            <button onclick="closeImageModal()" 
                    class="absolute top-2 right-2 bg-surface rounded-full p-2 hover:bg-surface-secondary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview de imagen
document.getElementById('foto_paquete').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Crear preview si no existe
            let preview = document.getElementById('image-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'image-preview';
                preview.className = 'mt-2 h-20 w-20 object-cover rounded-lg border border-border';
                e.target.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Funciones del modal de imagen
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageSrc;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Cerrar modal al hacer clic fuera de la imagen
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Función para cargar recursos disponibles basado en fecha y hora
async function cargarRecursosDisponibles() {
    const fecha = document.getElementById('fecha_estimada').value;
    const hora = document.getElementById('hora_disponibilidad').value || '08:00';
    const vehiculoSelect = document.getElementById('vehiculo_asignacion_id');
    const loadingDiv = document.getElementById('loading-resources');
    const noResourcesDiv = document.getElementById('no-resources');
    const infoDiv = document.getElementById('availability-info');
    
    // Reset
    vehiculoSelect.innerHTML = '<option value="">Cargando...</option>';
    loadingDiv.classList.remove('hidden');
    noResourcesDiv.classList.add('hidden');
    infoDiv.classList.add('hidden');
    
    if (!fecha) {
        vehiculoSelect.innerHTML = '<option value="">Primero selecciona una fecha</option>';
        loadingDiv.classList.add('hidden');
        infoDiv.classList.add('bg-info/10', 'border-info/20');
        infoDiv.classList.remove('hidden');
        return;
    }
    
    try {
        const response = await fetch(`{{ route('envios.available-resources') }}?fecha=${fecha}&hora=${hora}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        loadingDiv.classList.add('hidden');
        
        vehiculoSelect.innerHTML = '<option value="">Sin asignar</option>';
        
        if (data.recursos && data.recursos.length > 0) {
            // Verificar si hay asignación actual
            const currentAssignment = {{ $envio->vehiculoAsignacion ? $envio->vehiculoAsignacion->id : 'null' }};
            
            data.recursos.forEach(recurso => {
                const option = document.createElement('option');
                option.value = recurso.id;
                option.textContent = recurso.descripcion;
                // Preseleccionar si coincide con la asignación actual
                if (recurso.id == currentAssignment) {
                    option.selected = true;
                }
                vehiculoSelect.appendChild(option);
            });
            
            infoDiv.classList.remove('bg-info/10', 'border-info/20');
            infoDiv.classList.add('bg-success/10', 'border-success/20');
            infoDiv.innerHTML = `
                <p class="text-success text-sm">
                    <strong>${data.recursos.length} recurso(s) disponible(s)</strong> encontrado(s) para el ${new Date(new Date(fecha).getTime() + (24 * 60 * 60 * 1000)).toLocaleDateString()} a las ${hora}.
                </p>
            `;
            infoDiv.classList.remove('hidden');
        } else {
            noResourcesDiv.classList.remove('hidden');
        }
        
    } catch (error) {
        console.error('Error loading available resources:', error);
        loadingDiv.classList.add('hidden');
        vehiculoSelect.innerHTML = '<option value="">Error al cargar recursos</option>';
        infoDiv.classList.remove('bg-info/10', 'border-info/20');
        infoDiv.classList.add('bg-danger/10', 'border-danger/20');
        infoDiv.innerHTML = `
            <p class="text-danger text-sm">
                <strong>Error al cargar recursos.</strong> Intenta nuevamente.
            </p>
        `;
        infoDiv.classList.remove('hidden');
    }
}

// Event listeners para fecha y hora
document.getElementById('fecha_estimada').addEventListener('change', cargarRecursosDisponibles);
document.getElementById('hora_disponibilidad').addEventListener('change', cargarRecursosDisponibles);

// Trigger initial load
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha_estimada');
    if (fechaInput.value) {
        cargarRecursosDisponibles();
    }
});
</script>
@endpush
@endsection