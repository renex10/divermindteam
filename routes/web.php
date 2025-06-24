<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\StudentController;
use App\Models\Professional;
use App\Models\Course;

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
        Route::get('/students/stats', [StudentController::class, 'stats'])->name('students.stats');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        // routes/web.php
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        // routes/web.php
        /*   Route::get('/estudiantes/crear', [StudentController::class, 'create'])
            ->name('students.create'); */
    });
