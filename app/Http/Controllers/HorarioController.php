<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Horario;

class HorarioController extends Controller
{
    /**
     * Mostrar vista principal de horarios
     */
    public function index()
    {
        $horarios = Horario::withCount('matriculas')->get();

        return view('docente.horario', compact('horarios'));
    }


    /**
     * API:
     * Obtener horarios desde MySQL
     */
    public function getHorarios(Request $request)
    {
        try {

            $semestre = $request->query('semestre');

            $query = Horario::withCount('matriculas');

            /*
             * FILTRO POR SEMESTRE
             */
            if ($semestre) {

                $query->where(
                    'semestre',
                    $semestre
                );

            }


            /*
             * IMPORTANTE
             *
             * Por ahora NO filtramos por
             * auth()->user()->codDocente
             *
             * porque tu usuario de login todavía
             * no tiene esa columna.
             */

            $horarios = $query
                ->orderBy('dia')
                ->orderBy('hora')
                ->get();


            return response()->json([

                'success' => true,

                'total' => $horarios->count(),

                'data' => $horarios

            ]);


        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'total' => 0,

                'data' => [],

                'error' => $e->getMessage()

            ], 500);

        }
    }


    /**
     * API:
     * Obtener alumnos matriculados
     */
    public function getAlumnosMatriculados(Request $request)
    {
        try {

            $codCurso = $request->query('codCurso');

            $secCurso = $request->query('secCurso');

            $semestre = $request->query('semestre');


            $alumnos = DB::table('matricula')

                ->join(
                    'alumnos',
                    'matricula.codAlumno',
                    '=',
                    'alumnos.codAlumno'
                )

                ->where(
                    'matricula.codCurso',
                    $codCurso
                )

                ->where(
                    'matricula.secCurso',
                    $secCurso
                )

                ->where(
                    'matricula.semestre',
                    $semestre
                )

                ->select(

                    'alumnos.codAlumno as codigo',

                    DB::raw("
                        CONCAT(
                            alumnos.apePaterno,
                            ' ',
                            alumnos.apeMaterno,
                            ', ',
                            alumnos.nombres
                        ) as nombre
                    "),

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
}