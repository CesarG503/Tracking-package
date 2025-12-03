@extends('layouts.app')

@section('title', 'Mi Calendarización')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar-repartidor')

    {{-- Main Content --}}
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-background">
        <livewire:repartidor.calendario />
    </main>
</div>
@endsection
