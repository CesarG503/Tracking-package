{{-- Sidebar Component --}}
<aside class="w-20 bg-surface dark:glass-sidebar-dark flex flex-col items-center py-6 gap-2 border-r border-border dark:border-border transition-colors duration-300">
    {{-- Logo --}}
    <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-hover rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-primary/30 dark:shadow-primary/40">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
    </div>

    {{-- Nav Items --}}
    <nav class="flex-1 flex flex-col gap-2">
        <a href="{{ route('dashboard') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors" title="Dashboard">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
        </a>
        <a href="{{ route('disponibilidad.index') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('disponibilidad.*') ? 'bg-primary/10 text-primary' : 'text-foreground-muted hover:bg-surface-secondary hover:text-foreground' }} flex items-center justify-center transition-colors" title="Disponibilidad">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </a>
        <a href="{{ route('vehiculos.index') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('vehiculos.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors" title="Vehiculos">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </a>
        <a href="{{ route('usuarios.index') }}" class="w-12 h-12 rounded-xl {{ request()->routeIs('usuarios.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors" title="Usuarios">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </a>
    </nav>

    {{-- Bottom Nav --}}
    <div class="flex flex-col gap-2">
        {{-- Theme Toggle --}}
        @include('components.theme-toggle')
        
        <form action="{{ route('logout') }}" method="POST" id="sidebar-logout-form">
            @csrf
            <button type="button" onclick="confirmSidebarLogout()" class="w-12 h-12 rounded-xl text-foreground-muted flex items-center justify-center hover:bg-danger-light dark:hover:bg-danger-light hover:text-danger dark:hover:text-danger transition-colors" title="Cerrar Sesion">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </div>
</aside>

<script>
function confirmSidebarLogout() {
    Swal.fire({
        title: 'Cerrar Sesion',
        text: 'Estas seguro que deseas cerrar sesion?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Si, cerrar sesion',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('sidebar-logout-form').submit();
        }
    });
}
</script>
