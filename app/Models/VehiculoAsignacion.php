<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehiculoAsignacion extends Model
{
    use HasFactory;

    protected $table = 'vehiculo_asignaciones';

    protected $fillable = [
        'vehiculo_id',
        'repartidor_id',
        'asignado_por',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'repartidor_id');
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    public function envios(): HasMany
    {
        return $this->hasMany(Envio::class, 'vehiculo_asignacion_id');
    }
}
