<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Penting: Import Facade

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     * Middleware ini memastikan method selain login harus punya token
     */
    public function __construct()
    {
        // Kita protect lewat route saja agar lebih fleksibel, 
        // jadi constructor ini boleh dikosongkan atau dihapus.
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        // PENTING: Gunakan auth('api') agar Laravel tau kita pakai JWT Guard
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized. Email atau Password salah'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // auth('api')->factory() memanggil instance JWTAuth untuk ambil TTL (Time To Live)
            'expires_in' => 3600,
            'user' => auth('api')->user()
        ]);
    }
}