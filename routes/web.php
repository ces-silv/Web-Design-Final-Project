<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ClassController;
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
<<<<<<< HEAD
<<<<<<< HEAD

<<<<<<< HEAD

=======
>>>>>>> parent of 4a5c83a (Merge pull request #7 from ces-silv/feature/crud-justificacion)
    Route::view('/about', 'pages.about')
    ->name('about');
<<<<<<< HEAD

    Route::view('/about', 'pages.about')->name('about');


 
=======
>>>>>>> parent of 3ce20e3 (feat: Actualización de vistas de facultades)
=======
    Route::get('/justifications/available-classes', [JustificationController::class, 'getAvailableClasses'])
    ->name('justifications.available-classes')
    ->middleware(['auth']); // Asegura que solo usuarios autenticados puedan acceder
<<<<<<< HEAD
    Route::get('/available-classes', [ClassController::class, 'availableClasses']);
>>>>>>> parent of 38d2976 (Show the classes from a date range)
=======
>>>>>>> parent of 0e18f9b (Conection between the class schedule and the absence schedule)
=======
    Route::view('/about', 'pages.about')
    ->name('about');
>>>>>>> parent of dec530a (feature/justifications)
});


require __DIR__.'/auth.php';
