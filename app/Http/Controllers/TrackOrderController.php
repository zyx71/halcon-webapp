<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index()
    {
        return view('public.track-order');
    }

    public function track(Request $request)
    {
        $request->validate([
            'customer_number' => 'required',
            'invoice_number' => 'required',
        ]);

        $order = Order::where('customer_number', $request->customer_number)
                      ->where('invoice_number', $request->invoice_number)
                      ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'No se encontró ningún pedido con los datos proporcionados.');
        }

        return view('public.order-status', compact('order'));
    }

    public function view(Request $request)
    {
        $request->validate([
            'customer_number' => 'required',
            'invoice_number' => 'required',
        ]);

        $order = Order::where('customer_number', $request->query('customer_number'))
                      ->where('invoice_number', $request->query('invoice_number'))
                      ->first();

        if (!$order) {
            return redirect()->route('track.index')->with('error', 'No se encontró ningún pedido con los datos proporcionados.');
        }

        return view('public.order-status', compact('order'));
    }
}