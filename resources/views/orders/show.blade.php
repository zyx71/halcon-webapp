@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 mb-0"><i class="fas fa-box-open text-primary me-2"></i>Detalles de la Orden #{{ $order->invoice_number }}</h1>
        <div>
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver a la lista
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-{{ $order->status->color ?? 'secondary' }} mb-2 fs-6">{{ $order->status->name ?? 'Sin estado' }}</span>
                            <p class="mb-1"><strong>Factura:</strong> {{ $order->invoice_number }}</p>
                            <p class="mb-1"><strong>Fecha de Orden:</strong> {{ $order->order_date ? date('d/m/Y', strtotime($order->order_date)) : date('d/m/Y', strtotime($order->created_at)) }}</p>
                            <p class="mb-1"><strong>Fecha de Creación:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-0"><strong>Última Actualización:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="mb-1"><strong>Nombre:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-1"><strong>Teléfono:</strong> {{ $order->customer_number ?? 'No especificado' }}</p>
                            <p class="mb-1"><strong>Dirección de Entrega:</strong> {{ $order->delivery_address ?? 'No especificada' }}</p>
                            <p class="mb-0"><strong>Datos Fiscales:</strong> {{ optional($order->client)->fiscal_data ?? 'No especificados' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Detalles Adicionales</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="mb-1"><strong>Empleado Asignado:</strong> {{ $order->user->name ?? 'No asignado' }}</p>
                            <p class="mb-0"><strong>Notas:</strong> {{ $order->notes ?? 'Sin notas adicionales' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Imagen de Inicio</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        @if($order->start_image)
                            <img src="{{ asset('storage/' . $order->start_image) }}" alt="Imagen de inicio" class="img-fluid rounded" style="max-height: 300px">
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-4x mb-3"></i>
                                <p>No hay imagen disponible</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Imagen de Entrega</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        @if($order->end_image)
                            <img src="{{ asset('storage/' . $order->end_image) }}" alt="Imagen de entrega" class="img-fluid rounded" style="max-height: 300px">
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-4x mb-3"></i>
                                <p>No hay imagen disponible</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @auth
    @if(auth()->user()->department && auth()->user()->department->name === 'Route')
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Subir Evidencia</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.uploadPhoto', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Fotografía</label>
                                <input type="file" name="photo" id="photo" class="form-control" accept="image/*" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="photo_type" id="photo_type_start" value="start" checked>
                                        <label class="form-check-label" for="photo_type_start">Inicio</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="photo_type" id="photo_type_end" value="end">
                                        <label class="form-check-label" for="photo_type_end">Entrega</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas (opcional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Agregar detalles relevantes"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Subir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth
    </div>
</div>
@endsection
