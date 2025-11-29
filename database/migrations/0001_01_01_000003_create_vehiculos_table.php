<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('marca', 80)->nullable();
            $table->string('modelo', 80)->nullable();
            $table->string('placa', 30)->unique();
            $table->string('foto', 400)->nullable();
            $table->year('anio')->nullable();
            $table->string('capacidad', 80)->nullable();
            $table->enum('estado', ['disponible', 'asignado', 'mantenimiento', 'inactivo'])->default('disponible');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
