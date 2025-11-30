@extends('layouts.app')

@section('title', 'Dashboard Repartidor')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar del Repartidor -->
    @include('partials.sidebar-repartidor')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col-reverse lg:flex-row overflow-hidden">
        <div class="flex h-screen items-center justify-center mx-auto">
            <div class="glass-card p-10 rounded-2xl text-center">
                <h1 class="text-2xl font-bold mb-2">Módulo en construcción</h1>
                <p class="text-foreground-muted">Próximamente habrá un dashboard?</p>
            </div>
        </div>
    
    
    </main>
</div>