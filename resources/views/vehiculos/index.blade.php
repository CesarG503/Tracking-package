@extends('layouts.app')

@section('title', 'Vehiculos')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <livewire:vehiculos-index />
    </main>
</div>

@push('scripts')
<script>
// Select vehicle and show preview
function selectVehiculo(id, marca, modelo, placa, anio, estado, observaciones, fotos, editUrl, showUrl) {
    // Update row selection
    document.querySelectorAll('.vehiculo-row').forEach(row => {
        row.classList.remove('bg-primary/5', 'border-l-4', 'border-l-primary');
    });
    const selectedRow = document.querySelector(`.vehiculo-row[data-id="${id}"]`);
    if (selectedRow) {
        selectedRow.classList.add('bg-primary/5', 'border-l-4', 'border-l-primary');
    }

    // Show content, hide empty
    document.getElementById('preview-empty').classList.add('hidden');
    document.getElementById('preview-content').classList.remove('hidden');

    // Update content
    document.getElementById('preview-name').textContent = modelo;
    document.getElementById('preview-desc').textContent = observaciones || 'Sin observaciones adicionales';
    document.getElementById('preview-marca').textContent = marca;
    document.getElementById('preview-anio').textContent = anio || '-';
    document.getElementById('preview-placa').textContent = placa;

    // Status
    const statusColors = {
        'disponible': 'bg-success',
        'asignado': 'bg-primary',
        'mantenimiento': 'bg-warning',
        'inactivo': 'bg-danger'
    };
    const statusLabels = {
        'disponible': 'Disponible',
        'asignado': 'Asignado',
        'mantenimiento': 'Mantenimiento',
        'inactivo': 'Inactivo'
    };
    const statusDot = document.getElementById('preview-status-dot');
    statusDot.className = 'w-2 h-2 rounded-full ' + (statusColors[estado] || 'bg-foreground-muted');
    document.getElementById('preview-status-text').textContent = statusLabels[estado] || estado;

    // Image
    const imgEl = document.getElementById('preview-image');
    const noImgEl = document.getElementById('preview-no-image');
    if (fotos && fotos.length > 0) {
        imgEl.src = '{{ asset("storage") }}/' + fotos[0];
        imgEl.classList.remove('hidden');
        noImgEl.classList.add('hidden');
    } else {
        imgEl.classList.add('hidden');
        noImgEl.classList.remove('hidden');
    }

    // Update links
    document.getElementById('preview-edit-btn').href = editUrl;
    document.getElementById('preview-view-btn').href = showUrl;
}

// Confirm delete with SweetAlert2
function confirmDelete(id, nombre) {
    Swal.fire({
        title: 'Eliminar Vehiculo',
        html: `<p class="text-gray-600">Estas seguro que deseas eliminar <strong>${nombre}</strong>?</p><p class="text-sm text-gray-500 mt-2">Esta accion no se puede deshacer.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>
@endpush
@endsection
