<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DisponibilidadController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('vehiculos', VehiculoController::class);
    
    Route::resource('usuarios', UserController::class);
    Route::patch('usuarios/{usuario}/toggle-active', [UserController::class, 'toggleActive'])->name('usuarios.toggle-active');
    
    Route::get('/disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.index');
    Route::post('/disponibilidad', [DisponibilidadController::class, 'store'])->name('disponibilidad.store');
    Route::put('/disponibilidad/{disponibilidad}', [DisponibilidadController::class, 'update'])->name('disponibilidad.update');
    Route::delete('/disponibilidad/{disponibilidad}', [DisponibilidadController::class, 'destroy'])->name('disponibilidad.destroy');
    Route::get('/disponibilidad/eventos', [DisponibilidadController::class, 'getEventos'])->name('disponibilidad.eventos');
});
