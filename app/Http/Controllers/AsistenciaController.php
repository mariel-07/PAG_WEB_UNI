<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\ValidationException;
use App\Models\Asistencia;
use App\Models\AsistenciaAlumno;

class AsistenciaController extends Controller
{
    /**
     * Vista de asistencia del docente.
     */
    public function vista() {
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

            $codDocente = $docente->coddocente ?? $docente->codDocente ?? null;

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
     * Obtener asistencias del docente y alumnos matriculados.
     */
    public function index(Request $request): JsonResponse {
        try {
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

                $codDocente = $docente->coddocente ?? $docente->codDocente ?? null;
            }

            // Consultar asistencias
            $query = Asistencia::query();

            if ($codDocente) {
                $query->where('coddocente', $codDocente);
            }

            if ($request->filled('fecha')) {
                $query->where('fecha', $request->fecha);
            }

            if ($request->filled('semestre')) {
                $query->where('semestre', $request->semestre);
            }

            if ($request->filled('codCurso')) {
                $query->where('codcurso', $request->codCurso);
            }

            if ($request->filled('secCurso')) {
                $query->where('seccurso', $request->secCurso);
            }

            $asistencias = $query
                ->orderBy('fecha', 'desc')
                ->orderBy('hora')
                ->get();

            // Obtener alumnos
            $alumnos = collect();
            $codCurso = $request->input('codCurso');
            $secCurso = $request->input('secCurso');
            $semestre = $request->input('semestre');

            if ((!$codCurso || !$secCurso || !$semestre) && $asistencias->isNotEmpty()) {
                $asistenciaReferencia = $asistencias->first();
                $codCurso = $codCurso ?: ($asistenciaReferencia->codcurso ?? $asistenciaReferencia->codCurso);
                $secCurso = $secCurso ?: ($asistenciaReferencia->seccurso ?? $asistenciaReferencia->secCurso);
                $semestre = $semestre ?: $asistenciaReferencia->semestre;
            }

            if ($codCurso && $secCurso && $semestre) {
                $alumnos = DB::table('alumnos')
                    ->join(
                        'matricula',
                        'matricula.codalumno',
                        '=',
                        'alumnos.codalumno'
                    )
                    ->where('matricula.codcurso', $codCurso)
                    ->where('matricula.seccurso', $secCurso)
                    ->where('matricula.semestre', $semestre)
                    ->select(
                        'alumnos.id',
                        'alumnos.codalumno as codAlumno',
                        'alumnos.apepaterno as apePaterno',
                        'alumnos.apematerno as apeMaterno',
                        'alumnos.nombres',
                        'alumnos.especialidad',
                        'alumnos.celular',
                        'alumnos.email'
                    )
                    ->distinct()
                    ->orderBy('alumnos.apepaterno')
                    ->orderBy('alumnos.apematerno')
                    ->orderBy('alumnos.nombres')
                    ->get();
            }

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
    public function store(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'codHorario' => 'nullable|integer',
                'codDocente' => 'required|string|max:20',
                'fecha' => 'required|date',
                'codCurso' => 'required|string|max:50',
                'secCurso' => 'required|string|max:20',
                'teopra' => 'nullable|string|max:10',
                'hora' => 'nullable|string|max:50',
                'horEntrada' => 'nullable|date_format:H:i',
                'horSalida' => 'nullable|date_format:H:i',
                'semestre' => 'required|string|max:20',
                'estado' => 'required|string|max:30',
            ]);

            $data = [
                'codhorario' => $validated['codHorario'] ?? null,
                'coddocente' => $validated['codDocente'],
                'fecha'      => $validated['fecha'],
                'codcurso'   => $validated['codCurso'],
                'seccurso'   => $validated['secCurso'],
                'teopra'     => $validated['teopra'] ?? null,
                'hora'       => $validated['hora'] ?? null,
                'horentrada' => $validated['horEntrada'] ?? null,
                'horsalida'  => $validated['horSalida'] ?? null,
                'semestre'   => $validated['semestre'],
                'estado'     => $validated['estado'],
            ];

            $asistencia = Asistencia::create($data);

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
     * Mostrar una asistencia específica.
     */
    public function show($id): JsonResponse {
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
                'message' => 'Error al consultar la asistencia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una asistencia existente.
     */
    public function update(Request $request, $id): JsonResponse {
        try {
            $asistencia = Asistencia::find($id);

            if (!$asistencia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada para actualizar.'
                ], 404);
            }

            $validated = $request->validate([
                'codHorario' => 'nullable|integer',
                'codDocente' => 'nullable|string|max:20',
                'fecha' => 'nullable|date',
                'codCurso' => 'nullable|string|max:50',
                'secCurso' => 'nullable|string|max:20',
                'teopra' => 'nullable|string|max:10',
                'hora' => 'nullable|string|max:50',
                'horEntrada' => 'nullable|date_format:H:i',
                'horSalida' => 'nullable|date_format:H:i',
                'semestre' => 'nullable|string|max:20',
                'estado' => 'nullable|string|max:30',
            ]);

            $data = [];
            if (array_key_exists('codHorario', $validated)) $data['codhorario'] = $validated['codHorario'];
            if (array_key_exists('codDocente', $validated)) $data['coddocente'] = $validated['codDocente'];
            if (array_key_exists('fecha', $validated)) $data['fecha'] = $validated['fecha'];
            if (array_key_exists('codCurso', $validated)) $data['codcurso'] = $validated['codCurso'];
            if (array_key_exists('secCurso', $validated)) $data['seccurso'] = $validated['secCurso'];
            if (array_key_exists('teopra', $validated)) $data['teopra'] = $validated['teopra'];
            if (array_key_exists('hora', $validated)) $data['hora'] = $validated['hora'];
            if (array_key_exists('horEntrada', $validated)) $data['horentrada'] = $validated['horEntrada'];
            if (array_key_exists('horSalida', $validated)) $data['horsalida'] = $validated['horSalida'];
            if (array_key_exists('semestre', $validated)) $data['semestre'] = $validated['semestre'];
            if (array_key_exists('estado', $validated)) $data['estado'] = $validated['estado'];

            $asistencia->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Asistencia actualizada correctamente.',
                'data' => $asistencia
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados para actualizar no son válidos.',
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
     * Eliminar una asistencia.
     */
    public function destroy($id): JsonResponse {
        try {
            $asistencia = Asistencia::find($id);

            if (!$asistencia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada para eliminar.'
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
     * Asegura la existencia de la tabla asistencia_alumnos si hiciera falta.
     */
    protected function asegurarTablaAsistenciaAlumnos(): void {
        if (!Schema::hasTable('asistencia_alumnos')) {
            Schema::create('asistencia_alumnos', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('codhorario')->nullable();
                $table->string('coddocente', 20);
                $table->string('codalumno', 20);
                $table->date('fecha');
                $table->string('codcurso', 50);
                $table->string('seccurso', 20);
                $table->string('semestre', 20);
                $table->string('estado', 30);
                $table->text('observacion')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Guardar o actualizar asistencia de un alumno.
     */
    public function guardarAsistenciaAlumno(Request $request): JsonResponse {
        try {
            $this->asegurarTablaAsistenciaAlumnos();

            $codDocente = $request->input('codDocente');
            if (empty($codDocente) && Auth::check()) {
                $docente = DB::table('docentes')->where('email', Auth::user()->email)->first();
                $codDocente = $docente ? ($docente->coddocente ?? $docente->codDocente) : 'DOCENTE';
                $request->merge(['codDocente' => $codDocente]);
            }

            if (empty($request->input('codDocente'))) {
                $request->merge(['codDocente' => 'DOCENTE']);
            }

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
                    'codalumno' => $validated['codAlumno'],
                    'fecha'     => $validated['fecha'],
                    'codcurso'  => $validated['codCurso'],
                    'seccurso'  => $validated['secCurso'],
                    'semestre'  => $validated['semestre'],
                ],
                [
                    'codhorario'  => $validated['codHorario'] ?? null,
                    'coddocente'  => $validated['codDocente'],
                    'codalumno'   => $validated['codAlumno'],
                    'fecha'       => $validated['fecha'],
                    'codcurso'    => $validated['codCurso'],
                    'seccurso'    => $validated['secCurso'],
                    'semestre'    => $validated['semestre'],
                    'estado'      => $validated['estado'],
                    'observacion' => $validated['observacion'] ?? null,
                ]
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
    public function obtenerAsistenciasAlumnos(Request $request): JsonResponse {
        try {
            $this->asegurarTablaAsistenciaAlumnos();

            $query = AsistenciaAlumno::query();

            if ($request->filled('codHorario')) {
                $query->where('codhorario', $request->codHorario);
            }

            if ($request->filled('codDocente')) {
                $query->where('coddocente', $request->codDocente);
            }

            if ($request->filled('fecha')) {
                $query->where('fecha', $request->fecha);
            }

            if ($request->filled('codCurso')) {
                $query->where('codcurso', $request->codCurso);
            }

            if ($request->filled('secCurso')) {
                $query->where('seccurso', $request->secCurso);
            }

            if ($request->filled('semestre')) {
                $query->where('semestre', $request->semestre);
            }

            $asistencias = $query
                ->orderBy('codalumno')
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
