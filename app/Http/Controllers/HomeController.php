<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class HomeController extends Controller
{
    // Página pública de búsqueda
    public function index()
    {
        // Siempre mostrar la página pública; el contenido adapta CTA según sesión
        return view('public.home');
    }

    // Procesar búsqueda pública
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:100'
        ]);

        $query = $request->input('query');
        
        $orders = Order::with(['status', 'user'])
            ->where('invoice_number', 'like', "%{$query}%")
            ->orWhere('customer_name', 'like', "%{$query}%")
            ->orWhere('customer_number', 'like', "%{$query}%")
            ->get();

        return view('public.search_results', compact('orders', 'query'));
    }

    // Dashboard para usuarios autenticados
    public function dashboard()
    {
        return view('dashboard');
    }
}
