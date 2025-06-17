<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\FacultyController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta dashboard protegida por auth, verified y ahora también por rol
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:user,admin'])->name('dashboard');

// Grupo de rutas de perfil - ahora verificamos rol también
Route::middleware(['auth', 'role:user,admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('professors', ProfessorController::class);
    Route::resource('classes', ClassController::class);
    Route::view('/about', 'pages.about')
    ->name('about');

    Route::post('/available-classes', [\App\Http\Controllers\JustificationController::class, 'getAvailableClasses']);
    Route::resource('faculties', FacultyController::class);
});


require __DIR__.'/auth.php';
