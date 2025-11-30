<?php

namespace App\Livewire;

use App\Models\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class VehiculosIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $estado = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Vehiculo::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('marca', 'like', "%{$this->search}%")
                    ->orWhere('modelo', 'like', "%{$this->search}%")
                    ->orWhere('placa', 'like', "%{$this->search}%");
            });
        }

        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        $vehiculos = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.vehiculos-index', [
            'vehiculos' => $vehiculos
        ]);
    }
}
