@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-7xl mx-auto">
            <livewire:usuarios-index />
        </div>
    </main>
</div>

@push('scripts')
<script>
// Confirm delete user with SweetAlert2
function confirmDeleteUser(id, nombre) {
    Swal.fire({
        title: 'Eliminar Usuario',
        html: `<p class="text-gray-600">Estas seguro que deseas eliminar a <strong>${nombre}</strong>?</p><p class="text-sm text-gray-500 mt-2">Esta accion no se puede deshacer.</p>`,
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
            document.getElementById(`delete-user-form-${id}`).submit();
        }
    });
}
</script>
@endpush
@endsection
