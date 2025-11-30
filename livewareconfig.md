### Solución Livewire

1.  **Publicación de Assets**: Se publicaron los archivos de Livewire en la carpeta `public`.
    ```bash
    # Comando ejecutado (dentro de Docker):
    php artisan livewire:publish --assets
    ```
    Esto crea `public/vendor/livewire/livewire.js`.

2.  **Configuración (`config/livewire.php`)**:
    Se desactivó la inyección automática de assets para tener control manual.
    ```php
    'inject_assets' => false,
    ```

3.  **Layout Principal (`resources/views/layouts/app.blade.php`)**:
    Se agregaron manualmente los estilos y el script. **Nota importante**: Se usa una ruta relativa para el script para evitar problemas con la generación de URLs de `asset()`.

    ```blade
    <head>
        ...
        @livewireStyles
    </head>
    <body>
        ...
        <!-- Script manual con ruta relativa -->
        <script src="vendor/livewire/livewire.js" data-csrf="{{ csrf_token() }}" data-update-uri="{{ route('livewire.update') }}" data-navigate-once="true"></script>
    </body>
    ```

## 2. Renderizado de Componentes (Layouts)
Nuestro proyecto usa `layouts.app` con `@yield('content')`. Para que Livewire funcione con esto, debemos configurarlo explícitamente en cada componente.

### En la Clase del Componente (`app/Livewire/Ejemplo.php`)
Debemos indicar qué layout usar y en qué sección inyectar el contenido usando `extends()` y `section()`:

```php
public function render()
{
    return view('livewire.ejemplo')
        ->extends('layouts.app')  // El layout base
        ->section('content');     // La sección donde va el contenido (@yield('content'))
}
```

### En la Vista del Componente (`resources/views/livewire/ejemplo.blade.php`)
**NO** usar directivas `@extends` o `@section` en el archivo Blade del componente. Livewire requiere un único elemento raíz HTML.

**Correcto:**
```blade
<div>
    <h1>Contenido del componente</h1>
    <button wire:click="...">Acción</button>
</div>
```

**Incorrecto (Causará error `RootTagMissingFromViewException`):**
```blade
@section('content') <!-- NO HACER ESTO -->
<div>
    ...
</div>
@endsection
```
# Logica de clase Livewire

```php
public function render()
{
    return view('livewire.ejemplo')
        ->extends('layouts.app')  // El layout base
        ->section('content');     // La sección donde va el contenido (@yield('content'))
}
```


## 3. Comandos de artisan dentro del contenedor Docker

Para ejecutar comandos de artisan, hazlo dentro del contenedor Docker:

```bash
# 1. Acceder al contenedor
docker exec -it laravel_app bash

# 2. Ir al directorio del proyecto
cd tienda

# 3. Crear un nuevo componente
php artisan make:livewire NombreComponente

# 4. Limpiar caché de vistas (si los cambios no se reflejan)
php artisan view:clear
```
