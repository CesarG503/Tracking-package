<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Envio extends Model
{
    use HasFactory;

    protected $table = 'envios';

    protected $fillable = [
        'remitente_nombre',
        'remitente_telefono',
        'remitente_direccion',
        'destinatario_nombre',
        'destinatario_telefono',
        'destinatario_email',
        'destinatario_direccion',
        'descripcion',
        'foto_paquete',
        'peso',
        'tipo_envio',
        'fecha_creacion',
        'fecha_estimada',
        'estado',
        'vehiculo_asignacion_id',
        'repartidor_id',
        'disponibilidad_evento_id',
        'lat',
        'lng',
        'direccion_entrega',
        'foto_entrega',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
            'fecha_estimada' => 'date',
            'peso' => 'decimal:2',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    public function vehiculoAsignacion(): BelongsTo
    {
        return $this->belongsTo(VehiculoAsignacion::class, 'vehiculo_asignacion_id');
    }

    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'repartidor_id');
    }

    public function disponibilidadEvento(): BelongsTo
    {
        return $this->belongsTo(Disponibilidad::class, 'disponibilidad_evento_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialEnvio::class, 'envio_id');
    }

    public function getCodigoAttribute(): string
    {
        return str_pad($this->id, 5, '0', STR_PAD_LEFT) . '-' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
    }
}
