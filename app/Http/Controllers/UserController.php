<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    // Hanya Admin yang boleh akses ini
    public function index()
    {
        // Load relasi 'role' agar kita tau dia admin/seller/pelanggan
        return response()->json(User::with('role')->get());
    }

    // Method baru khusus ganti role
    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi: Role harus valid (ada di tabel roles)
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        // Update Role
        $user->update([
            'role_id' => $request->role_id
        ]);

        return response()->json([
            'message' => 'Role pengguna berhasil diubah',
            'data' => $user->load('role') // Tampilkan data user + role barunya
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        return response()->json(['message' => 'User created', 'data' => $user], 201);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'User deleted']);
    }
}