{{-- Sidebar Component para Repartidor --}}
<aside class="w-20 bg-surface dark:glass-sidebar-dark flex flex-col items-center py-6 gap-2 border-r border-border dark:border-border transition-colors duration-300">
    {{-- Logo --}}
    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-cyan-500/30 dark:shadow-cyan-500/40">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
    </div>

    {{-- Nav Items --}}
    <nav class="flex-1 flex flex-col gap-2">
        {{-- Dashboard --}}
        <a href="#" 
           class="w-12 h-12 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors relative group" 
           title="Dashboard">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        
        {{-- Mis Envíos (Activos) --}}
        <a href="#" 
           class="w-12 h-12 rounded-xl {{ request()->routeIs('repartidor.envios') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors relative group" 
           title="Mis Envíos">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            {{-- Badge con contador --}}
            @php
                $enviosPendientes = auth()->user()->envios()->whereIn('estado', ['en_ruta'])->count();
            @endphp
            @if($enviosPendientes > 0)
            <span class="glass glass-red !absolute -top-1 -right-1 w-5 h-5 bg-danger text-white text-xs rounded-full flex items-center justify-center font-bold">{{ $enviosPendientes }}</span>
            @endif
        </a>

        {{-- Rutas del Día --}}
        <a href="#" 
           class="w-12 h-12 rounded-xl {{ request()->routeIs('repartidor.rutas') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors relative group" 
           title="Rutas del Día">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
        </a>

        {{-- Mi Vehículo --}}
        <a href="#" 
           class="w-12 h-12 rounded-xl {{ request()->routeIs('repartidor.vehiculo') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors relative group" 
           title="Mi Vehículo">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </a>

        {{-- Historial --}}
        <a href="#" 
           class="w-12 h-12 rounded-xl {{ request()->routeIs('repartidor.historial') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors relative group" 
           title="Historial">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </a>

        {{-- Disponibilidad --}}
        <a href="#" 
           class="w-12 h-12 rounded-xl {{ request()->routeIs('repartidor.disponibilidad') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors relative group" 
           title="Disponibilidad">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </a>

        {{-- Mi Perfil --}}
        <a href="#" 
           class="w-12 h-12 rounded-xl {{ request()->routeIs('repartidor.perfil') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary' : 'text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground' }} flex items-center justify-center transition-colors relative group" 
           title="Mi Perfil">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </a>
    </nav>

    {{-- Bottom Nav --}}
    <div class="flex flex-col gap-2">
        {{-- Notificaciones --}}
        <button type="button" 
                class="w-12 h-12 rounded-xl text-foreground-muted hover:bg-surface-secondary dark:hover:bg-surface-secondary hover:text-foreground dark:hover:text-foreground flex items-center justify-center transition-colors relative group" 
                title="Notificaciones">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            {{-- Badge de notificaciones --}}
            <span class="glass glass-amber !absolute -top-1 -right-1 w-5 h-5 bg-warning text-white text-xs rounded-full flex items-center justify-center font-bold">3</span>
        </button>

        {{-- Theme Toggle --}}
        @include('components.theme-toggle')
        
        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" id="sidebar-logout-form">
            @csrf
            <button type="button" onclick="confirmSidebarLogout()" class="w-12 h-12 rounded-xl text-foreground-muted flex items-center justify-center hover:bg-danger-light dark:hover:bg-danger-light hover:text-danger dark:hover:text-danger transition-colors relative group" title="Cerrar Sesión">
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
        title: '¿Cerrar Sesión?',
        text: '¿Estás seguro que deseas cerrar sesión?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, cerrar sesión',
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