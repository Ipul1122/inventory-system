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
        // Gunakan DB Transaction agar data konsisten (Atomicity)
        // Jika insert gagal, stok tidak berkurang, dan sebaliknya.
        return DB::transaction(function () use ($request) {
            
            // 1. Ambil data produk
            $product = Product::lockForUpdate()->find($request->product_id); // Lock row agar tidak balapan data (Concurrency)

            // 2. Cek Stok
            if ($product->stock < $request->quantity) {
                return response()->json(['error' => 'Stok tidak mencukupi'], 400);
            }

            // 3. Hitung Total Harga
            $totalPrice = $product->price * $request->quantity;

            // 4. Kurangi Stok
            $product->decrement('stock', $request->quantity);

            // 5. Simpan Transaksi
            $transaction = Transaction::create([
                'user_id' => auth('api')->user()->id, // Mengambil ID user yg login (Seller/Admin)
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