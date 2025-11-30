{{-- usuarios/show --}}
@extends('layouts.app')

@section('title', 'Detalle Usuario')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-7xl mx-auto">
            <livewire:usuario-show :usuario="$usuario" />
        </div>
    </main>
</div>
@endsection