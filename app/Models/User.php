<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
        'rol',
        'activo',
        'licencia',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isRepartidor(): bool
    {
        return $this->rol === 'repartidor';
    }

    public function vehiculoAsignaciones(): HasMany
    {
        return $this->hasMany(VehiculoAsignacion::class, 'repartidor_id');
    }

    public function asignacionesRealizadas(): HasMany
    {
        return $this->hasMany(VehiculoAsignacion::class, 'asignado_por');
    }

    public function disponibilidades(): HasMany
    {
        return $this->hasMany(Disponibilidad::class, 'repartidor_id');
    }

    public function envios(): HasMany
    {
        return $this->hasMany(Envio::class, 'repartidor_id');
    }

    public function historialEnvios(): HasMany
    {
        return $this->hasMany(HistorialEnvio::class, 'usuario_id');
    }

    public function logsSistema(): HasMany
    {
        return $this->hasMany(LogSistema::class, 'usuario_id');
    }
}
