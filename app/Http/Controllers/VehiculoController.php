<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiculoController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehiculo::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('marca', 'like', "%{$search}%")
                    ->orWhere('modelo', 'like', "%{$search}%")
                    ->orWhere('placa', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $vehiculos = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create()
    {
        return view('vehiculos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'placa' => 'required|string|max:20|unique:vehiculos,placa',
            'anio' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'capacidad' => 'nullable|string|max:50',
            'estado' => 'required|in:disponible,en_uso,mantenimiento,inactivo',
            'observaciones' => 'nullable|string|max:500',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Procesar fotos múltiples
        $fotos = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('vehiculos', 'public');
                $fotos[] = $path;
            }
        }
        $validated['foto'] = json_encode($fotos);

        Vehiculo::create($validated);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }

    public function show(Vehiculo $vehiculo)
    {
        $vehiculo->load(['asignaciones.repartidor', 'disponibilidades']);
        return view('vehiculos.show', compact('vehiculo'));
    }

    public function edit(Vehiculo $vehiculo)
    {
        return view('vehiculos.edit', compact('vehiculo'));
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'placa' => 'required|string|max:20|unique:vehiculos,placa,' . $vehiculo->id,
            'anio' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'capacidad' => 'nullable|string|max:50',
            'estado' => 'required|in:disponible,en_uso,mantenimiento,inactivo',
            'observaciones' => 'nullable|string|max:500',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Obtener fotos existentes
        $fotos = json_decode($vehiculo->foto, true) ?? [];

        // Eliminar fotos marcadas
        if ($request->has('delete_fotos')) {
            foreach ($request->delete_fotos as $fotoPath) {
                Storage::disk('public')->delete($fotoPath);
                $fotos = array_filter($fotos, fn($f) => $f !== $fotoPath);
            }
        }

        // Agregar nuevas fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('vehiculos', 'public');
                $fotos[] = $path;
            }
        }

        $validated['foto'] = json_encode(array_values($fotos));

        $vehiculo->update($validated);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        // Eliminar fotos del storage
        $fotos = json_decode($vehiculo->foto, true) ?? [];
        foreach ($fotos as $foto) {
            Storage::disk('public')->delete($foto);
        }

        $vehiculo->delete();

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado exitosamente.');
    }
}
