<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class UsuariosIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $rol = '';

    #[Url(history: true)]
    public $activo = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRol()
    {
        $this->resetPage();
    }

    public function updatingActivo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('telefono', 'like', "%{$this->search}%");
            });
        }

        if ($this->rol) {
            $query->where('rol', $this->rol);
        }

        if ($this->activo !== '') {
            $query->where('activo', $this->activo === '1');
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.usuarios-index', [
            'usuarios' => $usuarios
        ]);
    }
}
