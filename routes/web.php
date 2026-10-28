<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InformacionController;
use App\Http\Controllers\ExportacionController;
use App\Http\Controllers\MessageRecipientController;
use App\Livewire\Messages\Inbox;
use App\Livewire\Messages\ShowMessage;
use App\Livewire\Messages\SendMessage;


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
    | BANDEJA DE MENSAJERÍA (LIVEWIRE 3)
    |--------------------------------------------------------------------------
    */
    Route::prefix('mensajes')->name('messages.')->group(function () {
        Route::get('/', Inbox::class)->name('inbox');
        Route::get('/redactar', SendMessage::class)->name('create');
        Route::get('/buscar-receptores', [MessageRecipientController::class, 'search'])->name('search-recipients');
        Route::get('/adjuntos/{id}/descargar', [MessageRecipientController::class, 'downloadAttachment'])->name('attachment.download');
        Route::get('/{id}', ShowMessage::class)->name('show');
    });


    /*
    |--------------------------------------------------------------------------
    | ========================================================================
    | MÓDULO INFORMACIÓN DOCENTE
    | ========================================================================
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


    /*
    |--------------------------------------------------------------------------
    | PÁGINA ASISTENCIA DOCENTE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/docente/asistencia',
        [AsistenciaController::class, 'vista']
    )->name('docente.asistencia');


    /*
    |--------------------------------------------------------------------------
    | API ASISTENCIA DOCENTE
    |--------------------------------------------------------------------------
    */

    Route::prefix('api/asistencias')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | LISTAR ASISTENCIAS
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            AsistenciaController::class,
            'index'
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREAR ASISTENCIA GENERAL
        |--------------------------------------------------------------------------
        */

        Route::post('/', [
            AsistenciaController::class,
            'store'
        ]);


        /*
        |--------------------------------------------------------------------------
        | GUARDAR / ACTUALIZAR ASISTENCIA DE UN ALUMNO
        |--------------------------------------------------------------------------
        |
        | Guarda:
        | codHorario
        | codDocente
        | codAlumno
        | fecha
        | codCurso
        | secCurso
        | semestre
        | estado
        | observacion
        |
        */

        Route::post('/alumno', [
            AsistenciaController::class,
            'guardarAsistenciaAlumno'
        ]);


        /*
        |--------------------------------------------------------------------------
        | OBTENER ASISTENCIAS INDIVIDUALES DE ALUMNOS
        |--------------------------------------------------------------------------
        */

        Route::get('/alumnos', [
            AsistenciaController::class,
            'obtenerAsistenciasAlumnos'
        ]);


        /*
        |--------------------------------------------------------------------------
        | VER UNA ASISTENCIA
        |--------------------------------------------------------------------------
        */

        Route::get('/{id}', [
            AsistenciaController::class,
            'show'
        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR ASISTENCIA
        |--------------------------------------------------------------------------
        */

        Route::put('/{id}', [
            AsistenciaController::class,
            'update'
        ]);


        /*
        |--------------------------------------------------------------------------
        | ELIMINAR ASISTENCIA
        |--------------------------------------------------------------------------
        */

        Route::delete('/{id}', [
            AsistenciaController::class,
            'destroy'
        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | ========================================================================
    | EXPORTACIONES DE ASISTENCIA
    | ========================================================================
    |--------------------------------------------------------------------------
    |
    | Estas rutas utilizan:
    |
    | PDF   -> DOMPDF
    | Excel -> Maatwebsite Excel
    |
    | Ambas reciben los filtros de asistencia mediante GET.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR ASISTENCIA A PDF
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/exportaciones/asistencia/pdf',
        [ExportacionController::class, 'asistenciaPdf']
    )->name('exportaciones.asistencia.pdf');


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR ASISTENCIA A EXCEL
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/exportaciones/asistencia/excel',
        [ExportacionController::class, 'asistenciaExcel']
    )->name('exportaciones.asistencia.excel');


    /*
    |--------------------------------------------------------------------------
    | ========================================================================
    | MÓDULO HORARIO DOCENTE
    | ========================================================================
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | PÁGINA HORARIO DOCENTE
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