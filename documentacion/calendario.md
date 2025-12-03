# Documentación del Calendario de Disponibilidad

Esta documentación detalla el funcionamiento técnico del módulo de calendario, incluyendo la generación de la vista, las consultas de validación y la gestión de disponibilidad de repartidores y vehículos.

## 1. Vista del Calendario (`resources/views/disponibilidad/index.blade.php`)

La interfaz del calendario se construye utilizando **Blade** y **JavaScript (Vanilla)**, sin depender de librerías de calendario externas pesadas (como FullCalendar).

### Generación de la Cuadrícula (Grid)
El calendario se renderiza como una cuadrícula de 7 columnas (días de la semana).

-   **Lógica de Fechas (Blade)**:
    -   Se calcula el primer y último día del mes seleccionado.
    -   Se extiende el rango para incluir días del mes anterior o siguiente para completar las semanas (comenzando en domingo y terminando en sábado).
    -   Se utiliza un bucle `@while` para iterar día por día.

```php
@php
    $primerDia = $fechaInicio->copy()->startOfMonth();
    $ultimoDia = $fechaInicio->copy()->endOfMonth();
    $inicioCalendario = $primerDia->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
    $finCalendario = $ultimoDia->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
    // ...
@endphp
```

-   **Visualización de Eventos**:
    -   Dentro del bucle de días, se filtran las disponibilidades (`$disponibilidades`) que caen dentro del día actual.
    -   Se muestran hasta 3 eventos como "píldoras" (`event-pill`). Si hay más, se muestra un indicador "+X más".

### Interactividad (JavaScript)
El archivo contiene scripts para manejar la interacción del usuario:

-   **Selección de Días**: Al hacer clic en una celda (`toggleDia`), se añade la fecha a un array `diasSeleccionados`.
-   **Selección de Repartidores**: Al hacer clic en un "chip" de repartidor (`toggleRepartidor`), se añade a `repartidoresSeleccionados`.
-   **Modales**: Se utilizan modales (con clases CSS para efectos "glassmorphism") para crear y editar disponibilidades.
-   **AJAX**: Las operaciones de creación (`store`), edición (`update`) y eliminación (`destroy`) se realizan mediante `fetch` API para no recargar la página completa, aunque en algunos casos se fuerza un `window.location.reload()` tras el éxito.

## 2. Controlador y Lógica de Negocio (`DisponibilidadController.php`)

El controlador maneja la lógica de validación y persistencia.

### Consultas de Validación (Disponibilidad)

La parte crítica es evitar conflictos de horario (doble reserva). Esto se valida tanto para **repartidores** como para **vehículos**.

#### Lógica de Superposición (Overlap)
Para verificar si un rango de tiempo (Inicio A - Fin A) se superpone con otro (Inicio B - Fin B), se utiliza la siguiente lógica en SQL/Eloquent:

```php
$query->whereBetween('fecha_inicio', [$inicio, $fin])
    ->orWhereBetween('fecha_fin', [$inicio, $fin])
    ->orWhere(function($q) use ($inicio, $fin) {
        $q->where('fecha_inicio', '<=', $inicio)
          ->where('fecha_fin', '>=', $fin);
    });
```
Esta consulta cubre los tres casos de superposición:
1.  El evento existente comienza dentro del nuevo rango.
2.  El evento existente termina dentro del nuevo rango.
3.  El evento existente engloba completamente al nuevo rango (empieza antes y termina después).

#### Validación al Crear (`store`)
1.  Itera sobre cada repartidor seleccionado y cada fecha seleccionada.
2.  Construye los timestamps de inicio y fin combinando la fecha con las horas seleccionadas.
3.  **Verifica Repartidor**: Consulta si existe alguna disponibilidad para ese `repartidor_id` que se superponga con el horario propuesto.
4.  **Verifica Vehículo** (si se seleccionó): Consulta si existe alguna disponibilidad para ese `vehiculo_id` que se superponga.
5.  Si hay conflicto, retorna un error 422 con un mensaje descriptivo.
6.  Si no hay conflicto, crea el registro en `disponibilidad` y también registra la asignación en `vehiculo_asignaciones`.

### Gestión de Colores
El controlador asigna colores a los repartidores dinámicamente usando un array predefinido (`$coloresEmpleados`).
-   Al cargar la vista (`index`), se asigna un color a cada repartidor basado en su índice.
-   Estos colores se pasan a la vista y se usan para pintar los "chips" de repartidores y las "píldoras" de eventos en el calendario.

## 3. Estructura de Datos

### Tabla `disponibilidad`
La tabla principal almacena los eventos:
-   `repartidor_id`: FK a usuarios.
-   `vehiculo_id`: FK a vehículos (nullable).
-   `fecha_inicio`: DateTime.
-   `fecha_fin`: DateTime.
-   `tipo`: Enum ('disponible', 'ocupado', 'vacaciones', 'bloqueo').

### Tabla `vehiculo_asignaciones`
Se mantiene una tabla paralela para el historial de asignaciones de vehículos, que se actualiza en sincronía con la disponibilidad.
