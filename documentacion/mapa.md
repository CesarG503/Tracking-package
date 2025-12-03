# Documentación del Mapa

Esta sección describe cómo funciona la integración de mapas en el sistema de seguimiento de envíos.

## Librerías Utilizadas

El sistema utiliza las siguientes librerías para la funcionalidad de mapas:

1.  **Leaflet JS (v1.9.4)**: Librería principal para renderizar mapas interactivos.
    -   CDN CSS: `https://unpkg.com/leaflet@1.9.4/dist/leaflet.css`
    -   CDN JS: `https://unpkg.com/leaflet@1.9.4/dist/leaflet.js`
2.  **Leaflet Routing Machine (v3.2.12)**: Plugin para cálculo y visualización de rutas (incluido en el layout principal).
    -   CDN CSS: `https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css`
    -   CDN JS: `https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js`
3.  **OpenStreetMap**: Proveedor de los "tiles" (imágenes del mapa) base.
4.  **Stadia Maps**: Proveedor de tiles para el modo oscuro (usado en la vista de repartidor).

## Implementación

### 1. Inclusión de Recursos
Los estilos y scripts necesarios se cargan en el layout principal `resources/views/layouts/app.blade.php`. Esto asegura que Leaflet esté disponible en toda la aplicación.

### 2. Componente de Seguimiento (`TrackingEnvio`)
La visualización del mapa para un envío específico se maneja en la vista `resources/views/livewire/tracking-envio.blade.php`.

#### Estructura HTML
Se utiliza un contenedor `div` con el ID `map` y la directiva `wire:ignore`.
```html
<div id="map" wire:ignore class="h-64 w-full rounded-xl border border-border z-0"></div>
```
-   `wire:ignore`: Es crucial para evitar que Livewire reinicialice el div y destruya la instancia del mapa cuando se actualizan otros componentes (como el chat).

#### Inicialización (JavaScript)
El mapa se inicializa cuando Livewire termina de cargar (`livewire:initialized`).

1.  **Coordenadas**: Se obtienen del objeto `$envio` pasado desde el backend. Si no hay coordenadas, se usan valores por defecto (San Miguel).
    ```javascript
    const lat = {{ $envio->lat ?? 13.4834 }};
    const lng = {{ $envio->lng ?? -88.1833 }};
    ```

2.  **Instancia del Mapa**: Se crea el mapa y se centra en las coordenadas.
    ```javascript
    const map = L.map('map').setView([lat, lng], 13);
    ```

3.  **Capa Base**: Se añade la capa de OpenStreetMap.
    ```javascript
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    ```

4.  **Marcador**: Se añade un marcador en la posición del envío con un popup que muestra la dirección.
    ```javascript
    L.marker([lat, lng]).addTo(map)
        .bindPopup('{{ $envio->destinatario_direccion }}')
        .openPopup();
    ```

### 3. Vista de Repartidor (`Mis Envíos`)
En `resources/views/repartidor/mis-envios.blade.php` existe una implementación más avanzada que soporta **cambio de tema (claro/oscuro)** y actualización dinámica.

#### Características Adicionales
1.  **Soporte de Tema Oscuro**:
    -   Detecta la clase `dark` en el elemento `html`.
    -   Usa OpenStreetMap para modo claro.
    -   Usa Stadia Maps (Alidade Smooth Dark) para modo oscuro.
    -   Un `MutationObserver` vigila cambios en el tema para actualizar el mapa en tiempo real.

2.  **Actualización Dinámica**:
    -   Expone una función global `mostrarEnMapa(lat, lng, nombre, direccion, envioId)` que puede ser llamada desde Livewire o AlpineJS para mover el marcador sin recargar el mapa.

3.  **Manejo de Redimensionamiento**:
    -   Escucha el evento `resize` y llama a `map.invalidateSize()` para asegurar que el mapa se renderice correctamente si el contenedor cambia de tamaño.

### Notas para Desarrolladores
-   Si necesitas agregar funcionalidades de ruta (routing), la librería `leaflet-routing-machine` ya está importada en el proyecto.
-   Para cambiar el proveedor de mapas (ej. Google Maps), solo necesitas cambiar la URL en `L.tileLayer`, aunque podrías necesitar una API Key.
-   Siempre usa `wire:ignore` en el contenedor del mapa dentro de componentes Livewire.
