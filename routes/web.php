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


=======
>>>>>>> parent of 4a5c83a (Merge pull request #7 from ces-silv/feature/crud-justificacion)
    Route::view('/about', 'pages.about')
    ->name('about');

    Route::view('/about', 'pages.about')->name('about');


 
});


require __DIR__.'/auth.php';
