
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InformacionController;


/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
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
    | ========================================================================
    | MÓDULO INFORMACIÓN DOCENTE
    | ========================================================================
    |
    | El docente autenticado se identifica mediante:
    |
    | users.email → docentes.email
    |
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | PÁGINA INFORMACIÓN DOCENTE
    |--------------------------------------------------------------------------
    */

    Route::get('/docente/informacion', function () {
        return view('docente.informacion');
    })->name('docente.informacion');


    /*
    |--------------------------------------------------------------------------
    | INFORMACIÓN GENERAL DEL DOCENTE
    |--------------------------------------------------------------------------
    |
    | GET  → Obtener información desde MySQL
    | PUT  → Actualizar datos personales
    |
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/api/docente/informacion',
        [InformacionController::class, 'index']
    );

    Route::put(
        '/api/docente/informacion',
        [InformacionController::class, 'actualizarInformacion']
    );


    /*
    |--------------------------------------------------------------------------
    | DOMICILIOS
    |--------------------------------------------------------------------------
    |
    | POST   → Registrar domicilio
    | PUT    → Editar domicilio
    | DELETE → Eliminar domicilio
    |
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/api/docente/domicilios',
        [InformacionController::class, 'guardarDomicilio']
    );

    Route::put(
        '/api/docente/domicilios/{id}',
        [InformacionController::class, 'actualizarDomicilio']
    );

    Route::delete(
        '/api/docente/domicilios/{id}',
        [InformacionController::class, 'eliminarDomicilio']
    );


    /*
    |--------------------------------------------------------------------------
    | FAMILIARES / CARGA FAMILIAR
    |--------------------------------------------------------------------------
    |
    | POST   → Registrar familiar
    | PUT    → Editar familiar
    | DELETE → Eliminar familiar
    |
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/api/docente/familiares',
        [InformacionController::class, 'guardarFamiliar']
    );

    Route::put(
        '/api/docente/familiares/{id}',
        [InformacionController::class, 'actualizarFamiliar']
    );

    Route::delete(
        '/api/docente/familiares/{id}',
        [InformacionController::class, 'eliminarFamiliar']
    );


    /*
    |--------------------------------------------------------------------------
    | FORMACIÓN PROFESIONAL
    |--------------------------------------------------------------------------
    |
    | POST   → Registrar formación + PDF
    | PUT    → Editar formación
    | DELETE → Eliminar formación + PDF
    |
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/api/docente/formaciones',
        [InformacionController::class, 'guardarFormacion']
    );

    Route::put(
        '/api/docente/formaciones/{id}',
        [InformacionController::class, 'actualizarFormacion']
    );

    Route::delete(
    '/api/docente/formaciones/{id}',
    [InformacionController::class, 'eliminarFormacion']
);

    /*
    |--------------------------------------------------------------------------
    | ========================================================================
    | MÓDULO ASISTENCIA DOCENTE
    | ========================================================================
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/docente/asistencia',
        function () {
            return view('docente.asistencia');
        }
    )->name('docente.asistencia');


    /*
    |--------------------------------------------------------------------------
    | API ASISTENCIA DOCENTE
    |--------------------------------------------------------------------------
    */

    Route::prefix('api/asistencias')->group(function () {

        // LISTAR
        Route::get('/', [
            AsistenciaController::class,
            'index'
        ]);

        // CREAR
        Route::post('/', [
            AsistenciaController::class,
            'store'
        ]);

        // VER UNA
        Route::get('/{id}', [
            AsistenciaController::class,
            'show'
        ]);

        // ACTUALIZAR
        Route::put('/{id}', [
            AsistenciaController::class,
            'update'
        ]);

        // ELIMINAR
        Route::delete('/{id}', [
            AsistenciaController::class,
            'destroy'
        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | ========================================================================
    | MÓDULO HORARIO DOCENTE
    | ========================================================================
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
    */

    Route::get(
        '/api/horarios',
        [HorarioController::class, 'getHorarios']
    );


    /*
    |--------------------------------------------------------------------------
    | RUTA ALTERNATIVA HORARIOS
    |--------------------------------------------------------------------------
    |
    | Compatible con versiones anteriores del JavaScript.
    |
    */

    Route::get(
        '/docente/api/horarios',
        [HorarioController::class, 'getHorarios']
    );


    /*
    |--------------------------------------------------------------------------
    | API ALUMNOS MATRICULADOS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/api/alumnos',
        [HorarioController::class, 'getAlumnosMatriculados']
    );


    /*
    |--------------------------------------------------------------------------
    | RUTA ALTERNATIVA ALUMNOS
    |--------------------------------------------------------------------------
    |
    | Compatible con versiones anteriores del JavaScript.
    |
    */

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
