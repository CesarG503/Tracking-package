@extends('layouts.app')

@section('title', 'Editar Vehiculo')

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
                    <h1 class="text-2xl font-bold text-foreground">Editar Vehiculo</h1>
                    <p class="text-foreground-muted mt-1">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} - {{ $vehiculo->placa }}</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column - Form Fields --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border space-y-5">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Informacion del Vehiculo</h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="marca" class="block text-sm font-medium text-foreground-muted mb-2">Marca *</label>
                                <input type="text" name="marca" id="marca" value="{{ old('marca', $vehiculo->marca) }}" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('marca') !border-danger @enderror">
                                @error('marca')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modelo" class="block text-sm font-medium text-foreground-muted mb-2">Modelo *</label>
                                <input type="text" name="modelo" id="modelo" value="{{ old('modelo', $vehiculo->modelo) }}" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('modelo') !border-danger @enderror">
                                @error('modelo')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="placa" class="block text-sm font-medium text-foreground-muted mb-2">Placa *</label>
                                <input type="text" name="placa" id="placa" value="{{ old('placa', $vehiculo->placa) }}" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground font-mono uppercase focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('placa') !border-danger @enderror">
                                @error('placa')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="anio" class="block text-sm font-medium text-foreground-muted mb-2">Año</label>
                                <input type="number" name="anio" id="anio" value="{{ old('anio', $vehiculo->anio) }}" min="1900" max="{{ date('Y') + 1 }}" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('anio') !border-danger @enderror">
                                @error('anio')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="capacidad" class="block text-sm font-medium text-foreground-muted mb-2">Capacidad</label>
                                <input type="text" name="capacidad" id="capacidad" value="{{ old('capacidad', $vehiculo->capacidad) }}" 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('capacidad') !border-danger @enderror">
                                @error('capacidad')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="estado" class="block text-sm font-medium text-foreground-muted mb-2">Estado *</label>
                                <select name="estado" id="estado" required 
                                    class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent cursor-pointer @error('estado') !border-danger @enderror">
                                    <option value="disponible" {{ old('estado', $vehiculo->estado) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="en_uso" {{ old('estado', $vehiculo->estado) === 'en_uso' ? 'selected' : '' }}>En uso</option>
                                    <option value="mantenimiento" {{ old('estado', $vehiculo->estado) === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="inactivo" {{ old('estado', $vehiculo->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="observaciones" class="block text-sm font-medium text-foreground-muted mb-2">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="4" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none @error('observaciones') !border-danger @enderror">{{ old('observaciones', $vehiculo->observaciones) }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column - Photos --}}
                    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-border">
                        <h2 class="text-lg font-semibold text-foreground mb-4">Fotos del Vehiculo</h2>

                        {{-- Current Photos --}}
                        @php
                            $fotosActuales = json_decode($vehiculo->foto, true) ?? [];
                        @endphp
                        @if(count($fotosActuales) > 0)
                        <div class="mb-6">
                            <p class="text-sm text-foreground-muted mb-3">Fotos actuales (marca para eliminar)</p>
                            <div class="grid grid-cols-3 gap-3" id="current-photos">
                                @foreach($fotosActuales as $index => $foto)
                                <div class="aspect-square rounded-xl overflow-hidden relative group" id="photo-{{ $index }}">
                                    <img src="{{ Storage::url($foto) }}" alt="Foto vehiculo" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <label class="px-3 py-1.5 bg-danger text-white text-xs rounded-lg cursor-pointer hover:bg-danger-hover transition-colors">
                                            <input type="checkbox" name="delete_fotos[]" value="{{ $foto }}" class="hidden" onchange="togglePhotoDelete(this, {{ $index }})">
                                            Eliminar
                                        </label>
                                    </div>
                                    <div class="absolute inset-0 bg-danger/50 hidden pointer-events-none flex items-center justify-center" id="delete-overlay-{{ $index }}">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- New Photos Dropzone --}}
                        <p class="text-sm text-foreground-muted mb-3">Agregar nuevas fotos</p>
                        <div class="border-2 border-dashed border-border rounded-xl p-6 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all" id="dropzone" onclick="document.getElementById('fotos').click()">
                            <input type="file" name="fotos[]" id="fotos" multiple accept="image/*" class="hidden" onchange="handleNewFiles(this.files)">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-primary/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <p class="text-foreground text-sm font-medium">Agregar mas fotos</p>
                        </div>

                        <div id="new-preview-grid" class="grid grid-cols-3 gap-3 mt-4"></div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('vehiculos.index') }}" class="px-6 py-3 text-foreground-muted font-medium rounded-xl hover:bg-surface-secondary transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors shadow-lg shadow-primary/20">
                        Actualizar Vehiculo
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

@push('scripts')
<script>
function togglePhotoDelete(checkbox, index) {
    const overlay = document.getElementById(`delete-overlay-${index}`);
    if (checkbox.checked) {
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
    } else {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }
}

const dropzone = document.getElementById('dropzone');
const newPreviewGrid = document.getElementById('new-preview-grid');
let newFiles = [];

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
    handleNewFiles(e.dataTransfer.files);
});

function handleNewFiles(files) {
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
        
        newFiles.push(file);
        const currentIndex = newFiles.length - 1;
        
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'aspect-square rounded-xl overflow-hidden relative group';
            div.dataset.index = currentIndex;
            div.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button type="button" onclick="removeNewFile(${currentIndex}, this)" class="px-3 py-1.5 bg-danger text-white text-xs rounded-lg hover:bg-danger-hover transition-colors">
                        Quitar
                    </button>
                </div>
            `;
            newPreviewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
    
    updateNewFileInput();
}

function removeNewFile(index, btn) {
    newFiles[index] = null;
    btn.closest('.aspect-square').remove();
    updateNewFileInput();
}

function updateNewFileInput() {
    const input = document.getElementById('fotos');
    const dt = new DataTransfer();
    newFiles.filter(f => f !== null).forEach(file => dt.items.add(file));
    input.files = dt.files;
}
</script>
@endpush
@endsection
