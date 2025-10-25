@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center">Halcón Mensajería</h3>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('logo.png') }}" alt="Halcon Logo" class="img-fluid" style="max-height: 100px;">
                        <h4 class="mt-3">Sistema de Gestión de Pedidos</h4>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-search fa-3x text-primary mb-3"></i>
                                    <h5>Buscar Pedido</h5>
                                    <p class="text-muted">Busca tu pedido por número de factura o nombre</p>
                                    <form action="{{ route('search') }}" method="GET">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="query" placeholder="Número o nombre..." required>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i> Buscar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                                    <h5>Seguimiento de Pedido</h5>
                                    <p class="text-muted">Consulta el estado actual de tu pedido</p>
                                    <a href="{{ route('track.index') }}" class="btn btn-primary">
                                        <i class="fas fa-map-marker-alt me-2"></i> Seguir Pedido
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        @guest
                            <p class="text-muted">¿Eres empleado de Halcón?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                            </a>
                        @endguest
                        @auth
                            <p class="text-muted">Ya iniciaste sesión</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i> Acceder al Panel
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection