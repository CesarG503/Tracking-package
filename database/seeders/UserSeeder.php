<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Envio;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'nombre' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'telefono' => '+52 555 123 4567',
            'rol' => 'admin',
            'activo' => true,
        ]);

        // Create Repartidores
        $repartidor1 = User::create([
            'nombre' => 'Carlos Mendoza',
            'email' => 'carlos@gmail.com',
            'password' => Hash::make('1234'),
            'telefono' => '+52 555 234 5678',
            'rol' => 'repartidor',
            'activo' => true,
            'licencia' => 'LIC-2024-001',
        ]);

        $repartidor2 = User::create([
            'nombre' => 'María García',
            'email' => 'maria@gmail.com',
            'password' => Hash::make('1234'),
            'telefono' => '+52 555 345 6789',
            'rol' => 'repartidor',
            'activo' => true,
            'licencia' => 'LIC-2024-002',
        ]);

        $repartidor3 = User::create([
            'nombre' => 'Miguel Angel',
            'email' => 'miguel@gmail.com',
            'password' => Hash::make('1234'),
            'telefono' => '+52 555 456 7890',
            'rol' => 'repartidor',
            'activo' => true,
            'licencia' => 'LIC-2024-003',
        ]);

        // Create Vehicles
        $vehiculo1 = Vehiculo::create([
            'marca' => 'Ford',
            'modelo' => 'Transit',
            'placa' => 'ABC-123',
            'anio' => 2023,
            'capacidad' => '1500 kg',
            'estado' => 'asignado',
        ]);

        $vehiculo2 = Vehiculo::create([
            'marca' => 'Mercedes',
            'modelo' => 'Sprinter',
            'placa' => 'DEF-456',
            'anio' => 2022,
            'capacidad' => '2000 kg',
            'estado' => 'asignado',
        ]);

        $vehiculo3 = Vehiculo::create([
            'marca' => 'Volkswagen',
            'modelo' => 'Crafter',
            'placa' => 'GHI-789',
            'anio' => 2024,
            'capacidad' => '1800 kg',
            'estado' => 'disponible',
        ]);

        Envio::create([
            'codigo' => 'ENV-CZ7YBHLS',
            'remitente_nombre' => 'Warsaw Trading',
            'remitente_telefono' => '+48 22 123 4567',
            'remitente_direccion' => 'Warsaw, Poland',
            'destinatario_nombre' => 'James MacLeod',
            'destinatario_telefono' => '+44 131 555 0199',
            'destinatario_email' => 'james@correo.com',
            'destinatario_direccion' => 'Edinburgh, Scotland',
            'descripcion' => 'Libros y material educativo',
            'peso' => 8.50,
            'tipo_envio' => 'Standard',
            'fecha_estimada' => now()->addDays(4),
            'estado' => 'pendiente',
        ]);

        Envio::create([
            'codigo' => 'ENV-CZ8YBHLS',
            'remitente_nombre' => 'México Distribuidora',
            'remitente_telefono' => '+52 55 1234 5678',
            'remitente_direccion' => 'Ciudad de México, México',
            'destinatario_nombre' => 'Ana López',
            'destinatario_telefono' => '+52 33 9876 5432',
            'destinatario_email' => 'ana@gmail.com',
            'destinatario_direccion' => 'Guadalajara, Jalisco, México',
            'descripcion' => 'Ropa y accesorios',
            'peso' => 5.00,
            'tipo_envio' => 'Express Nacional',
            'fecha_estimada' => now()->addDays(2),
            'estado' => 'pendiente',
        ]);
    }
}
