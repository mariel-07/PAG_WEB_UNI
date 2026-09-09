<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AsistenciaController extends Controller
{
    /**
     * Vista de asistencia del docente.
     */
    public function vista()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            $usuario = Auth::user();

            $docente = DB::table('docentes')
                ->where('email', $usuario->email)
                ->first();

            if (!$docente) {
                return view('docente.asistencia', [
                    'codDocente' => null,
                    'emailDocente' => $usuario->email,
                    'errorDocente' => 'No se encontró un docente asociado al correo: ' . $usuario->email
                ]);
            }

            $codDocente = $docente->codDocente;

            return view('docente.asistencia', [
                'codDocente' => $codDocente,
                'emailDocente' => $docente->email,
                'nombreDocente' => $docente->nombres ?? '',
                'docente' => $docente,
                'errorDocente' => null
            ]);

        } catch (\Throwable $e) {

            return view('docente.asistencia', [
                'codDocente' => null,
                'emailDocente' => Auth::user()->email ?? null,
                'errorDocente' => 'Error al obtener el docente: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Obtener asistencias del docente.
     *
     * También permite obtener los alumnos de un curso,
     * sección y semestre mediante:
     *
     * alumnos -> matricula
     */
    public function index(Request $request): JsonResponse
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | 1. OBTENER CODDOCENTE
            |--------------------------------------------------------------------------
            */

            $codDocente = $request->input('codDocente');

            if (!$codDocente && Auth::check()) {

                $usuario = Auth::user();

                $docente = DB::table('docentes')
                    ->where('email', $usuario->email)
                    ->first();

                if (!$docente) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró el docente asociado al usuario autenticado.',
                        'email' => $usuario->email
                    ], 404);
                }

                $codDocente = $docente->codDocente;
            }


            /*
            |--------------------------------------------------------------------------
            | 2. CONSULTAR ASISTENCIAS
            |--------------------------------------------------------------------------
            */

            $query = Asistencia::query();

            if ($codDocente) {
                $query->where('codDocente', $codDocente);
            }

            if ($request->filled('fecha')) {
                $query->where('fecha', $request->fecha);
            }

            if ($request->filled('semestre')) {
                $query->where('semestre', $request->semestre);
            }

            if ($request->filled('codCurso')) {
                $query->where('codCurso', $request->codCurso);
            }

            if ($request->filled('secCurso')) {
                $query->where('secCurso', $request->secCurso);
            }

            $asistencias = $query
                ->orderBy('fecha', 'desc')
                ->orderBy('hora')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | 3. OBTENER ALUMNOS
            |--------------------------------------------------------------------------
            |
            | Los alumnos NO tienen codDocente.
            |
            | La relación es:
            |
            | alumnos.codAlumno
            |       ↓
            | matricula.codAlumno
            |
            | Y matricula se filtra por:
            |
            | codCurso
            | secCurso
            | semestre
            |
            |--------------------------------------------------------------------------
            */

            $alumnos = collect();

            $codCurso = $request->input('codCurso');
            $secCurso = $request->input('secCurso');
            $semestre = $request->input('semestre');


            /*
            |--------------------------------------------------------------------------
            | 4. SI NO VIENEN CURSO Y SECCIÓN, LOS OBTENEMOS
            |    DE LAS ASISTENCIAS
            |--------------------------------------------------------------------------
            */

            if (
                (!$codCurso || !$secCurso || !$semestre)
                && $asistencias->isNotEmpty()
            ) {

                $asistenciaReferencia = $asistencias->first();

                $codCurso = $codCurso ?: $asistenciaReferencia->codCurso;
                $secCurso = $secCurso ?: $asistenciaReferencia->secCurso;
                $semestre = $semestre ?: $asistenciaReferencia->semestre;
            }


            /*
            |--------------------------------------------------------------------------
            | 5. CONSULTAR ALUMNOS CON MATRICULA
            |--------------------------------------------------------------------------
            */

            if ($codCurso && $secCurso && $semestre) {

                $alumnos = DB::table('alumnos')
                    ->join(
                        'matricula',
                        'matricula.codAlumno',
                        '=',
                        'alumnos.codAlumno'
                    )
                    ->where('matricula.codCurso', $codCurso)
                    ->where('matricula.secCurso', $secCurso)
                    ->where('matricula.semestre', $semestre)
                    ->select(
                        'alumnos.id',
                        'alumnos.codAlumno',
                        'alumnos.apePaterno',
                        'alumnos.apeMaterno',
                        'alumnos.nombres',
                        'alumnos.especialidad',
                        'alumnos.celular',
                        'alumnos.email'
                    )
                    ->distinct()
                    ->orderBy('alumnos.apePaterno')
                    ->orderBy('alumnos.apeMaterno')
                    ->orderBy('alumnos.nombres')
                    ->get();
            }


            /*
            |--------------------------------------------------------------------------
            | 6. RESPUESTA JSON
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'codDocente' => $codDocente,

                'semestre' => $semestre,

                'codCurso' => $codCurso,

                'secCurso' => $secCurso,

                'total' => $asistencias->count(),

                'data' => $asistencias,

                'totalAlumnos' => $alumnos->count(),

                'alumnos' => $alumnos
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las asistencias y alumnos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Registrar asistencia.
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $validated = $request->validate([

                'codHorario' => 'nullable|integer',

                'codDocente' => [
                    'required',
                    'string',
                    'max:20'
                ],

                'fecha' => [
                    'required',
                    'date'
                ],

                'codCurso' => [
                    'required',
                    'string',
                    'max:50'
                ],

                'secCurso' => [
                    'required',
                    'string',
                    'max:20'
                ],

                'teopra' => [
                    'nullable',
                    'string',
                    'max:10'
                ],

                'hora' => [
                    'nullable',
                    'string',
                    'max:50'
                ],

                'horEntrada' => [
                    'nullable',
                    'date_format:H:i'
                ],

                'horSalida' => [
                    'nullable',
                    'date_format:H:i'
                ],

                'semestre' => [
                    'required',
                    'string',
                    'max:20'
                ],

                'estado' => [
                    'required',
                    'string',
                    'max:30'
                ],
            ]);

            $asistencia = Asistencia::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asistencia registrada correctamente.',
                'data' => $asistencia
            ], 201);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo registrar la asistencia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Mostrar una asistencia.
     */
    public function show($id): JsonResponse
    {
        try {

            $asistencia = Asistencia::find($id);

            if (!$asistencia) {

                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $asistencia
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la asistencia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Actualizar asistencia.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {

            $asistencia = Asistencia::find($id);

            if (!$asistencia) {

                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada.'
                ], 404);
            }

            $validated = $request->validate([

                'fecha' => [
                    'sometimes',
                    'required',
                    'date'
                ],

                'codCurso' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:50'
                ],

                'secCurso' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:20'
                ],

                'teopra' => [
                    'nullable',
                    'string',
                    'max:10'
                ],

                'hora' => [
                    'nullable',
                    'string',
                    'max:50'
                ],

                'horEntrada' => [
                    'nullable',
                    'date_format:H:i'
                ],

                'horSalida' => [
                    'nullable',
                    'date_format:H:i'
                ],

                'semestre' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:20'
                ],

                'estado' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:30'
                ],
            ]);

            $asistencia->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asistencia actualizada correctamente.',
                'data' => $asistencia
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar la asistencia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Eliminar asistencia.
     */
    public function destroy($id): JsonResponse
    {
        try {

            $asistencia = Asistencia::find($id);

            if (!$asistencia) {

                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada.'
                ], 404);
            }

            $asistencia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asistencia eliminada correctamente.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la asistencia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
 * Guardar o actualizar asistencia de un alumno.
 */
public function guardarAsistenciaAlumno(Request $request): JsonResponse
{
    try {

        $validated = $request->validate([
            'codHorario' => 'nullable|integer',
            'codDocente' => 'required|string|max:20',
            'codAlumno' => 'required|string|max:20',
            'fecha' => 'required|date',
            'codCurso' => 'required|string|max:50',
            'secCurso' => 'required|string|max:20',
            'semestre' => 'required|string|max:20',
            'estado' => 'required|string|max:30',
            'observacion' => 'nullable|string',
        ]);

        $asistencia = AsistenciaAlumno::updateOrCreate(
            [
                'codHorario' => $validated['codHorario'] ?? null,
                'codAlumno' => $validated['codAlumno'],
                'fecha' => $validated['fecha'],
                'semestre' => $validated['semestre'],
            ],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Asistencia del alumno guardada correctamente.',
            'data' => $asistencia
        ]);

    } catch (ValidationException $e) {

        return response()->json([
            'success' => false,
            'message' => 'Los datos enviados no son válidos.',
            'errors' => $e->errors()
        ], 422);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'No se pudo guardar la asistencia del alumno.',
            'error' => $e->getMessage()
        ], 500);
    }
}


/**
 * Obtener asistencias individuales de los alumnos.
 */
public function obtenerAsistenciasAlumnos(Request $request): JsonResponse
{
    try {

        $query = AsistenciaAlumno::query();

        if ($request->filled('codHorario')) {
            $query->where('codHorario', $request->codHorario);
        }

        if ($request->filled('codDocente')) {
            $query->where('codDocente', $request->codDocente);
        }

        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        if ($request->filled('codCurso')) {
            $query->where('codCurso', $request->codCurso);
        }

        if ($request->filled('secCurso')) {
            $query->where('secCurso', $request->secCurso);
        }

        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        $asistencias = $query
            ->orderBy('codAlumno')
            ->get();

        return response()->json([
            'success' => true,
            'total' => $asistencias->count(),
            'data' => $asistencias
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => 'No se pudieron obtener las asistencias de los alumnos.',
            'error' => $e->getMessage()
        ], 500);
    }
}
}

