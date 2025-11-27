<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialEnvio extends Model
{
    use HasFactory;

    protected $table = 'historial_envios';

    public $timestamps = false;

    protected $fillable = [
        'envio_id',
        'usuario_id',
        'estado_anterior',
        'estado_nuevo',
        'comentario',
        'fecha',
        'evidencia_ruta',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
