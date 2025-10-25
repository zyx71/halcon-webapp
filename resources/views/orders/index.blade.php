@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 mb-0"><i class="fas fa-box text-primary me-2"></i>Lista de Órdenes</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva Orden
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($orders) > 0)
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-3">{{ $order->id }}</td>
                            <td>{{ $order->invoice_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $order->status->color ?? '#6c757d' }};">
                                    {{ $order->status->name ?? 'Sin estado' }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar" 
                                            onclick="if(confirm('¿Está seguro de eliminar esta orden?')) { document.getElementById('delete-form-{{ $order->id }}').submit(); }">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5>No hay órdenes disponibles</h5>
                                    <p class="text-muted">Crea una nueva orden para comenzar</p>
                                    <a href="{{ route('orders.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-2"></i>Nueva Orden
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
