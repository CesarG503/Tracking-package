## 1. Comandos Utilizados

```bash
# Para crear el componente de Usuarios
php artisan make:livewire UsuariosIndex

# Para crear el componente de Vehículos
php artisan make:livewire VehiculosIndex
```

Estos comandos generan dos archivos por cada componente:
1.  **La Clase (Lógica):** `app/Livewire/NombreComponente.php`
2.  **La Vista (Diseño):** `resources/views/livewire/nombre-componente.blade.php`

## 2. Lógica de Conexión y Tiempo Real

La actualización en tiempo real y la interactividad sin recargar la página se basa en tres conceptos clave de Livewire que implementé:

### A. Polling (`wire:poll`)
Para lograr que "si otro admin agrega un usuario/vehículo se muestre de manera inmediata", utilicé la directiva `wire:poll`.

```html
<div wire:poll.5s>
    <!-- Contenido -->
</div>
```

*   **Cómo funciona:** Esta directiva le dice a Livewire que haga una petición al servidor cada **5 segundos** (o el tiempo que definas) para refrescar el componente.
*   **Resultado:** El componente se vuelve a renderizar con los datos más recientes de la base de datos automáticamente, sin que el usuario tenga que hacer nada.

### B. Binding en Tiempo Real (`wire:model.live`)
Para los buscadores y filtros, usé `wire:model.live`.

```html
<input type="text" wire:model.live.debounce.300ms="search" ...>
```

*   **`wire:model.live`:** Actualiza la propiedad `$search` en la clase PHP inmediatamente cuando el usuario escribe, sin esperar a que presione un botón o "Enter".
*   **`.debounce.300ms`:** Es una optimización. Espera 300 milisegundos después de que el usuario deja de escribir antes de enviar la petición, para no saturar el servidor con cada tecla pulsada.

### C. Estado en la URL (`#[Url]`)
En la clase del componente (`app/Livewire/UsuariosIndex.php`), usé atributos para mantener los filtros en la barra de direcciones.

```php
#[Url(history: true)]
public $search = '';
```

*   **Lógica:** Esto permite que si filtras por "admin" y recargas la página, o compartes el link, el filtro se mantenga activo.

## 3. Estructura de los Componentes

Así es como están construidos internamente los componentes que creé:

### Clase PHP (`app/Livewire/UsuariosIndex.php`)
Es el "cerebro" del componente.
*   **Propiedades (`public $search`):** Almacenan el estado de los filtros.
*   **Método `render()`:** Es donde ocurre la consulta a la base de datos.
    *   Construye la query basándose en los filtros (`where`, `like`).
    *   Ejecuta la paginación (`paginate(10)`).
    *   Retorna la vista con los datos.
*   **Hooks (`updatingSearch`):** Métodos especiales que resetean la paginación a la página 1 cuando se busca algo nuevo, para evitar errores de "página vacía".

### Vista Blade (`resources/views/livewire/usuarios-index.blade.php`)
Es la "cara" del componente.
*   Contiene todo el HTML de la tabla y los filtros.
*   Usa directivas de Livewire (`wire:click`, `wire:model`) en lugar de formularios HTML tradicionales (`action="..."`).
*   **Importante:** Todo el contenido debe estar dentro de un único elemento raíz `<div>`.

### Integración (`resources/views/usuarios/index.blade.php`)
La vista original de Laravel se simplificó drásticamente. Ahora solo actúa como un contenedor que carga el componente Livewire:

```html
@extends('layouts.app')
@section('content')
    <livewire:usuarios-index />
@endsection
```

De esta manera, toda la lógica compleja se encapsula dentro del componente Livewire, haciendo el código más limpio y mantenible.
