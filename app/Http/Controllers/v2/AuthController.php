<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Inicio de sesión satisfactorio'], 200);
        }

        return response()->json(['message' => 'Usuario o contraseña incorrectos'], 401);
    }

    // Logout
    public function logout(Request $request)
    {
        Log::info('Logout attempt', [
            'headers' => $request->headers->all(),
            'cookies' => $request->cookies->all(),
        ]);
    
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Obtener usuario autenticado
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}