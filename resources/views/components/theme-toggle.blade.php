{{-- Theme Toggle Component --}}
<button 
    data-theme-toggle
    class="w-12 h-12 rounded-xl text-foreground-muted hover:text-foreground dark:hover:text-foreground hover:bg-surface-secondary dark:hover:bg-surface-secondary transition-all duration-200 flex items-center justify-center group relative overflow-hidden"
    title="Cambiar tema"
    aria-label="Cambiar tema"
>
    {{-- Background hover effect --}}
    <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-xl"></div>
    
    {{-- Sun Icon (shown in dark mode) --}}
    <svg class="w-6 h-6 sun-icon relative z-10 transform group-hover:scale-110 transition-transform duration-200" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    
    {{-- Moon Icon (shown in light mode) --}}
    <svg class="w-6 h-6 moon-icon relative z-10 transform group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
</button>