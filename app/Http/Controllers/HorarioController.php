<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Horario;

class HorarioController extends Controller
{
    // Cargar la vista principal de horarios
    public function index()
    {
        $horarios = Horario::withCount('matriculas')->get();

        // Apunta a la vista en resources/views/docente/horario.blade.php
        return view('docente.horario', compact('horarios'));
    }

    // Obtener los alumnos matriculados en un curso/sección/semestre específico
   public function getAlumnosMatriculados(Request $request)
{
    try {
        $codCurso = $request->query('codCurso');
        $secCurso = $request->query('secCurso');
        $semestre = $request->query('semestre');

        $alumnos = DB::table('matricula')
            ->join('alumnos', 'matricula.codAlumno', '=', 'alumnos.codAlumno')
            ->where('matricula.codCurso', $codCurso)
            ->where('matricula.secCurso', $secCurso)
            ->where('matricula.semestre', $semestre)
            ->select(
                'alumnos.codAlumno as codigo',
                DB::raw("CONCAT(alumnos.apePaterno, ' ', alumnos.apeMaterno, ', ', alumnos.nombres) as nombre"),
                'alumnos.celular',
                'alumnos.especialidad as esp',
                'alumnos.email'
            )
            ->get();

        return response()->json([
            'success' => true,
            'total' => $alumnos->count(),
            'alumnos' => $alumnos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'total' => 0,
            'alumnos' => [],
            'error' => $e->getMessage()
        ], 500);
    }
}
   public function getHorarios(Request $request)
{
    $semestre = $request->query('semestre');

    $query = Horario::withCount('matriculas');

    if ($semestre) {
        $query->where('semestre', $semestre);
    }

    // Si el horario debe ser solo del docente logueado
    if (auth()->check() && auth()->user()->codDocente) {
        $query->where('codDocente', auth()->user()->codDocente);
    }

    $horarios = $query->get();

    return response()->json($horarios);
}

}