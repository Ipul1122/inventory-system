<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Penting: Import Facade

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     * Middleware memastikan method selain login harus punya token
     */
    public function __construct()
    {

    }

    /**
     * mendapatkan token JWT lewat kredensial yang diberikan.
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        // menggunakan auth('api') supaya laravel mengetahui memakai JWT Guard
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
     * Logout user (invalidate the token).
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * mendapatkan token JWT.
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600,
            'user' => auth('api')->user()
        ]);
    }
}