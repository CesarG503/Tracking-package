@extends('layouts.app')

@section('title', 'Detalle Vehiculo')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('vehiculos.index') }}" class="w-10 h-10 rounded-xl bg-surface-secondary border border-border flex items-center justify-center text-foreground-muted hover:text-primary hover:border-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-foreground">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h1>
                        <p class="text-foreground-muted mt-1">Placa: <span class="font-mono bg-surface-secondary px-2 py-0.5 rounded text-foreground">{{ $vehiculo->placa }}</span></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="px-4 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
