@extends('layouts.app')

@section('title', 'Nuevo Vehiculo')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('vehiculos.index') }}" class="w-10 h-10 rounded-xl bg-surface-secondary border border-border flex items-center justify-center text-foreground-muted hover:text-primary hover:border-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-foreground">Nuevo Vehiculo</h1>
                    <p class="text-foreground-muted mt-1">Registra un nuevo vehiculo en la flota</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('vehiculos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="vehiculo-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column - Form Fields --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border space-y-5">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Informacion del Vehiculo</h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="marca" class="block text-sm font-medium text-foreground-muted mb-2">Marca *</label>
                                <input type="text" name="marca" id="marca" value="{{ old('marca') }}" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('marca') !border-danger !ring-danger @enderror" 
                                    placeholder="Toyota">
                                @error('marca')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modelo" class="block text-sm font-medium text-foreground-muted mb-2">Modelo *</label>
                                <input type="text" name="modelo" id="modelo" value="{{ old('modelo') }}" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('modelo') !border-danger !ring-danger @enderror" 
                                    placeholder="Hilux">
                                @error('modelo')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="placa" class="block text-sm font-medium text-foreground-muted mb-2">Placa *</label>
                                <input type="text" name="placa" id="placa" value="{{ old('placa') }}" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground font-mono uppercase placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('placa') !border-danger !ring-danger @enderror" 
                                    placeholder="ABC-123">
                                @error('placa')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="anio" class="block text-sm font-medium text-foreground-muted mb-2">Año</label>
                                <input type="number" name="anio" id="anio" value="{{ old('anio') }}" min="1900" max="{{ date('Y') + 1 }}" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('anio') !border-danger !ring-danger @enderror" 
                                    placeholder="{{ date('Y') }}">
                                @error('anio')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="capacidad" class="block text-sm font-medium text-foreground-muted mb-2">Capacidad</label>
                                <input type="text" name="capacidad" id="capacidad" value="{{ old('capacidad') }}" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('capacidad') !border-danger !ring-danger @enderror" 
                                    placeholder="1000 kg">
                                @error('capacidad')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="estado" class="block text-sm font-medium text-foreground-muted mb-2">Estado *</label>
                                <select name="estado" id="estado" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent cursor-pointer @error('estado') !border-danger !ring-danger @enderror">
                                    <option value="disponible" {{ old('estado') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="en_uso" {{ old('estado') === 'en_uso' ? 'selected' : '' }}>En uso</option>
                                    <option value="mantenimiento" {{ old('estado') === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="observaciones" class="block text-sm font-medium text-foreground-muted mb-2">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="4" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none @error('observaciones') !border-danger !ring-danger @enderror" 
                                placeholder="Notas adicionales sobre el vehiculo...">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column - Photos --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Fotos del Vehiculo</h2>

                        {{-- Dropzone --}}
                        <div class="border-2 border-dashed border-border rounded-xl p-8 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all" id="dropzone" onclick="document.getElementById('fotos').click()">
                            <input type="file" name="fotos[]" id="fotos" multiple accept="image/*" class="hidden" onchange="handleFiles(this.files)">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-foreground font-medium mb-1">Arrastra fotos aqui</p>
                            <p class="text-foreground-muted text-sm">o haz clic para seleccionar</p>
                            <p class="text-foreground-muted text-xs mt-2">PNG, JPG, WEBP hasta 5MB</p>
                        </div>

                        {{-- Preview Grid --}}
                        <div id="preview-grid" class="grid grid-cols-3 gap-3 mt-4"></div>

                        @error('fotos.*')
                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('vehiculos.index') }}" class="px-6 py-3 text-foreground-muted font-medium rounded-xl hover:bg-surface-secondary transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors shadow-lg shadow-primary/20">
                        Guardar Vehiculo
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

@push('scripts')
<script>
const dropzone = document.getElementById('dropzone');
const input = document.getElementById('fotos');
const previewGrid = document.getElementById('preview-grid');
let selectedFiles = [];

// Drag and drop handlers
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
    dropzone.addEventListener(event, e => {
        e.preventDefault();
        e.stopPropagation();
    });
});

['dragenter', 'dragover'].forEach(event => {
    dropzone.addEventListener(event, () => {
        dropzone.classList.add('border-primary', 'bg-primary/5');
    });
});

['dragleave', 'drop'].forEach(event => {
    dropzone.addEventListener(event, () => {
        dropzone.classList.remove('border-primary', 'bg-primary/5');
    });
});

dropzone.addEventListener('drop', e => {
    handleFiles(e.dataTransfer.files);
});

function handleFiles(files) {
    Array.from(files).forEach(file => {
        if (!file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'error',
                title: 'Archivo no valido',
                text: 'Solo se permiten imagenes',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }
        
        selectedFiles.push(file);
        const currentIndex = selectedFiles.length - 1;
        
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'aspect-square rounded-xl overflow-hidden relative group';
            div.dataset.index = currentIndex;
            div.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button type="button" onclick="removeFile(${currentIndex}, this)" class="px-3 py-1.5 bg-danger text-white text-xs rounded-lg hover:bg-danger-hover transition-colors">
                        Eliminar
                    </button>
                </div>
            `;
            previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
    
    updateFileInput();
}

function removeFile(index, btn) {
    selectedFiles[index] = null;
    btn.closest('.aspect-square').remove();
    updateFileInput();
}

function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.filter(f => f !== null).forEach(file => dt.items.add(file));
    input.files = dt.files;
}

// Form validation with SweetAlert
document.getElementById('vehiculo-form').addEventListener('submit', function(e) {
    const marca = document.getElementById('marca').value.trim();
    const modelo = document.getElementById('modelo').value.trim();
    const placa = document.getElementById('placa').value.trim();
    
    if (!marca || !modelo || !placa) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Por favor completa todos los campos obligatorios',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl'
            }
        });
    }
});
</script>
@endpush
@endsection
