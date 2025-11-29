<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        return view('usuarios.index');
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:admin,repartidor',
            'licencia' => 'nullable|string|max:50',
            'activo' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['activo'] = $request->has('activo');

        User::create($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $usuario)
    {
        $usuario->load(['vehiculoAsignaciones.vehiculo', 'envios', 'disponibilidades']);
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:admin,repartidor',
            'licencia' => 'nullable|string|max:50',
            'activo' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['activo'] = $request->has('activo');

        $usuario->update($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    public function toggleActive(User $usuario)
    {
        $usuario->update(['activo' => !$usuario->activo]);

        return redirect()->back()
            ->with('success', 'Estado del usuario actualizado.');
    }
}
