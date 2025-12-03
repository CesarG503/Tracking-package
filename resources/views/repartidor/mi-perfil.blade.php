{{-- resources/views/repartidor/mi-perfil.blade.php --}}
@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar-repartidor')

    {{-- Main Content --}}
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-background">
        <livewire:repartidor.mi-perfil />
    </main>
</div>
@endsection