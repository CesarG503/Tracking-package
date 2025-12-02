@extends('layouts.app')

@section('title', 'Crear Envío')

@push('styles')
<style>
    .map-container {
        height: 300px;
        width: 100%;
        border-radius: 0.75rem;
        z-index: 1;
    }
    .step-indicator {
        position: relative;
        z-index: 10;
    }
    .step-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: 0;
        transform: translateY(-50%);
    }
    .dark .step-line {
        background-color: #374151;
    }
    .step-circle {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .dark .step-circle {
        background-color: #1f2937;
        border-color: #374151;
    }
    .step-circle.active {
        border-color: #3b82f6;
        background-color: #3b82f6;
        color: white;
    }
    .step-circle.completed {
        border-color: #10b981;
        background-color: #10b981;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-foreground">Crear Nuevo Envío</h1>
                    <p class="text-foreground-muted text-sm mt-1">Complete los pasos para registrar un envío</p>
                </div>
                <a href="{{ route('envios.index') }}" class="px-4 py-2 bg-surface-secondary border border-border rounded-xl text-foreground hover:bg-border transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>

            {{-- Stepper --}}
            <div class="mb-8 px-4">
                <div class="step-indicator flex justify-between items-center relative">
                    <div class="step-line"></div>
                    
                    <!-- Step 1 Indicator -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="step-circle-1" class="step-circle active font-bold">1</div>
                        <span class="mt-2 text-sm font-medium text-foreground">Direcciones</span>
                    </div>

                    <!-- Step 2 Indicator -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="step-circle-2" class="step-circle font-bold text-foreground-muted">2</div>
                        <span class="mt-2 text-sm font-medium text-foreground-muted">Paquete</span>
                    </div>

                    <!-- Step 3 Indicator -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="step-circle-3" class="step-circle font-bold text-foreground-muted">3</div>
                        <span class="mt-2 text-sm font-medium text-foreground-muted">Programación</span>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            {{-- Added novalidate to handle validation manually via JS and avoid browser blocking hidden fields --}}
            <form action="{{ route('envios.store') }}" method="POST" enctype="multipart/form-data" id="envio-form" class="space-y-6" novalidate>
                @csrf
                
                {{-- Hidden Inputs for Coordinates (Recipient) --}}
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">

                {{-- STEP 1: Remitente y Destinatario --}}
                <div id="step-1" class="step-content">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Remitente -->
                        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-foreground flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Remitente
                                </h2>
                                <button type="button" id="toggle-sender-map" class="text-xs px-3 py-1.5 bg-surface-secondary border border-border rounded-lg text-foreground hover:bg-border transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Mapa
                                </button>
                            </div>

                            <!-- Sender Map Container (Hidden by default) -->
                            <div id="sender-map-container" class="hidden mb-4 transition-all duration-300">
                                <label class="block text-sm font-medium text-foreground mb-2">Seleccionar ubicación del remitente</label>
                                <div id="map-sender" class="map-container border border-border shadow-inner"></div>
                                <p class="text-xs text-foreground-muted mt-1">Haga clic para establecer la dirección.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-foreground mb-2">Nombre Completo</label>
                                    <input type="text" name="remitente_nombre" id="remitente_nombre"
                                        class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary" 
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-foreground mb-2">Teléfono</label>
                                    <input type="tel" name="remitente_telefono" id="remitente_telefono"
                                        class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-foreground mb-2">Dirección</label>
                                    <textarea name="remitente_direccion" id="remitente_direccion" rows="3" 
                                        class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary resize-none" 
                                        required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Destinatario & Mapa -->
                        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                            <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Destinatario
                            </h2>
                            
                            <!-- Recipient Map Container -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-foreground mb-2">Ubicación de Entrega (Click en el mapa)</label>
                                <div id="map-recipient" class="map-container border border-border shadow-inner"></div>
                                <p class="text-xs text-foreground-muted mt-1">Haga clic en el mapa para establecer la dirección automáticamente.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-foreground mb-2">Nombre Completo</label>
                                    <input type="text" name="destinatario_nombre" value="{{ old('destinatario_nombre') }}" 
                                        class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary" 
                                        placeholder="Nombre del destinatario" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-foreground mb-2">Email</label>
                                        <input type="email" name="destinatario_email" value="{{ old('destinatario_email') }}" 
                                            class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary" 
                                            placeholder="correo@ejemplo.com" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-foreground mb-2">Teléfono</label>
                                        <input type="tel" name="destinatario_telefono" value="{{ old('destinatario_telefono') }}" 
                                            class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary" 
                                            placeholder="Teléfono">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-foreground mb-2">Dirección de Entrega</label>
                                    <textarea name="destinatario_direccion" id="destinatario_direccion" rows="2" 
                                        class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary resize-none" 
                                        placeholder="La dirección aparecerá aquí al seleccionar en el mapa" required>{{ old('destinatario_direccion') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: Información del Paquete --}}
                <div id="step-2" class="step-content hidden">
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border max-w-3xl mx-auto">
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Detalles del Paquete
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-foreground mb-2">Descripción del Contenido</label>
                                <textarea name="descripcion" rows="4" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary resize-none" 
                                    placeholder="Describa el contenido del paquete">{{ old('descripcion') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-2">Peso (kg)</label>
                                <input type="number" step="0.01" min="0" name="peso" value="{{ old('peso') }}" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary" 
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-2">Tipo de Envío</label>
                                <select name="tipo_envio" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer" required>
                                    <option value="">Seleccione el tipo</option>
                                    <option value="express" {{ old('tipo_envio') == 'express' ? 'selected' : '' }}>Express (24h)</option>
                                    <option value="normal" {{ old('tipo_envio') == 'normal' ? 'selected' : '' }}>Normal (2-3 días)</option>
                                    <option value="economico" {{ old('tipo_envio') == 'economico' ? 'selected' : '' }}>Económico (5-7 días)</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-foreground mb-2">Foto del Paquete</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="foto_paquete" class="flex flex-col items-center justify-center w-full h-40 border-2 border-border border-dashed rounded-xl cursor-pointer bg-surface-secondary hover:bg-border transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-4 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-foreground-muted"><span class="font-semibold">Haz clic para subir</span> o arrastra la imagen</p>
                                            <p class="text-xs text-foreground-muted">PNG, JPG o GIF (MAX. 2MB)</p>
                                        </div>
                                        <input id="foto_paquete" name="foto_paquete" type="file" class="hidden" accept="image/*" />
                                    </label>
                                </div>
                                <div id="image-preview-container" class="mt-4 hidden text-center">
                                    <p class="text-sm text-foreground mb-2">Vista previa:</p>
                                    <img id="image-preview" class="h-32 mx-auto object-cover rounded-lg border border-border shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: Programación --}}
                <div id="step-3" class="step-content hidden">
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border max-w-3xl mx-auto">
                        <h2 class="text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1l-1 1v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4L3 9V8a2 2 0 012-2h3z"/>
                            </svg>
                            Programación y Asignación
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-2">Fecha Estimada de Entrega</label>
                                <input type="date" name="fecha_estimada" id="fecha_estimada" value="{{ old('fecha_estimada', date('Y-m-d')) }}" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer" 
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-2">Hora de Disponibilidad</label>
                                <input type="time" id="hora_disponibilidad" value="08:00" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-foreground mb-2">Repartidor y Vehículo</label>
                                <select name="vehiculo_asignacion_id" id="vehiculo_asignacion_id" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                                    <option value="">Primero selecciona una fecha</option>
                                </select>
                            </div>
                            
                            {{-- Resource Status Messages --}}
                            <div class="md:col-span-2">
                                <div id="availability-info" class="bg-info/10 border border-info/20 rounded-xl p-4">
                                    <p class="text-info text-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Selecciona una fecha para consultar disponibilidad.
                                    </p>
                                </div>
                                <div id="loading-resources" class="hidden bg-warning/10 border border-warning/20 rounded-xl p-4 mt-2">
                                    <p class="text-warning text-sm flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Buscando recursos disponibles...
                                    </p>
                                </div>
                                <div id="no-resources" class="hidden bg-danger/10 border border-danger/20 rounded-xl p-4 mt-2">
                                    <p class="text-danger text-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        No hay recursos disponibles para esta fecha.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between pt-6 border-t border-border mt-8">
                    <button type="button" id="prev-btn" class="hidden px-6 py-3 bg-surface-secondary border border-border rounded-xl text-foreground hover:bg-border transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                    
                    <div class="flex-1"></div>

                    <button type="button" id="next-btn" class="px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors shadow-lg shadow-primary/20 flex items-center gap-2">
                        Siguiente
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <button type="submit" id="submit-btn" class="hidden px-6 py-3 bg-success hover:bg-success-hover text-white rounded-xl font-medium transition-colors shadow-lg shadow-success/20 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Finalizar Envío
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- WIZARD LOGIC ---
    let currentStep = 1;
    const totalSteps = 3;
    const form = document.getElementById('envio-form');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');

    function updateWizard() {
        // Show/Hide Steps
        for (let i = 1; i <= totalSteps; i++) {
            const stepDiv = document.getElementById(`step-${i}`);
            const circle = document.getElementById(`step-circle-${i}`);
            
            if (i === currentStep) {
                stepDiv.classList.remove('hidden');
                circle.classList.add('active');
                circle.classList.remove('completed');
            } else {
                stepDiv.classList.add('hidden');
                circle.classList.remove('active');
                if (i < currentStep) {
                    circle.classList.add('completed');
                    circle.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                } else {
                    circle.classList.remove('completed');
                    circle.innerHTML = i;
                }
            }
        }

        // Button Visibility
        prevBtn.classList.toggle('hidden', currentStep === 1);
        nextBtn.classList.toggle('hidden', currentStep === totalSteps);
        submitBtn.classList.toggle('hidden', currentStep !== totalSteps);
    }

    function validateStep(step) {
        const stepDiv = document.getElementById(`step-${step}`);
        const inputs = stepDiv.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if (!input.value) {
                valid = false;
                input.classList.add('border-danger');
                input.addEventListener('input', () => input.classList.remove('border-danger'), {once: true});
            }
        });
        return valid;
    }

    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateWizard();
                // Resize maps if needed
                if (currentStep === 1) {
                    setTimeout(() => {
                        if(mapRecipient) mapRecipient.invalidateSize();
                        if(mapSender) mapSender.invalidateSize();
                    }, 200);
                }
            }
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos',
                text: 'Por favor complete los campos obligatorios antes de continuar.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateWizard();
        }
    });
    
    // --- FORM SUBMISSION VALIDATION ---
    form.addEventListener('submit', function(e) {
        // Validate ALL steps before submitting
        let allValid = true;
        let firstInvalidStep = null;

        for (let i = 1; i <= totalSteps; i++) {
            if (!validateStep(i)) {
                allValid = false;
                if (!firstInvalidStep) firstInvalidStep = i;
            }
        }

        if (!allValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Formulario incompleto',
                text: 'Por favor revise los pasos anteriores, hay campos obligatorios sin completar.',
                confirmButtonText: 'Revisar'
            });
            if (firstInvalidStep) {
                currentStep = firstInvalidStep;
                updateWizard();
            }
        }
    });

    // --- LOCAL STORAGE LOGIC (SENDER) ---
    const senderFields = ['remitente_nombre', 'remitente_telefono', 'remitente_direccion'];
    
    // Load from LocalStorage
    senderFields.forEach(field => {
        const storedValue = localStorage.getItem(field);
        const input = document.getElementById(field);
        if (storedValue && input && !input.value) { // Only if empty (don't overwrite old() or user input)
            input.value = storedValue;
        }
        
        // Save to LocalStorage on input
        if (input) {
            input.addEventListener('input', function() {
                localStorage.setItem(field, this.value);
            });
        }
    });


    // --- MAP LOGIC (Leaflet) ---
    // Default center: San Miguel, El Salvador
    const defaultLat = 13.4834;
    const defaultLng = -88.1833;
    
    // 1. Recipient Map
    const mapRecipient = L.map('map-recipient').setView([defaultLat, defaultLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mapRecipient);

    let markerRecipient;

    mapRecipient.on('click', async function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;

        if (markerRecipient) {
            markerRecipient.setLatLng(e.latlng);
        } else {
            markerRecipient = L.marker(e.latlng).addTo(mapRecipient);
        }

        const addressField = document.getElementById('destinatario_direccion');
        addressField.value = "Cargando dirección...";
        addressField.classList.add('opacity-50');

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
            const data = await response.json();
            if (data && data.display_name) {
                addressField.value = data.display_name;
            } else {
                addressField.value = `${lat}, ${lng}`;
            }
        } catch (error) {
            console.error('Error fetching address:', error);
            addressField.value = `${lat}, ${lng}`;
        } finally {
            addressField.classList.remove('opacity-50');
        }
    });

    // 2. Sender Map (Collapsible)
    let mapSender = null;
    let markerSender = null;
    const toggleSenderMapBtn = document.getElementById('toggle-sender-map');
    const senderMapContainer = document.getElementById('sender-map-container');

    toggleSenderMapBtn.addEventListener('click', function() {
        senderMapContainer.classList.toggle('hidden');
        
        if (!senderMapContainer.classList.contains('hidden')) {
            if (!mapSender) {
                // Initialize map only when shown
                mapSender = L.map('map-sender').setView([defaultLat, defaultLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapSender);

                mapSender.on('click', async function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    if (markerSender) {
                        markerSender.setLatLng(e.latlng);
                    } else {
                        markerSender = L.marker(e.latlng).addTo(mapSender);
                    }

                    const addressField = document.getElementById('remitente_direccion');
                    addressField.value = "Cargando dirección...";
                    addressField.classList.add('opacity-50');

                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                        const data = await response.json();
                        if (data && data.display_name) {
                            addressField.value = data.display_name;
                            // Trigger input event to save to localStorage
                            addressField.dispatchEvent(new Event('input'));
                        } else {
                            addressField.value = `${lat}, ${lng}`;
                        }
                    } catch (error) {
                        console.error('Error fetching address:', error);
                        addressField.value = `${lat}, ${lng}`;
                    } finally {
                        addressField.classList.remove('opacity-50');
                    }
                });
            } else {
                setTimeout(() => mapSender.invalidateSize(), 200);
            }
        }
    });

    // --- IMAGE PREVIEW ---
    document.getElementById('foto_paquete').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const container = document.getElementById('image-preview-container');
                preview.src = e.target.result;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // --- RESOURCE LOADING ---
    async function cargarRecursosDisponibles() {
        const fecha = document.getElementById('fecha_estimada').value;
        const hora = document.getElementById('hora_disponibilidad').value || '08:00';
        const vehiculoSelect = document.getElementById('vehiculo_asignacion_id');
        const loadingDiv = document.getElementById('loading-resources');
        const noResourcesDiv = document.getElementById('no-resources');
        const infoDiv = document.getElementById('availability-info');
        
        vehiculoSelect.innerHTML = '<option value="">Cargando...</option>';
        loadingDiv.classList.remove('hidden');
        noResourcesDiv.classList.add('hidden');
        infoDiv.classList.add('hidden');
        
        if (!fecha) {
            vehiculoSelect.innerHTML = '<option value="">Primero selecciona una fecha</option>';
            loadingDiv.classList.add('hidden');
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
                data.recursos.forEach(recurso => {
                    const option = document.createElement('option');
                    option.value = recurso.id;
                    option.textContent = recurso.descripcion;
                    vehiculoSelect.appendChild(option);
                });
                infoDiv.classList.remove('hidden');
                infoDiv.classList.remove('bg-info/10', 'border-info/20');
                infoDiv.classList.add('bg-success/10', 'border-success/20');
                infoDiv.innerHTML = `<p class="text-success text-sm"><strong>${data.recursos.length} recurso(s) disponible(s)</strong></p>`;
            } else {
                noResourcesDiv.classList.remove('hidden');
            }
            
        } catch (error) {
            console.error('Error loading available resources:', error);
            loadingDiv.classList.add('hidden');
            vehiculoSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    document.getElementById('fecha_estimada').addEventListener('change', cargarRecursosDisponibles);
    document.getElementById('hora_disponibilidad').addEventListener('change', cargarRecursosDisponibles);
    
    if (document.getElementById('fecha_estimada').value) {
        cargarRecursosDisponibles();
    }
});
</script>
@endpush
@endsection