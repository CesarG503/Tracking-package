<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\EnvioController;
use App\Livewire\Counter;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/livewire-test', Counter::class);
Route::get('/tracking/{codigo}', App\Livewire\TrackingEnvio::class)->name('tracking');
// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (admin)
Route::middleware(['auth','admin'])->group(function () {
    Route::resource('vehiculos', VehiculoController::class);
    Route::resource('envios', EnvioController::class);
    Route::get('/envios/resources/available', [EnvioController::class, 'getAvailableResources'])->name('envios.available-resources');
    
    Route::resource('usuarios', UserController::class);
    Route::patch('usuarios/{usuario}/toggle-active', [UserController::class, 'toggleActive'])->name('usuarios.toggle-active');
    
    Route::get('/disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.index');
    Route::post('/disponibilidad', [DisponibilidadController::class, 'store'])->name('disponibilidad.store');
    Route::put('/disponibilidad/{disponibilidad}', [DisponibilidadController::class, 'update'])->name('disponibilidad.update');
    Route::delete('/disponibilidad/{disponibilidad}', [DisponibilidadController::class, 'destroy'])->name('disponibilidad.destroy');
    Route::get('/disponibilidad/eventos', [DisponibilidadController::class, 'getEventos'])->name('disponibilidad.eventos');
});

// Protected routes (requiere autenticación)
Route::middleware(['auth'])->group(function () {
    // Dashboard único que redirige según el rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Protected routes (repartidor) 
// TODO: será mejor crear un controlador apartir del modelo Envio? 
Route::middleware(['auth', 'repartidor'])->group(function () {
    Route::get('/mis-envios', function() {
        return view('repartidor.mis-envios');
    })->name('mis-envios');
    
    Route::get('/calendario', function() {
        return view('repartidor.calendario');
    })->name('repartidor.calendario');

});

