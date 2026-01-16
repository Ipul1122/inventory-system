<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

// 1. Public Routes (Login)
Route::post('auth/login', [AuthController::class, 'login'])->name('login');

// 2. Protected Routes (Harus punya Token)
Route::group(['middleware' => 'auth:api'], function () {
    
    // Auth Features
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // --- Product Management ---
    // Semua role (Admin, Seller, Pelanggan) boleh lihat produk
    Route::get('products', [ProductController::class, 'index']);
    
    // Hanya Admin yang boleh Create/Update/Delete Produk
    Route::middleware(['role:admin'])->group(function () {
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
        
        // Hanya Admin yang boleh kelola User
        Route::apiResource('users', UserController::class);
    });

    // --- Transaction ---
    // Admin dan Seller boleh melakukan transaksi
    Route::middleware(['role:admin,seller'])->group(function () {
        Route::post('transactions', [TransactionController::class, 'store']);
    });

});