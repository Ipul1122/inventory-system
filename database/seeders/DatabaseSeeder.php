<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Product; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleSeller = Role::create(['name' => 'seller']);
        $rolePelanggan = Role::create(['name' => 'pelanggan']);

        // 2. Buat Users Dummy
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role_id' => $roleAdmin->id,
        ]);

        User::create([
            'name' => 'Seller Satu',
            'email' => 'seller@toko.com',
            'password' => Hash::make('password'),
            'role_id' => $roleSeller->id,
        ]);

        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'pelanggan@toko.com',
            'password' => Hash::make('password'),
            'role_id' => $rolePelanggan->id,
        ]);
        
        // 3. Buat Dummy Product (Opsional, agar tidak kosong saat dites)
        Product::create([
            'name' => 'Laptop Gaming',
            'stock' => 10,
            'price' => 15000000
        ]);
        
        Product::create([
            'name' => 'Mouse Wireless',
            'stock' => 50,
            'price' => 150000
        ]);
    }
}