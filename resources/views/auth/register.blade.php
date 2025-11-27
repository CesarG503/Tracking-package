@extends('layout')

@section('title', 'Registro')

@section('content')
<div class="flex min-h-[80vh] items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8 bg-surface p-8 rounded-lg shadow-lg border border-border">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-foreground">
                Crear Cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-foreground-muted">
                ¿Ya tienes una cuenta?
                <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary/90">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-4 rounded-md shadow-sm">
                <div>
                    <label for="name" class="block text-sm font-medium text-foreground mb-1">Nombre Completo</label>
                    <input id="name" name="name" type="text" autocomplete="name" required class="relative block w-full rounded-md border-0 py-1.5 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 px-3" placeholder="Juan Pérez">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-foreground mb-1">Correo Electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="relative block w-full rounded-md border-0 py-1.5 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 px-3" placeholder="correo@ejemplo.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-foreground mb-1">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="relative block w-full rounded-md border-0 py-1.5 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 px-3" placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-1">Confirmar Contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="relative block w-full rounded-md border-0 py-1.5 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 px-3" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                    Registrarse
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
