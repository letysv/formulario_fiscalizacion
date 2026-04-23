<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [RegistroController::class, 'index'])->name('registros.index');
// Route::post('/registros', [RegistroController::class, 'store'])->name('registros.store');
// Route::get('/ayuntamientos/{sede_id}', [RegistroController::class, 'getAyuntamientos'])->name('get.ayuntamientos');
// Route::get('/reporte', [RegistroController::class, 'reporte'])->name('registros.reporte');
// Route::get('/exportar-excel', [RegistroController::class, 'exportarExcel'])->name('exportar.excel');

// Rutas públicas
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Formulario de registro - PÚBLICO
Route::get('/', [RegistroController::class, 'index'])->name('registros.index');
Route::post('/registros', [RegistroController::class, 'store'])->name('registros.store');
Route::get('/ayuntamientos/{sede_id}', [RegistroController::class, 'getAyuntamientos'])->name('get.ayuntamientos');

// Reportes - PROTEGIDOS (sin middleware, con verificación directa)
Route::get('/reporte', function() {
    if (!session()->has('user_id')) {
        return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a los reportes');
    }
    $controller = new RegistroController();
    return $controller->reporte();
})->name('registros.reporte');

Route::get('/exportar-excel', function() {
    if (!session()->has('user_id')) {
        return redirect()->route('login')->with('error', 'Debe iniciar sesión para exportar reportes');
    }
    $controller = new RegistroController();
    return $controller->exportarExcel(request());
})->name('exportar.excel');

// TRUNCAR registros - PROTEGIDO (solo admin)
Route::delete('/truncar-registros', function() {
    if (!session()->has('user_id')) {
        return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
    }
    
    // Verificar que sea usuario admin
    if (session('user_usuario') !== 'admin') {
        return response()->json(['success' => false, 'message' => 'No tienes permiso para realizar esta acción'], 403);
    }
    
    $controller = new RegistroController();
    return $controller->truncarRegistros(request());
})->name('truncar.registros');