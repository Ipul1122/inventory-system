<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTransactionRequest;

class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request)
    {
        // Jika insert gagal, stok tidak berkurang, dan sebaliknya.
        return DB::transaction(function () use ($request) {
            
            //  GET data produk
            $product = Product::lockForUpdate()->find($request->product_id); // Lock row agar tidak balapan data (Concurrency)

            // Cek Stok
            if ($product->stock < $request->quantity) {
                return response()->json(['error' => 'Stok tidak mencukupi'], 400);
            }

            $totalPrice = $product->price * $request->quantity;

            $product->decrement('stock', $request->quantity);

            // Simpan Transaksi berdasarkan user yang sedang login
            $transaction = Transaction::create([
                'user_id' => auth('api')->user()->id, 
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $totalPrice
            ]);

            return response()->json([
                'message' => 'Transaksi berhasil',
                'data' => $transaction
            ], 201);
        });
    }
}