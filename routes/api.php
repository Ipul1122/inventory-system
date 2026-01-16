<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController; // Kita pakai ini utk logika sell
use App\Http\Controllers\UserController;

// --- 1. Modul Auth ---
Route::post('auth/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // --- 2. Modul Produk ---
    // GET /products (Authenticated user - Semua Role)
    Route::get('products', [ProductController::class, 'index']);

    // POST /products (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::post('products', [ProductController::class, 'store']);
    });

    // POST /products/{id}/sell (Admin & Seller)
    // Disini kita ubah strukturnya sesuai permintaan
    Route::middleware(['role:admin,seller'])->group(function () {
        Route::post('products/{id}/sell', [TransactionController::class, 'sell']);
    });

    // --- 3. Modul User ---
    // Hanya Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('users', [UserController::class, 'index']);
        
        // PUT /users/{id}/change-role
        Route::put('users/{id}/change-role', [UserController::class, 'changeRole']);
    });
});