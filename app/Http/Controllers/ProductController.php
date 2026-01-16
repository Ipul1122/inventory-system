<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    // GET /api/products (public)
    public function index()
    {
        $products = Product::all();
        return response()->json(['data' => $products]);
    }

    // POST /api/products (Admin only)
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return response()->json(['message' => 'Product created', 'data' => $product], 201);
    }

    // PUT /api/products/{id} (Admin only)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $product->update($request->only(['name', 'stock', 'price']));
        
        return response()->json(['message' => 'Product updated', 'data' => $product]);
    }

    // DELETE /api/products/{id} (Admin only)
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(['message' => 'Product deleted']);
    }
}