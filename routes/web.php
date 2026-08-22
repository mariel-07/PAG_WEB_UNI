<?php
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    // Información Docente
    Route::get('/docente/informacion', [DocenteController::class, 'index'])->name('docente.informacion');
    
    // Asistencia Docente
    Route::get('/docente/asistencia', [AsistenciaController::class, 'index'])->name('docente.asistencia');
    
    // Horario Docente
    Route::get('/docente/horario', [HorarioController::class, 'index'])->name('docente.horario');
});
require __DIR__.'/auth.php';
