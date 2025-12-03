<?php

namespace App\Livewire;

use App\Models\Envio;
use Livewire\Component;
use Livewire\WithPagination;

class EnviosIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterEstado = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $activeTimeTab = 'hoy'; // hoy, semana, todos
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterEstado' => ['except' => ''],
        'perPage' => ['except' => 10],
        'activeTimeTab' => ['except' => 'hoy'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterEstado = '';
        $this->resetPage();
    }

    public function setTimeTab($tab)
    {
        $this->activeTimeTab = $tab;
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function updatingActiveTimeTab()
    {
        $this->resetPage();
    }

    public function cancelEnvio($envioId)
    {
        $envio = Envio::find($envioId);
        
        if ($envio && $envio->estado !== 'entregado') {
            $envio->update(['estado' => 'cancelado']);
            session()->flash('message', 'Envío cancelado correctamente.');
        }
    }

    public function getEnviosProperty()
    {
        return Envio::query()
            ->with(['repartidor', 'vehiculoAsignacion.vehiculo'])
            ->when($this->activeTimeTab === 'hoy', function ($query) {
                $query->whereDate('created_at', today());
            })
            ->when($this->activeTimeTab === 'semana', function ($query) {
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('remitente_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('destinatario_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('destinatario_email', 'like', '%' . $this->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                        ->orWhere('codigo', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterEstado, function ($query) {
                $query->where('estado', $this->filterEstado);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.envios-index', [
            'envios' => $this->envios,
            'estados' => [
                'pendiente' => 'Pendiente',
                'en_ruta' => 'En Proceso',
                'entregado' => 'Entregado',
                'devuelto' => 'Devuelto',
                'cancelado' => 'Cancelado'
            ]
        ]);
    }
}
