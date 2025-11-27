@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-foreground">Usuarios</h1>
                    <p class="text-foreground-muted text-sm mt-1">Gestiona los usuarios del sistema</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-foreground-muted">{{ $usuarios->total() }} usuarios</span>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-surface rounded-2xl p-4 mb-6 shadow-sm border border-border">
                <form method="GET" id="user-filter-form" class="flex items-center gap-4 flex-wrap">
                    <div class="flex-1 min-w-[200px] relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" id="user-search-input" value="{{ request('search') }}" placeholder="Buscar por nombre, email..." 
                            class="w-full pl-12 pr-4 py-3 bg-surface-secondary border border-border rounded-xl text-foreground placeholder:text-foreground-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <select name="rol" id="rol-filter" class="px-4 py-3 bg-surface-secondary border border-border rounded-xl min-w-[150px] text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                        <option value="">Todos los roles</option>
                        <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="repartidor" {{ request('rol') === 'repartidor' ? 'selected' : '' }}>Repartidor</option>
                    </select>

                    <select name="activo" id="activo-filter" class="px-4 py-3 bg-surface-secondary border border-border rounded-xl min-w-[140px] text-foreground focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>

                    <button type="submit" class="p-3 bg-surface-secondary border border-border rounded-xl text-foreground-muted hover:text-primary hover:border-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </button>

                    <a href="{{ route('usuarios.create') }}" class="px-6 py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium flex items-center gap-2 transition-colors shadow-lg shadow-primary/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nuevo Usuario
                    </a>
                </form>
            </div>

            {{-- Users Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($usuarios as $usuario)
                <div class="bg-surface rounded-2xl p-5 shadow-sm border border-border hover:shadow-md hover:border-primary/30 transition-all group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center shadow-lg shadow-primary/20">
                                <span class="text-white font-bold text-lg">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">{{ $usuario->nombre }}</h3>
                                <p class="text-sm text-foreground-muted">{{ $usuario->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('usuarios.show', $usuario) }}" class="p-1.5 rounded-lg text-foreground-muted hover:text-primary hover:bg-primary/10 transition-colors" title="Ver">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="p-1.5 rounded-lg text-foreground-muted hover:text-warning hover:bg-warning/10 transition-colors" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @if(auth()->id() !== $usuario->id)
                            <button type="button" onclick="confirmDeleteUser({{ $usuario->id }}, '{{ $usuario->nombre }}')" class="p-1.5 rounded-lg text-foreground-muted hover:text-danger hover:bg-danger/10 transition-colors" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            <form id="delete-user-form-{{ $usuario->id }}" action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-foreground-muted">Rol</span>
                            @if($usuario->rol === 'admin')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                                </svg>
                                Admin
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-cyan-100 text-cyan-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                Repartidor
                            </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-foreground-muted">Telefono</span>
                            <span class="text-foreground">{{ $usuario->telefono ?? '-' }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-foreground-muted">Licencia</span>
                            <span class="font-mono text-foreground text-xs bg-surface-secondary px-2 py-0.5 rounded">{{ $usuario->licencia ?? '-' }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm pt-2 border-t border-border">
                            <span class="text-foreground-muted">Estado</span>
                            <form action="{{ route('usuarios.toggle-active', $usuario) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors {{ $usuario->activo ? 'bg-success-light text-success hover:bg-success/20' : 'bg-surface-secondary text-foreground-muted hover:bg-border' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $usuario->activo ? 'bg-success' : 'bg-danger' }}"></span>
                                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-border flex items-center justify-between text-xs text-foreground-muted">
                        <span>Desde {{ $usuario->created_at->format('M Y') }}</span>
                        <a href="{{ route('usuarios.show', $usuario) }}" class="text-primary hover:underline font-medium">Ver perfil</a>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="bg-surface rounded-2xl p-12 text-center border border-border">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-surface-secondary flex items-center justify-center">
                            <svg class="w-10 h-10 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <p class="text-foreground-muted mb-4">No hay usuarios registrados</p>
                        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Agregar usuario
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            @if($usuarios->hasPages())
            <div class="mt-6">
                {{ $usuarios->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

@push('scripts')
<script>
// Confirm delete user with SweetAlert2
function confirmDeleteUser(id, nombre) {
    Swal.fire({
        title: 'Eliminar Usuario',
        html: `<p class="text-gray-600">Estas seguro que deseas eliminar a <strong>${nombre}</strong>?</p><p class="text-sm text-gray-500 mt-2">Esta accion no se puede deshacer.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-user-form-${id}`).submit();
        }
    });
}

// Real-time search
let userSearchTimeout;
document.getElementById('user-search-input').addEventListener('input', function(e) {
    clearTimeout(userSearchTimeout);
    userSearchTimeout = setTimeout(() => {
        document.getElementById('user-filter-form').submit();
    }, 500);
});

// Auto-submit on filter change
document.getElementById('rol-filter').addEventListener('change', function() {
    document.getElementById('user-filter-form').submit();
});

document.getElementById('activo-filter').addEventListener('change', function() {
    document.getElementById('user-filter-form').submit();
});
</script>
@endpush
@endsection
