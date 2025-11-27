<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'marca',
        'modelo',
        'placa',
        'foto',
        'anio',
        'capacidad',
        'estado',
        'observaciones',
    ];

    public function asignaciones(): HasMany
    {
        return $this->hasMany(VehiculoAsignacion::class, 'vehiculo_id');
    }

    public function disponibilidades(): HasMany
    {
        return $this->hasMany(Disponibilidad::class, 'vehiculo_id');
    }

    public function asignacionActiva()
    {
        return $this->asignaciones()->where('estado', 'activo')->first();
    }
}
