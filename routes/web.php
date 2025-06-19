<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\StudentController;
use App\Models\Professional;

// Ruta de inicio (puedes cambiarla)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/estudiantes', [StudentController::class, 'index'])->name('students.index');
        Route::post('/estudiantes', [StudentController::class, 'store'])->name('students.store');
    });

// Nueva ruta API para obtener profesionales
Route::get('/api/professionals', function () {
    return Professional::with(['user', 'specialty'])
        ->where('active', true)
        ->get()
        ->map(function ($prof) {
            return [
                'id' => $prof->id,
                'user' => [
                    'id' => $prof->user->id,
                    'name' => $prof->user->name
                ],
                'specialty' => [
                    'id' => $prof->specialty->id,
                    'name' => $prof->specialty->name
                ]
            ];
        });
})->middleware('auth:sanctum');