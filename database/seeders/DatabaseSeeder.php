<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehiculo;
use App\Models\VehiculoAsignacion;
use App\Models\Envio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
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

        // Create Vehicle Assignments
        $asignacion1 = VehiculoAsignacion::create([
            'vehiculo_id' => $vehiculo1->id,
            'repartidor_id' => $repartidor1->id,
            'asignado_por' => $admin->id,
            'fecha_inicio' => now(),
            'estado' => 'activo',
        ]);

        $asignacion2 = VehiculoAsignacion::create([
            'vehiculo_id' => $vehiculo2->id,
            'repartidor_id' => $repartidor3->id,
            'asignado_por' => $admin->id,
            'fecha_inicio' => now(),
            'estado' => 'activo',
        ]);

        // Create Shipments
        Envio::create([
            'remitente_nombre' => 'Tech Solutions SA',
            'remitente_telefono' => '+52 555 111 2222',
            'remitente_direccion' => '123 Rue de la République, 13002 Marseille, France',
            'destinatario_nombre' => 'John Smith',
            'destinatario_telefono' => '+1 212 555 0123',
            'destinatario_direccion' => '456 Elm Street, New York, NY 10001, USA',
            'descripcion' => 'Equipos electrónicos',
            'peso' => 25.50,
            'tipo_envio' => 'Express Internacional',
            'fecha_estimada' => now()->addDays(3),
            'estado' => 'en_ruta',
            'vehiculo_asignacion_id' => $asignacion2->id,
            'repartidor_id' => $repartidor3->id,
        ]);

        Envio::create([
            'remitente_nombre' => 'Global Imports',
            'remitente_telefono' => '+44 20 7946 0958',
            'remitente_direccion' => 'London, UK',
            'destinatario_nombre' => 'Pavel Novak',
            'destinatario_telefono' => '+420 777 123 456',
            'destinatario_direccion' => 'Prague, Czech Republic',
            'descripcion' => 'Documentos legales',
            'peso' => 2.00,
            'tipo_envio' => 'Express',
            'fecha_estimada' => now()->addDays(1),
            'estado' => 'en_ruta',
            'vehiculo_asignacion_id' => $asignacion1->id,
            'repartidor_id' => $repartidor1->id,
        ]);

        Envio::create([
            'remitente_nombre' => 'Rio Export Co',
            'remitente_telefono' => '+55 21 9999 8888',
            'remitente_direccion' => 'Rio de Janeiro, Brazil',
            'destinatario_nombre' => 'Takeshi Yamamoto',
            'destinatario_telefono' => '+81 3 1234 5678',
            'destinatario_direccion' => 'Tokyo, Japan',
            'descripcion' => 'Artesanías',
            'peso' => 15.00,
            'tipo_envio' => 'Standard Internacional',
            'fecha_estimada' => now()->addDays(7),
            'estado' => 'en_ruta',
            'repartidor_id' => $repartidor2->id,
        ]);

        Envio::create([
            'remitente_nombre' => 'Warsaw Trading',
            'remitente_telefono' => '+48 22 123 4567',
            'remitente_direccion' => 'Warsaw, Poland',
            'destinatario_nombre' => 'James MacLeod',
            'destinatario_telefono' => '+44 131 555 0199',
            'destinatario_direccion' => 'Edinburgh, Scotland',
            'descripcion' => 'Libros y material educativo',
            'peso' => 8.50,
            'tipo_envio' => 'Standard',
            'fecha_estimada' => now()->addDays(4),
            'estado' => 'pendiente',
        ]);

        Envio::create([
            'remitente_nombre' => 'México Distribuidora',
            'remitente_telefono' => '+52 55 1234 5678',
            'remitente_direccion' => 'Ciudad de México, México',
            'destinatario_nombre' => 'Ana López',
            'destinatario_telefono' => '+52 33 9876 5432',
            'destinatario_direccion' => 'Guadalajara, Jalisco, México',
            'descripcion' => 'Ropa y accesorios',
            'peso' => 5.00,
            'tipo_envio' => 'Express Nacional',
            'fecha_estimada' => now()->addDays(2),
            'estado' => 'pendiente',
        ]);

        Envio::create([
            'remitente_nombre' => 'Barcelona Logistics',
            'remitente_telefono' => '+34 93 123 4567',
            'remitente_direccion' => 'Barcelona, Spain',
            'destinatario_nombre' => 'Pierre Dubois',
            'destinatario_telefono' => '+33 1 23 45 67 89',
            'destinatario_direccion' => 'Paris, France',
            'descripcion' => 'Vino y gastronomía',
            'peso' => 20.00,
            'tipo_envio' => 'Refrigerado',
            'fecha_estimada' => now()->addDays(2),
            'estado' => 'entregado',
        ]);
    }
}
