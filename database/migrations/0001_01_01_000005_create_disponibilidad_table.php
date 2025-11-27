<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disponibilidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repartidor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehiculo_id')->nullable()->constrained('vehiculos')->onDelete('set null');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->enum('tipo', ['disponible', 'ocupado', 'vacaciones', 'bloqueo', 'asignado_envio'])->default('disponible');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('origen_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilidad');
    }
};
