<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TrackFlow') - Sistema de Envíos</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    {{-- Added SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/glass-effects.css', 'resources/js/glass-effects.js', 'resources/js/app.js'])
    @endif

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    {{-- Theme Detection Script - Runs before page render to prevent flash --}}
    <script>
        (function() {
            'use strict';
            
            // Function to apply theme immediately
            function applyTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
            
            // Check for saved theme preference
            const savedTheme = localStorage.getItem('theme');
            
            if (savedTheme) {
                // Use saved preference
                applyTheme(savedTheme);
            } else {
                // Check system preference
                const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                applyTheme(systemPrefersDark ? 'dark' : 'light');
            }
        })();
    </script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-background from-blue-50 via-sky-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen transition-colors duration-300">
    @yield('content')
    
    {{-- Added global scripts stack --}}
    @stack('scripts')
    
    {{-- Flash message handling with SweetAlert2 --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Exito',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {  
                popup: 'rounded-xl shadow-lg'
            }
            
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-lg'
            }
        });
    </script>
    @endif
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
</body>
</html>
