<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTransactionRequest;

class TransactionController extends Controller
{
    // Ganti nama function jadi 'sell', dan terima parameter $id
    public function sell(Request $request, $id)
    {
        // Validasi input quantity saja (product_id dari URL)
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        return DB::transaction(function () use ($request, $id) {
            // Ambil produk berdasarkan ID di URL
            $product = Product::lockForUpdate()->findOrFail($id);

            // Logika Wajib: Stok tidak boleh negatif
            if ($product->stock < $request->quantity) {
                return response()->json(['error' => 'Stok tidak mencukupi, transaksi ditolak.'], 400);
            }

            // Hitung harga
            $totalPrice = $product->price * $request->quantity;

            // Kurangi Stok
            $product->decrement('stock', $request->quantity);

            // Simpan Transaksi
            $transaction = Transaction::create([
                'user_id' => auth('api')->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $totalPrice
            ]);

            return response()->json([
                'message' => 'Penjualan berhasil',
                'data' => $transaction
            ], 200); // Atau 201
        });
    }
}