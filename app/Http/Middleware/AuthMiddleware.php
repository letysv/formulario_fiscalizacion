<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta página');
        }
        
        $user = \App\Models\User::find(session('user_id'));
        
        if (!$user || !$user->activo) {
            session()->flush();
            return redirect()->route('login')->with('error', 'Usuario no válido o desactivado');
        }
        
        return $next($request);
    }
}