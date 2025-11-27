<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained('envios')->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('estado_anterior', ['pendiente', 'en_ruta', 'entregado', 'devuelto', 'cancelado'])->nullable();
            $table->enum('estado_nuevo', ['pendiente', 'en_ruta', 'entregado', 'devuelto', 'cancelado']);
            $table->text('comentario')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->string('evidencia_ruta', 400)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_envios');
    }
};
