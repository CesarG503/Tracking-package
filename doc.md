# Documentación Técnica: Sistema de Rastreo QR y Chat en Tiempo Real

Este documento detalla la implementación técnica del sistema de seguimiento de envíos mediante códigos QR, incluyendo mapas interactivos y un chat en tiempo real entre el cliente y el sistema.

## 1. Dependencias Instaladas

Para el funcionamiento de este módulo se han integrado las siguientes librerías:

### Backend (Composer)
-   **`simplesoftwareio/simple-qrcode`**: Utilizada para generar códigos QR dinámicos que enlazan a la página pública de seguimiento.
    -   *Comando de instalación*: `composer require simplesoftwareio/simple-qrcode`

### Frontend (CDN/NPM)
-   **`Leaflet.js`**: Biblioteca JavaScript para mapas interactivos (OpenStreetMap).
    -   *Uso*: Visualización de la ubicación de entrega en el formulario de creación y en la página de seguimiento.
-   **`SweetAlert2`**: Para alertas y confirmaciones visuales.

---

## 2. Arquitectura del Backend

### Modelos de Base de Datos

#### `Mensaje` (Nuevo)
Modelo creado para almacenar el historial del chat.
-   **Tabla**: `mensajes`
-   **Campos**:
    -   `envio_id`: Clave foránea vinculada al envío.
    -   `mensaje`: Contenido del texto.
    -   `es_repartidor`: Booleano (`false` = Cliente, `true` = Repartidor/Admin).
-   **Relación**: Pertenece a un `Envio`.

#### `Envio` (Modificado)
Se ha actualizado el modelo existente para incluir la relación con los mensajes.
-   **Relación**: `hasMany(Mensaje::class)` -> Un envío tiene múltiples mensajes.
-   **Campos**: Se aseguraron los campos `lat` y `lng` en `$fillable` para guardar coordenadas.

### Controladores y Componentes

#### `App\Livewire\TrackingEnvio` (Nuevo Componente)
Este es el núcleo de la lógica del seguimiento público.
-   **Función**: Gestiona la vista pública de rastreo.
-   **Lógica**:
    -   `mount($codigo)`: Busca el envío por su código único.
    -   `sendMessage()`: Valida y guarda mensajes enviados por el cliente.
    -   `render()`: Retorna la vista y refresca los mensajes (Polling).
-   **Polling**: Utiliza `wire:poll` para actualizar el chat automáticamente cada 3 segundos sin recargar la página.

#### `App\Http\Controllers\EnvioController` (Modificado)
-   **Validación**: Se agregaron reglas para `lat` y `lng`.
-   **Redirección**: Al crear un envío, ahora redirige a la vista de detalles (`show`) para mostrar inmediatamente el código QR generado.

---

## 3. Arquitectura del Frontend

### Vistas y Layouts

#### `resources/views/layouts/guest.blade.php` (Nuevo)
Layout minimalista destinado a las vistas públicas (como el tracking) que no requieren autenticación ni barra lateral de administración.

#### `resources/views/livewire/tracking-envio.blade.php` (Nuevo)
La interfaz principal para el cliente final.
-   **Mapa**: Muestra la ubicación de entrega usando Leaflet.
-   **Línea de Tiempo**: Visualiza el historial de estados del paquete.
-   **Chat**: Interfaz de mensajería en tiempo real.

#### `resources/views/envios/create.blade.php` (Modificado)
Formulario de creación mejorado.
-   **Wizard**: Formulario dividido en 3 pasos (Direcciones, Paquete, Programación).
-   **Mapas**: Integración de mapas para seleccionar coordenadas de remitente y destinatario.
-   **LocalStorage**: Persistencia de datos del remitente para agilizar envíos recurrentes.

#### `resources/views/envios/show.blade.php` (Modificado)
-   **QR**: Se agregó una sección que genera y muestra el código QR del envío.

---

## 4. Flujo del Sistema

1.  **Creación del Envío**:
    -   El administrador llena el formulario.
    -   Selecciona la ubicación en el mapa (se guardan `lat`/`lng`).
    -   Al guardar, se genera un código único (ej. `ENV-X7Y8Z9`).

2.  **Generación del QR**:
    -   El sistema utiliza el código único para crear una URL pública: `https://dominio.com/tracking/ENV-X7Y8Z9`.
    -   Esta URL se codifica en una imagen QR visible en los detalles del envío.

3.  **Acceso del Cliente**:
    -   El cliente escanea el QR.
    -   Accede a la ruta pública (sin login).
    -   Ve el estado, mapa y puede usar el chat.

4.  **Interacción (Chat)**:
    -   El cliente escribe en el chat -> Se guarda en la BD (`es_repartidor = false`).
    -   El componente Livewire actualiza la vista del repartidor/admin (futura implementación de vista conductor) y viceversa.


- php artisan optimize:clear

## 5. Explicación Detallada: Tablas y Vista de Tracking

### Estructura de Base de Datos

El sistema se apoya en tres tablas fundamentales para gestionar el flujo de envíos y la comunicación:

1.  **Tabla `envios`**:
    *   **Propósito**: Núcleo del sistema, almacena toda la información estática y de estado actual del paquete.
    *   **Datos**: Código de rastreo, información de remitente/destinatario, descripción, peso y coordenadas (`lat`, `lng`) para el mapa.
    *   **Estado**: Mantiene el estado actual (pendiente, en ruta, entregado).

2.  **Tabla `historial_envios`**:
    *   **Propósito**: Bitácora de auditoría para la línea de tiempo.
    *   **Funcionamiento**: Registra cada cambio de estado, guardando el estado anterior, el nuevo, un comentario y la fecha. Esto alimenta la visualización cronológica en la vista de tracking.

3.  **Tabla `mensajes`**:
    *   **Propósito**: Canal de comunicación entre el cliente y el repartidor/admin.
    *   **Estructura**:
        *   `envio_id`: Vincula el mensaje al paquete.
        *   `mensaje`: El texto del mensaje.
        *   `es_repartidor`: Bandera booleana (`true` = Repartidor/Admin, `false` = Cliente) que determina el origen del mensaje.

### Funcionamiento de la Vista de Tracking (`TrackingEnvio`)

La vista de seguimiento es un componente Livewire dinámico que integra varias funcionalidades:

*   **Chat en Tiempo Real**:
    *   **Identificación**: El sistema detecta si el usuario es Admin/Repartidor o un Cliente público.
    *   **Visualización**: Los mensajes propios se muestran a la derecha (azul) y los ajenos a la izquierda (gris), basándose en la bandera `es_repartidor`.
    *   **Actualización**: Utiliza `wire:poll` para consultar la base de datos cada 3 segundos, permitiendo ver nuevos mensajes sin recargar la página.

*   **Mapa Interactivo**:
    *   Utiliza las coordenadas (`lat`, `lng`) almacenadas en el envío para inicializar un mapa de Leaflet centrado en el destino del paquete.

*   **Línea de Tiempo (Timeline)**:
    *   Recorre los registros de la tabla `historial_envios` para mostrar una lista cronológica de todos los eventos por los que ha pasado el paquete.

