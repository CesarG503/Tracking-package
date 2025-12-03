<?php

namespace Database\Seeders;

use App\Models\Disponibilidad;
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

        Disponibilidad::create([
            "vehiculo_id" => $vehiculo1->id,
            "repartidor_id" => $repartidor1->id,
            "fecha_inicio" => now()->setTime(8, 0),
            "fecha_fin" => now()->setTime(18, 0),
            "tipo" => "disponible",
            "descripcion" => "Jornada laboral completa",
        ]);

        Disponibilidad::create([
            "vehiculo_id" => $vehiculo2->id,
            "repartidor_id" => $repartidor2->id,
            "fecha_inicio" => now()->setTime(8, 0),
            "fecha_fin" => now()->setTime(18, 0),
            "tipo" => "disponible",
            "descripcion" => "Jornada laboral completa",
        ]);

        Disponibilidad::create([
            "vehiculo_id" => $vehiculo2->id, // Asumiendo que comparte o usa otro, pero por ahora uso vehiculo2 o null si no es obligatorio
            "repartidor_id" => $repartidor3->id,
            "fecha_inicio" => now()->setTime(8, 0),
            "fecha_fin" => now()->setTime(18, 0),
            "tipo" => "disponible",
            "descripcion" => "Jornada laboral completa",
        ]);

        // Create Shipments
        Envio::create([
            'codigo' => 'ENV-CZ4YBHLS',
            'remitente_nombre' => 'Tech Solutions SA',
            'remitente_telefono' => '+503 2661 1111',
            'remitente_direccion' => 'Centro Comercial Metrocentro, San Miguel',
            'destinatario_nombre' => 'John Smith',
            'destinatario_telefono' => '+503 7777 8888',
            'destinatario_email' => 'test@mail.com',
            'destinatario_direccion' => 'Col. San Francisco, San Miguel',
            'descripcion' => 'Equipos electrónicos',
            'peso' => 25.50,
            'tipo_envio' => 'Express',
            'fecha_estimada' => now()->addDays(3),
            'estado' => 'en_ruta',
            'vehiculo_asignacion_id' => $asignacion2->id,
            'repartidor_id' => $repartidor3->id,
            'lat' => 13.4812,
            'lng' => -88.1795,
        ]);

        Envio::create([
            'codigo' => 'ENV-CZ5YBHLS',
            'remitente_nombre' => 'Global Imports',
            'remitente_telefono' => '+503 2661 2233',
            'remitente_direccion' => 'Barrio El Centro, San Miguel',
            'destinatario_nombre' => 'Pavel Novak',
            'destinatario_telefono' => '+503 7111 2222',
            'destinatario_email' => 'alguien@gmail.com',
            'destinatario_direccion' => 'Col. Jardín, San Miguel',
            'descripcion' => 'Documentos legales',
            'peso' => 2.00,
            'tipo_envio' => 'Express',
            'fecha_estimada' => now()->addDays(1),
            'estado' => 'en_ruta',
            'vehiculo_asignacion_id' => $asignacion1->id,
            'repartidor_id' => $repartidor1->id,
            'lat' => 13.4845,
            'lng' => -88.1821,
        ]);

        Envio::create([
            'codigo' => 'ENV-CZ6YBHLS',
            'remitente_nombre' => 'Rio Export Co',
            'remitente_telefono' => '+503 2661 3344',
            'remitente_direccion' => 'Col. Belén, San Miguel',
            'destinatario_nombre' => 'Takeshi Yamamoto',
            'destinatario_telefono' => '+503 7333 4444',
            'destinatario_email' => 'takeshi@mail.com',
            'destinatario_direccion' => 'Ciudad Barrios, San Miguel',
            'descripcion' => 'Artesanías',
            'peso' => 15.00,
            'tipo_envio' => 'Standard',
            'fecha_estimada' => now()->addDays(7),
            'estado' => 'en_ruta',
            'lat' => 13.7667, 
            'lng' => -88.2667,
        ]);

        Envio::create([
            'codigo' => 'ENV-CZ7YBHLS',
            'remitente_nombre' => 'Warsaw Trading',
            'remitente_telefono' => '+503 2661 4455',
            'remitente_direccion' => 'Av. Roosevelt Norte, San Miguel',
            'destinatario_nombre' => 'James MacLeod',
            'destinatario_telefono' => '+503 7555 6666',
            'destinatario_email' => 'james@correo.com',
            'destinatario_direccion' => 'Barrio La Merced, San Miguel',
            'descripcion' => 'Libros y material educativo',
            'peso' => 8.50,
            'tipo_envio' => 'Standard',
            'fecha_estimada' => now()->addDays(4),
            'estado' => 'pendiente',
            'lat' => 13.4801, 
            'lng' => -88.1756,
        ]);

        Envio::create([
            'codigo' => 'ENV-CZ8YBHLS',
            'remitente_nombre' => 'México Distribuidora',
            'remitente_telefono' => '+503 2661 5566',
            'remitente_direccion' => 'Col. Mónaco, San Miguel',
            'destinatario_nombre' => 'Ana López',
            'destinatario_telefono' => '+503 7777 8899',
            'destinatario_email' => 'ana@gmail.com',
            'destinatario_direccion' => 'Col. Las Margaritas, San Miguel',
            'descripcion' => 'Ropa y accesorios',
            'peso' => 5.00,
            'tipo_envio' => 'Express',
            'fecha_estimada' => now()->addDays(2),
            'estado' => 'pendiente',
            'lat' => 13.4768, 
            'lng' => -88.1889,
        ]);

        Envio::create([
            'codigo' => 'ENV-C11YBHLS',
            'remitente_nombre' => 'Barcelona Logistics',
            'remitente_telefono' => '+503 2661 6677',
            'remitente_direccion' => 'Parque Guzmán, San Miguel',
            'destinatario_nombre' => 'Pierre Dubois',
            'destinatario_telefono' => '+503 7999 0000',
            'destinatario_email' => 'pierre@mail.com',
            'destinatario_direccion' => 'Col. Santa Lucía, San Miguel',
            'descripcion' => 'Vino y gastronomía',
            'peso' => 20.00,
            'tipo_envio' => 'Refrigerado',
            'fecha_estimada' => now()->addDays(2),
            'estado' => 'entregado',
            'repartidor_id' => $repartidor2->id,
            'lat' => 13.4889, 
            'lng' => -88.1712,
        ]);


        Envio::create([
            'codigo' => 'ENV-D12YBHLS',
            'remitente_nombre' => 'Farmacia San Rafael',
            'remitente_telefono' => '+503 2661 7788',
            'remitente_direccion' => 'Av. José Simeón Cañas, San Miguel',
            'destinatario_nombre' => 'María González',
            'destinatario_telefono' => '+503 7222 3333',
            'destinatario_email' => 'maria.g@correo.com',
            'destinatario_direccion' => 'Col. Moderna, San Miguel',
            'descripcion' => 'Medicamentos y productos farmacéuticos',
            'peso' => 3.25,
            'tipo_envio' => 'Express',
            'fecha_estimada' => now()->addDays(1),
            'estado' => 'en_ruta',
            'vehiculo_asignacion_id' => $asignacion1->id,
            'repartidor_id' => $repartidor1->id,
            'lat' => 13.4823, 
            'lng' => -88.1843,
        ]);

        Envio::create([
            'codigo' => 'ENV-E13YBHLS',
            'remitente_nombre' => 'Librería El Estudiante',
            'remitente_telefono' => '+503 2661 8899',
            'remitente_direccion' => 'Barrio San Felipe, San Miguel',
            'destinatario_nombre' => 'Roberto Martínez',
            'destinatario_telefono' => '+503 7444 5555',
            'destinatario_email' => 'roberto@mail.com',
            'destinatario_direccion' => 'Residencial Los Álamos, San Miguel',
            'descripcion' => 'Material escolar y libros',
            'peso' => 12.00,
            'tipo_envio' => 'Standard',
            'fecha_estimada' => now()->addDays(5),
            'estado' => 'pendiente',
            'lat' => 13.4791, // Residencial Los Álamos
            'lng' => -88.1698,
        ]);

        Envio::create([
            'codigo' => 'ENV-F14YBHLS',
            'remitente_nombre' => 'Electrodomésticos La Casa',
            'remitente_telefono' => '+503 2661 9900',
            'remitente_direccion' => 'Paseo General Gerardo Barrios, San Miguel',
            'destinatario_nombre' => 'Carmen Rivas',
            'destinatario_telefono' => '+503 7666 7777',
            'destinatario_email' => 'carmen.r@gmail.com',
            'destinatario_direccion' => 'Col. Bellavista, San Miguel',
            'descripcion' => 'Electrodomésticos',
            'peso' => 35.00,
            'tipo_envio' => 'Carga Pesada',
            'fecha_estimada' => now()->addDays(3),
            'estado' => 'en_ruta',
            'vehiculo_asignacion_id' => $asignacion2->id,
            'repartidor_id' => $repartidor3->id,
            'lat' => 13.4856, 
            'lng' => -88.1779,
        ]);

        Envio::create([
            'codigo' => 'ENV-G15YBHLS',
            'remitente_nombre' => 'Joyería El Diamante',
            'remitente_telefono' => '+503 2661 0011',
            'remitente_direccion' => 'Centro Comercial Plaza Chaparrastique, San Miguel',
            'destinatario_nombre' => 'Luis Hernández',
            'destinatario_telefono' => '+503 7888 9999',
            'destinatario_email' => 'luis.h@correo.com',
            'destinatario_direccion' => 'Moncagua, San Miguel',
            'descripcion' => 'Joyería fina - Envío asegurado',
            'peso' => 0.50,
            'tipo_envio' => 'Express Seguro',
            'fecha_estimada' => now()->addDays(1),
            'estado' => 'pendiente',
            'lat' => 13.4987, 
            'lng' => -88.1623,
        ]);

        Envio::create([
            'codigo' => 'ENV-H16YBHLS',
            'remitente_nombre' => 'Panadería y Pastelería Dulce Amor',
            'remitente_telefono' => '+503 2661 1122',
            'remitente_direccion' => 'Barrio Concepción, San Miguel',
            'destinatario_nombre' => 'Sandra Flores',
            'destinatario_telefono' => '+503 7000 1111',
            'destinatario_email' => 'sandra.f@mail.com',
            'destinatario_direccion' => 'Urbanización San José, San Miguel',
            'descripcion' => 'Pasteles y postres - Entrega temprana',
            'peso' => 6.50,
            'tipo_envio' => 'Express Refrigerado',
            'fecha_estimada' => now()->addHours(6),
            'estado' => 'entregado',
            'repartidor_id' => $repartidor1->id,
            'lat' => 13.4734,
            'lng' => -88.1867,
        ]);
    }
}
