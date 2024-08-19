<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Jobs\ScanProductStockJob;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        $product = Product::findOrFail($validatedData['product_id']);

        // Calcular el total (ejemplo: cantidad * precio del producto)
        $total = $product->price * $validatedData['quantity'];

        // Crear la orden
        $order = Order::create([
            'product_id' => $validatedData['product_id'],
            'quantity' => $validatedData['quantity'],
            'user_id' => $validatedData['user_id'],
            'total' => $total,
        ]);

        // Actualizar el stock del producto
        $product->stock_quantity -= $validatedData['quantity'];
        $product->save();

         // Despacha el job
         ScanProductStockJob::dispatch();

        return response()->json(['order' => $order], 201);
    }
}
