<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
{
    // Buscar usuario
    $user = User::where('usuario', $request->usuario)->first();
    
    if ($user && $request->password === 'admin123') {
        session([
            'user_id' => $user->id,
            'user_nombre' => $user->nombre_completo,
            'user_usuario' => $user->usuario
        ]);
        return redirect()->route('registros.reporte')->with('success', 'Bienvenido');
    }
    
    return back()->with('error', 'Credenciales incorrectas');
}
    
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}