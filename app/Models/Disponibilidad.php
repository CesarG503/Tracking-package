<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disponibilidad extends Model
{
    use HasFactory;

    protected $table = 'disponibilidad';

    protected $fillable = [
        'repartidor_id',
        'vehiculo_id',
        'fecha_inicio',
        'fecha_fin',
        'tipo',
        'descripcion',
        'origen_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }

    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'repartidor_id');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function envios(): HasMany
    {
        return $this->hasMany(Envio::class, 'disponibilidad_evento_id');
    }
}
