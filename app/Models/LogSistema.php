<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogSistema extends Model
{
    use HasFactory;

    protected $table = 'logs_sistema';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'accion',
        'detalle',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'detalle' => 'array',
            'fecha' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
