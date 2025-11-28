<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            
            // Remitente
            $table->string('remitente_nombre', 120);
            $table->string('remitente_telefono', 30)->nullable();
            $table->text('remitente_direccion');
            
            // Destinatario
            $table->string('destinatario_nombre', 120);
            $table->string('destinatario_telefono', 30)->nullable();
            $table->string('destinatario_email', 200)->isNotEmpty();
            $table->text('destinatario_direccion');
            
            // Paquete
            $table->text('descripcion')->nullable();
            $table->string('foto_paquete', 400)->nullable();
            $table->decimal('peso', 10, 2)->nullable();
            $table->string('tipo_envio', 80)->nullable();
            
            // Fechas
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->date('fecha_estimada')->nullable();
            
            // Estado
            $table->enum('estado', ['pendiente', 'en_ruta', 'entregado', 'devuelto', 'cancelado'])->default('pendiente');
            
            // Relaciones
            $table->foreignId('vehiculo_asignacion_id')->nullable()->constrained('vehiculo_asignaciones')->onDelete('set null');
            $table->foreignId('repartidor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('disponibilidad_evento_id')->nullable()->constrained('disponibilidad')->onDelete('set null');
            
            // Datos de entrega
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->text('direccion_entrega')->nullable();
            $table->string('foto_entrega', 400)->nullable();
            
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
};
