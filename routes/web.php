<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\StudentController;

// Ruta de inicio CON NOMBRE 'home'
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home'); // ✅ SOLUCIÓN CLAVE: Agrega el nombre 'home'

// Grupos de rutas con autenticación
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    
    // Rutas de estudiantes
    Route::get('/estudiantes', [StudentController::class, 'index'])->name('students.index');
    Route::post('/estudiantes', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/stats', [StudentController::class, 'stats'])->name('students.stats');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
});
Route::view('/inicio', 'placeholder')->name('inicio');
Route::view('/que-es', 'placeholder')->name('que-es');
Route::view('/caracteristicas', 'placeholder')->name('caracteristicas');
Route::view('/beneficios', 'placeholder')->name('beneficios');
Route::view('/normativa', 'placeholder')->name('normativa');
Route::view('/integraciones', 'placeholder')->name('integraciones');
Route::view('/contacto', 'placeholder')->name('contacto');
/* Route::view('/login', 'auth.login')->name('login'); */
