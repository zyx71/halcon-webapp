<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Photo;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Lista de órdenes
    public function index()
    {
        $orders = Order::with(['status', 'user'])->get();
        return view('orders.index', compact('orders'));
    }

    // Buscar orden por ID o cliente
    public function search(Request $request)
    {
        // Si no se envían criterios, mostrar el formulario de búsqueda con filtros
        $hasFilters = $request->filled('invoice_number')
            || $request->filled('customer_number')
            || $request->filled('query')
            || $request->filled('status_id')
            || $request->filled('date_from')
            || $request->filled('date_to');

        if (!$hasFilters) {
            return view('orders.search');
        }

        $orders = Order::with(['status', 'user'])
            ->when($request->filled('invoice_number'), function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
            })
            ->when($request->filled('customer_number'), function($q) use ($request) {
                $q->where('customer_number', 'like', '%' . $request->customer_number . '%');
            })
            ->when($request->filled('query'), function($q) use ($request) {
                $q->where(function($qq) use ($request) {
                    $qq->where('customer_name', 'like', '%' . $request->query . '%')
                       ->orWhere('invoice_number', 'like', '%' . $request->query . '%')
                       ->orWhere('customer_number', 'like', '%' . $request->query . '%');
                });
            })
            ->when($request->filled('status_id'), function($q) use ($request) {
                $q->where('status_id', $request->status_id);
            })
            ->when($request->filled('date_from'), function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->get();

        return view('orders.search_results', compact('orders'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $statuses = OrderStatus::all();
        $users = User::all();
        $clients = Client::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('orders.create', compact('statuses', 'users', 'clients', 'products'));
    }


public function store(Request $request)
    {
        // Validar los datos del formulario
        $data = $request->validate([
            'invoice_number' => 'nullable|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status_id' => 'nullable|exists:order_statuses,id',
            'user_id' => 'required|exists:users,id',
            'order_date' => 'nullable|date',
            'delivery_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'start_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'end_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // Si no envían invoice_number, generamos uno
        if (empty($data['invoice_number'])) {
            $data['invoice_number'] = 'FAC-' . strtoupper(uniqid());
        }

        // Estado por defecto 'Ordered' si no se envía
        if (empty($data['status_id'])) {
            $data['status_id'] = OrderStatus::where('name', 'Ordered')->value('id');
        }

        // Derivar datos del cliente
        $client = Client::find($data['client_id']);
        if ($client) {
            $data['customer_name'] = $client->name;
            $data['customer_number'] = $client->phone;
            if (empty($data['delivery_address'])) {
                $data['delivery_address'] = $client->address;
            }
        }

        // Procesar subida de imágenes
        if ($request->hasFile('start_image')) {
            try {
                $data['start_image'] = $request->file('start_image')->store('orders', 'public');
            } catch (\Exception $e) {
                // Log del error
                //Log::error('Error al subir imagen de inicio: ' . $e->getMessage());
            }
        }
        
        if ($request->hasFile('end_image')) {
            try {
                $data['end_image'] = $request->file('end_image')->store('orders', 'public');
            } catch (\Exception $e) {
                // Log del error
                //Log::error('Error al subir imagen de entrega: ' . $e->getMessage());
            }
        }

        try {
            // Crear la orden en la base de datos
            $order = Order::create($data);
            
            // Verificar si la orden se creó correctamente
            if ($order) {
                return redirect()->route('orders.index')
                    ->with('success', 'Orden #' . $order->invoice_number . ' creada correctamente.');
            } else {
                throw new \Exception('No se pudo crear la orden.');
            }
        } catch (\Exception $e) {
            // Log del error
            //\Log::error('Error al crear orden: ' . $e->getMessage());
            
            // Devolver al formulario con mensaje de error
             return redirect()->back()
                 ->withInput()
                 ->with('error', 'Error al crear la orden: ' . $e->getMessage());
        }
    }

    // Mostrar una orden
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

public function edit(Order $order)
    {
        $statuses = OrderStatus::all();
        $users = User::all();
        $clients = Client::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('orders.edit', compact('order', 'statuses', 'users', 'clients', 'products'));
    }


    // Actualizar orden
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status_id' => 'required|exists:order_statuses,id',
            'user_id' => 'required|exists:users,id',
            'order_date' => 'nullable|date',
            'delivery_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'start_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'end_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);
        
        // Si order_date está vacío, establecerlo como null
        if (empty($data['order_date'])) {
            $data['order_date'] = null;
        }
        
        // Procesar subida de imágenes
        if ($request->hasFile('start_image')) {
            try {
                // Eliminar imagen anterior si existe
                if ($order->start_image && file_exists(storage_path('app/public/' . $order->start_image))) {
                    unlink(storage_path('app/public/' . $order->start_image));
                }
                $data['start_image'] = $request->file('start_image')->store('orders', 'public');
            } catch (\Exception $e) {
                // Log del error
                //\Log::error('Error al subir imagen de inicio: ' . $e->getMessage());
            }
        } else {
            // Si no se sube una nueva imagen, mantener la existente
            unset($data['start_image']);
        }
        
        if ($request->hasFile('end_image')) {
            try {
                // Eliminar imagen anterior si existe
                if ($order->end_image && file_exists(storage_path('app/public/' . $order->end_image))) {
                    unlink(storage_path('app/public/' . $order->end_image));
                }
                $data['end_image'] = $request->file('end_image')->store('orders', 'public');
            } catch (\Exception $e) {
                // Log del error
               // \Log::error('Error al subir imagen de entrega: ' . $e->getMessage());
            }
        } else {
            // Si no se sube una nueva imagen, mantener la existente
            unset($data['end_image']);
        }

        try {
            // Derivar datos del cliente si se actualiza
            $client = Client::find($data['client_id']);
            if ($client) {
                $data['customer_name'] = $client->name;
                $data['customer_number'] = $client->phone;
            }

            $order->update($data);
            return redirect()->route('orders.index')
                ->with('success', 'Orden #' . $order->invoice_number . ' actualizada correctamente.');
        } catch (\Exception $e) {
            // Log del error
            //\Log::error('Error al actualizar orden: ' . $e->getMessage());
            
            // Devolver al formulario con mensaje de error
             return redirect()->back()
                 ->withInput()
                 ->with('error', 'Error al actualizar la orden: ' . $e->getMessage());
         }
      }

    // Eliminar (archivar) orden
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Orden eliminada.');
    }

    // Marcar como entregada
    public function markAsDelivered(Order $order)
    {
        $deliveredId = OrderStatus::where('name', 'Delivered')->value('id');
        if ($deliveredId) {
            $order->status_id = $deliveredId;
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Orden marcada como entregada.');
        }

        return redirect()->route('orders.index')->with('error', 'Estado Delivered no encontrado.');
    }

    // Cambiar estado de la orden
    public function changeStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status_id' => 'required|exists:order_statuses,id',
        ]);

        $order->update(['status_id' => $data['status_id']]);

        return redirect()->route('orders.show', $order)->with('success', 'Estado de la orden actualizado.');
    }

    // Subir fotografía de evidencia
    public function uploadPhoto(Request $request, Order $order)
    {
        // Restringir a departamento 'Route'
        $user = auth()->user();
        if (!$user || !$user->department || $user->department->name !== 'Route') {
            abort(403, 'Solo el departamento Route puede subir evidencia.');
        }

        $data = $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            'photo_type' => 'required|string|in:start,end',
            'notes' => 'nullable|string',
        ]);

        $path = $request->file('photo')->store('orders', 'public');

        // Asignar evidencia al pedido
        if ($data['photo_type'] === 'start') {
            $order->start_image = $path;
        } else {
            $order->end_image = $path;
        }
        $order->save();

        // Registrar en tabla photos para historial
        Photo::create([
            'order_id' => $order->id,
            'path' => $path,
            'type' => $data['photo_type'] === 'end' ? 'entrega' : 'en_ruta',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Fotografía subida correctamente.');
    }
}
