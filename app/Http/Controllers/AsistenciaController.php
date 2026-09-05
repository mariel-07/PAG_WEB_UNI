<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AsistenciaController extends Controller
{
    /**
     * Obtener asistencias.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $query = Asistencia::query();

            // Filtrar por docente
            if ($request->filled('codDocente')) {
                $query->where(
                    'codDocente',
                    $request->codDocente
                );
            }

            // Filtrar por fecha
            if ($request->filled('fecha')) {
                $query->where(
                    'fecha',
                    $request->fecha
                );
            }

            // Filtrar por semestre
            if ($request->filled('semestre')) {
                $query->where(
                    'semestre',
                    $request->semestre
                );
            }

            $asistencias = $query
                ->orderBy('fecha', 'desc')
                ->orderBy('hora')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $asistencias
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las asistencias.',
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


        } catch (\Illuminate\Validation\ValidationException $e) {

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
    public function update(
        Request $request,
        $id
    ): JsonResponse {

        try {

            $asistencia = Asistencia::find($id);

            if (!$asistencia) {

                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada.'
                ], 404);
            }


            $validated = $request->validate([

                'fecha' => 'sometimes|required|date',

                'codCurso' =>
                    'sometimes|required|string|max:50',

                'secCurso' =>
                    'sometimes|required|string|max:20',

                'teopra' =>
                    'nullable|string|max:10',

                'hora' =>
                    'nullable|string|max:50',

                'horEntrada' =>
                    'nullable|date_format:H:i',

                'horSalida' =>
                    'nullable|date_format:H:i',

                'semestre' =>
                    'sometimes|required|string|max:20',

                'estado' =>
                    'sometimes|required|string|max:30',
            ]);


            $asistencia->update($validated);


            return response()->json([
                'success' => true,
                'message' => 'Asistencia actualizada correctamente.',
                'data' => $asistencia
            ]);


        } catch (\Illuminate\Validation\ValidationException $e) {

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
}