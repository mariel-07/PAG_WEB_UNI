<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocenteController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocenteFamiliarController;


/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| RUTAS AUTENTICADAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | FAMILIARES DEL DOCENTE
    |--------------------------------------------------------------------------
    */

    // Obtener familiares
    Route::get(
        '/api/docentes/{docenteId}/familiares',
        [DocenteFamiliarController::class, 'getFamiliares']
    );

    // Registrar familiar
    Route::post(
        '/api/docentes/familiares',
        [DocenteFamiliarController::class, 'store']
    );

    // Eliminar familiar
    Route::delete(
        '/api/docentes/familiares/{id}',
        [DocenteFamiliarController::class, 'destroy']
    );

    // Exportar familiares a PDF
    Route::get(
        '/docentes/{docenteId}/familiares/pdf',
        [DocenteFamiliarController::class, 'exportPdf']
    )->name('familiares.pdf');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO DOCENTE
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | 1. INFORMACIÓN DOCENTE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/docente/informacion',
        [DocenteController::class, 'index']
    )->name('docente.informacion');


    /*
    |--------------------------------------------------------------------------
    | 2. ASISTENCIA DOCENTE
    |--------------------------------------------------------------------------
    |
    | Esta página trabaja directamente con los archivos JSON
    | ubicados en public/json/.
    |
    */

    Route::get('/docente/asistencia', function () {
        return view('docente.asistencia');
    })->name('docente.asistencia');


    /*
    |--------------------------------------------------------------------------
    | 3. HORARIO DOCENTE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/docente/horario',
        [HorarioController::class, 'index']
    )->name('docente.horario');


    /*
    |--------------------------------------------------------------------------
    | API HORARIOS
    |--------------------------------------------------------------------------
    |
    | Estas rutas son utilizadas por JavaScript mediante fetch().
    |
    */

    Route::get(
        '/api/horarios',
        [HorarioController::class, 'getHorarios']
    );

    Route::get(
        '/docente/api/horarios',
        [HorarioController::class, 'getHorarios']
    );


    /*
    |--------------------------------------------------------------------------
    | API ALUMNOS
    |--------------------------------------------------------------------------
    |
    | Obtiene los alumnos matriculados para mostrarlos
    | en el frontend/modal.
    |
    */

    Route::get(
        '/api/alumnos',
        [HorarioController::class, 'getAlumnosMatriculados']
    );

    Route::get(
        '/docente/api/alumnos',
        [HorarioController::class, 'getAlumnosMatriculados']
    );

});


/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';