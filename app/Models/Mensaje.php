<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Envio;

class Mensaje extends Model
{
    use HasFactory;

    protected $fillable = ['envio_id', 'mensaje', 'es_repartidor', 'leido'];

    public function envio()
    {
        return $this->belongsTo(Envio::class);
    }
}
