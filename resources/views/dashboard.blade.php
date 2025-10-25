@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle p-3 text-white">
                            <i class="fas fa-tachometer-alt fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h1 class="h3 mb-0">Bienvenido, {{ Auth::check() ? Auth::user()->name : 'Invitado' }}</h1>
                        <p class="text-muted mb-0">Panel de Control</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @if(Auth::check())
    {{-- Estadísticas rápidas --}}
    <div class="col-12 mb-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Órdenes Totales</h6>
                                <h2 class="mb-0 mt-2">{{ App\Models\Order::count() }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-box-open fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Órdenes Completadas</h6>
                                @php($deliveredId = \App\Models\OrderStatus::where('name','Delivered')->value('id'))
                                <h2 class="mb-0 mt-2">{{ \App\Models\Order::where('status_id', $deliveredId)->count() }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-check-circle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-dark">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Órdenes Pendientes</h6>
                                @php($orderedId = \App\Models\OrderStatus::where('name','Ordered')->value('id'))
                                <h2 class="mb-0 mt-2">{{ \App\Models\Order::where('status_id', $orderedId)->count() }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-clock fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Usuarios Activos</h6>
                                <h2 class="mb-0 mt-2">{{ App\Models\User::count() }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-users fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0"><i class="fas fa-users text-primary me-2"></i>Usuarios</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Gestiona los usuarios activos e inactivos, asigna roles y departamentos.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary"><i class="fas fa-list me-2"></i>Ver Usuarios</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Órdenes --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0"><i class="fas fa-box text-success me-2"></i>Órdenes</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Consulta, crea y actualiza las órdenes de clientes.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-success"><i class="fas fa-list me-2"></i>Ver Órdenes</a>
                    <a href="{{ route('orders.create') }}" class="btn btn-success"><i class="fas fa-plus me-2"></i>Nueva Orden</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Pedidos Archivados --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0"><i class="fas fa-archive text-warning me-2"></i>Pedidos Archivados</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Consulta y recupera pedidos eliminados lógicamente.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('orders.archived') }}" class="btn btn-outline-warning"><i class="fas fa-folder-open me-2"></i>Ver Archivados</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Productos y Clientes --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0"><i class="fas fa-tags text-info me-2"></i>Productos y Clientes</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Registra nuevos productos y clientes en el sistema.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-outline-info"><i class="fas fa-box-open me-2"></i>Agregar Productos</a>
                    <a href="{{ route('clients.create') }}" class="btn btn-info"><i class="fas fa-user-plus me-2"></i>Agregar Clientes</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0"><i class="fas fa-search text-primary me-2"></i>Buscar Pedido</h5>
            </div>
            <div class="card-body p-4">
                <p class="card-text">Ingresa el número de factura para consultar el estado de tu pedido.</p>
                <form action="{{ route('search') }}" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-file-invoice"></i></span>
                        <input type="text" name="invoice_number" class="form-control form-control-lg" placeholder="Número de factura" required>
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search me-2"></i>Buscar</button>
                    </div>
                </form>
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i> Si eres un empleado, inicia sesión para acceder a todas las funcionalidades del sistema.
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
