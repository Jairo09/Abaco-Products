<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Jobs\ScanProductStockJob;

class ProductController extends Controller
{
    // Listar todos los productos
    public function index()
    {
        $products = Product::paginate(10);
        return ProductResource::collection($products);
    }

    // Mostrar un producto específico
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $product = Product::create($validatedData);

        // Despacha el job
        ScanProductStockJob::dispatch();

        return new ProductResource($product);
    }

    // Actualizar un producto existente
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string',
            'base_price' => 'numeric',
            'price' => 'numeric',
            'stock_quantity' => 'integer',
        ]);

        $product->update($validatedData);

        // Despacha el job
        ScanProductStockJob::dispatch();

        return new ProductResource($product);
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
