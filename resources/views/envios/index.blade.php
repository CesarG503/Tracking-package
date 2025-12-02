@extends('layouts.app')

@section('title', 'Envios')

@section('content')
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-3 sm:p-4 lg:p-6 overflow-hidden">
        <div class="flex flex-col lg:flex-row h-full gap-4 lg:gap-6">
            {{-- Livewire Component --}}
            <div class="flex-1 min-h-0 flex flex-col">
                <livewire:envios-index />
            </div>
            
            {{-- Preview Panel (Outside Livewire) --}}
            <div class="hidden lg:flex w-full lg:w-80 xl:w-96 h-auto lg:h-full bg-surface border border-border rounded-2xl flex-col shadow-sm order-first lg:order-last">
                {{-- Mobile Toggle Button --}}
                <button id="preview-toggle" class="lg:hidden w-full p-4 flex items-center justify-between bg-surface-secondary rounded-t-2xl border-b border-border">
                    <h2 class="text-lg font-semibold text-foreground">Vista Previa</h2>
                    <svg id="preview-chevron" class="w-5 h-5 text-foreground transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                {{-- Desktop Header --}}
                <div class="hidden lg:block p-4 xl:p-6 border-b border-border">
                    <h2 class="text-lg font-semibold text-foreground">Vista Previa</h2>
                </div>

                {{-- Preview Content Container --}}
                <div id="preview-container" class="flex-1 hidden lg:flex flex-col min-h-0">
                    {{-- Default state --}}
                    <div id="preview-empty" class="flex-1 flex flex-col items-center justify-center text-center p-6">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-2xl bg-surface-secondary flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-foreground-muted text-sm">Selecciona un envío para ver sus detalles</p>
                </div>

                    {{-- Package details --}}
                    <div id="preview-content" class="hidden flex-1 flex-col p-4 lg:p-6 min-h-0">
                        {{-- Package Image --}}
                        <div class="aspect-video lg:aspect-square xl:aspect-4/3 rounded-xl overflow-hidden bg-surface-secondary mb-4 shrink-0">
                            <img id="preview-image" src="" alt="Foto del paquete" class="w-full h-full object-cover hidden">
                            <div id="preview-no-image" class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Info --}}
                        <h3 id="preview-codigo" class="text-lg lg:text-xl font-bold text-foreground mb-2 shrink-0"></h3>
                        <p id="preview-desc" class="text-foreground-muted text-sm mb-4 line-clamp-3 lg:line-clamp-2 xl:line-clamp-3 shrink-0"></p>

                        {{-- Details --}}
                        <div class="space-y-3 mb-6 flex-1 overflow-y-auto min-h-0">
                            <div class="flex flex-col text-sm py-2 border-b border-border">
                                <span class="text-foreground-muted mb-1">Remitente</span>
                                <span id="preview-remitente" class="text-foreground font-medium break-all"></span>
                            </div>
                            <div class="flex flex-col text-sm py-2 border-b border-border">
                                <span class="text-foreground-muted mb-1">Destinatario</span>
                                <span id="preview-destinatario" class="text-foreground font-medium break-all"></span>
                            </div>
                            <div class="flex flex-col text-sm py-2 border-b border-border">
                                <span class="text-foreground-muted mb-1">Email</span>
                                <span id="preview-email" class="text-foreground font-medium break-all text-xs"></span>
                            </div>
                            <div id="preview-peso-row" class="flex flex-col text-sm py-2 border-b border-border" style="display: none;">
                                <span class="text-foreground-muted mb-1">Peso</span>
                                <span id="preview-peso" class="text-foreground font-medium"></span>
                            </div>
                            <div id="preview-tipo-row" class="flex flex-col text-sm py-2 border-b border-border" style="display: none;">
                                <span class="text-foreground-muted mb-1">Tipo</span>
                                <span id="preview-tipo" class="text-foreground font-medium"></span>
                            </div>
                            <div class="flex flex-col text-sm py-2 border-b border-border">
                                <span class="text-foreground-muted mb-1">Estado</span>
                                <span class="flex items-center gap-2">
                                    <span id="preview-status-dot" class="w-2 h-2 rounded-full bg-foreground-muted"></span>
                                    <span id="preview-status-text" class="text-foreground"></span>
                                </span>
                            </div>
                            <div class="flex flex-col text-sm py-2 border-b border-border">
                                <span class="text-foreground-muted mb-1">Repartidor</span>
                                <span id="preview-repartidor" class="text-foreground font-medium break-all"></span>
                            </div>
                            <div class="flex flex-col text-sm py-2">
                                <span class="text-foreground-muted mb-1">Fecha</span>
                                <span id="preview-fecha" class="text-foreground font-medium"></span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 shrink-0">
                            <a id="preview-edit-btn" href="#" class="flex-1 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl text-center text-sm font-medium transition-colors">
                                Editar
                            </a>
                            <a id="preview-show-btn" href="#" class="flex-1 py-2.5 bg-surface-secondary hover:bg-border text-foreground rounded-xl text-center text-sm font-medium transition-colors">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
// Mobile preview toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('preview-toggle');
    const container = document.getElementById('preview-container');
    const chevron = document.getElementById('preview-chevron');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isHidden = container.classList.contains('hidden');
            
            if (isHidden) {
                container.classList.remove('hidden');
                container.classList.add('flex');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                container.classList.add('hidden');
                container.classList.remove('flex');
                chevron.style.transform = 'rotate(0deg)';
            }
        });
    }
    
    // Auto-open preview on mobile when an envio is selected
    function autoToggleMobilePreview() {
        if (window.innerWidth < 1024) { // lg breakpoint
            const container = document.getElementById('preview-container');
            if (container && container.classList.contains('hidden')) {
                toggleBtn.click();
            }
        }
    }
    
    // Make autoToggleMobilePreview globally available
    window.autoToggleMobilePreview = autoToggleMobilePreview;
});

// Simple selection function without Livewire conflicts
function selectEnvio(id, codigo, remitente, destinatario, email, estado, descripcion, repartidor, fecha, peso, tipoEnvio, foto) {
    // Update row selection
    document.querySelectorAll('.envio-row').forEach(row => {
        row.classList.remove('bg-primary/5', 'border-l-4', 'border-l-primary');
    });
    const selectedRow = document.querySelector(`.envio-row[data-id="${id}"]`);
    if (selectedRow) {
        selectedRow.classList.add('bg-primary/5', 'border-l-4', 'border-l-primary');
    }

    // Update action buttons with correct URLs
    document.getElementById('preview-edit-btn').href = `/envios/${id}/edit`;
    document.getElementById('preview-show-btn').href = `/envios/${id}`;

    // Show content, hide empty
    document.getElementById('preview-empty').classList.add('hidden');
    document.getElementById('preview-content').classList.remove('hidden');
    document.getElementById('preview-content').classList.add('flex');
    
    // Auto-open mobile preview when selection is made
    if (window.autoToggleMobilePreview) {
        window.autoToggleMobilePreview();
    }

    // Update content
    document.getElementById('preview-codigo').textContent = codigo;
    document.getElementById('preview-desc').textContent = descripcion || 'Sin descripción del paquete';
    document.getElementById('preview-remitente').textContent = remitente;
    document.getElementById('preview-destinatario').textContent = destinatario;
    document.getElementById('preview-email').textContent = email;
    document.getElementById('preview-repartidor').textContent = repartidor || 'Sin asignar';
    document.getElementById('preview-fecha').textContent = fecha;

    // Update weight
    const pesoRow = document.getElementById('preview-peso-row');
    const pesoValue = document.getElementById('preview-peso');
    if (peso && peso.trim() !== '') {
        pesoRow.style.display = 'flex';
        pesoValue.textContent = peso + ' kg';
    } else {
        pesoRow.style.display = 'none';
    }

    // Update type
    const tipoRow = document.getElementById('preview-tipo-row');
    const tipoValue = document.getElementById('preview-tipo');
    if (tipoEnvio && tipoEnvio.trim() !== '') {
        tipoRow.style.display = 'flex';
        tipoValue.textContent = tipoEnvio;
    } else {
        tipoRow.style.display = 'none';
    }

    // Update status
    const statusColors = {
        'pendiente': 'bg-warning',
        'en_ruta': 'bg-primary',
        'entregado': 'bg-success',
        'devuelto': 'bg-danger',
        'cancelado': 'bg-foreground-muted'
    };
    const statusLabels = {
        'pendiente': 'Pendiente',
        'en_ruta': 'En Proceso',
        'entregado': 'Entregado',
        'devuelto': 'Devuelto',
        'cancelado': 'Cancelado'
    };
    const statusDot = document.getElementById('preview-status-dot');
    statusDot.className = 'w-2 h-2 rounded-full ' + (statusColors[estado] || 'bg-foreground-muted');
    document.getElementById('preview-status-text').textContent = statusLabels[estado] || estado;

    // Update image
    const imgEl = document.getElementById('preview-image');
    const noImgEl = document.getElementById('preview-no-image');
    if (foto && foto.length > 0) {
        imgEl.src = '/storage/' + foto;
        imgEl.classList.remove('hidden');
        noImgEl.classList.add('hidden');
    } else {
        imgEl.classList.add('hidden');
        noImgEl.classList.remove('hidden');
    }
}

// Confirm cancel with SweetAlert2
function confirmCancel(id, codigo) {
    Swal.fire({
        title: 'Cancelar Envío',
        html: `<p class="text-gray-600">¿Estás seguro que deseas cancelar el envío <strong>${codigo}</strong>?</p><p class="text-sm text-gray-500 mt-2">Esta acción no se puede deshacer.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No cancelar',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl',
            cancelButton: 'rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // You can call a Livewire method here to cancel the shipment
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('cancelEnvio', id);
        }
    });
}
</script>
@endpush
@endsection