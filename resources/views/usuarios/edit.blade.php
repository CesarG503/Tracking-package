@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-2xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('usuarios.index') }}" class="w-10 h-10 rounded-xl bg-surface-secondary border border-border flex items-center justify-center text-foreground-muted hover:text-primary hover:border-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center shadow-lg shadow-primary/20">
                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-foreground">Editar Usuario</h1>
                        <p class="text-foreground-muted mt-1">{{ $usuario->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('usuarios.update', $usuario) }}" method="POST" class="bg-surface rounded-2xl p-6 shadow-sm border border-border space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Nombre --}}
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-sm font-medium text-foreground-muted mb-2">Nombre Completo *</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}" required 
                            class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('nombre') !border-danger @enderror">
                        @error('nombre')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-foreground-muted mb-2">Correo Electronico *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" required 
                            class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('email') !border-danger @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telefono --}}
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-foreground-muted mb-2">Telefono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $usuario->telefono) }}" 
                            class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('telefono') !border-danger @enderror">
                        @error('telefono')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-foreground-muted mb-2">Nueva Contraseña</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password') !border-danger @enderror" 
                                placeholder="Dejar vacio para mantener">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-foreground-muted hover:text-foreground">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-foreground-muted mb-2">Confirmar Contraseña</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-foreground-muted hover:text-foreground">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Rol --}}
                    <div>
                        <label for="rol" class="block text-sm font-medium text-foreground-muted mb-2">Rol *</label>
                        <select name="rol" id="rol" required 
                            class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent cursor-pointer @error('rol') !border-danger @enderror">
                            <option value="repartidor" {{ old('rol', $usuario->rol) === 'repartidor' ? 'selected' : '' }}>Repartidor</option>
                            <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @error('rol')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Licencia --}}
                    <div>
                        <label for="licencia" class="block text-sm font-medium text-foreground-muted mb-2">Numero de Licencia</label>
                        <input type="text" name="licencia" id="licencia" value="{{ old('licencia', $usuario->licencia) }}" 
                            class="w-full px-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground font-mono focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('licencia') !border-danger @enderror">
                        @error('licencia')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Activo --}}
                <div class="flex items-center gap-3 p-4 bg-surface-secondary rounded-xl">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $usuario->activo) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-border peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:bg-primary"></div>
                    </label>
                    <div>
                        <label for="activo" class="text-sm font-medium text-foreground cursor-pointer">Usuario activo</label>
                        <p class="text-xs text-foreground-muted">Los usuarios inactivos no pueden iniciar sesion</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('usuarios.index') }}" class="px-6 py-3 text-foreground-muted font-medium rounded-xl hover:bg-surface-secondary transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium transition-colors shadow-lg shadow-primary/20">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
@endsection
