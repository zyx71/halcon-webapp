<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ArchivedOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Auth;

// Página pública (inicio: búsqueda de pedidos)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Rutas públicas para seguimiento de pedidos
use App\Http\Controllers\TrackOrderController;
Route::get('/track', [TrackOrderController::class, 'index'])->name('track.index');
Route::post('/track', [TrackOrderController::class, 'track'])->name('track.order');
Route::get('/track/result', [TrackOrderController::class, 'view'])->name('track.view');

// Rutas públicas para buscar órdenes sin login
Route::get('/orders/search', [OrderController::class, 'search'])->name('orders.search');

// Rutas de autenticación (login, sin registro público)
Auth::routes(['register' => false]);

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    
    // Dashboard (solo logueados)
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Gestión de usuarios
    Route::resource('users', UserController::class);

    // Gestión de clientes
    Route::resource('clients', ClientController::class);

    // Gestión de productos
    Route::resource('products', ProductController::class);

    // Gestión de roles, departamentos y estados
    Route::resource('roles', RoleController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('statuses', StatusController::class);

    // Gestión de pedidos (CRUD completo)
    Route::resource('orders', OrderController::class);

    // Marcar como entregada
    Route::patch('/orders/{order}/delivered', [OrderController::class, 'markAsDelivered'])->name('orders.delivered');

    // Cambio de estado
    Route::post('/orders/{order}/status', [OrderController::class, 'changeStatus'])->name('orders.changeStatus');

    // Subir fotografía de evidencia
    Route::post('/orders/{order}/photo', [OrderController::class, 'uploadPhoto'])->name('orders.uploadPhoto');

    // Órdenes archivadas
    Route::get('/archived-orders', [ArchivedOrderController::class, 'index'])->name('orders.archived');
    Route::post('/archived-orders/{id}/restore', [ArchivedOrderController::class, 'restore'])->name('orders.restore');
});
