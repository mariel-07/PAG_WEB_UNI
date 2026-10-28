<?php

namespace App\Http\Controllers;

use App\Exports\AsistenciaExport;
use App\Models\Alumno;
use App\Models\AsistenciaAlumno;
use App\Models\Docente;
use App\Models\Horario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportacionController extends Controller
{
    /**
     * Construye la consulta de asistencia de alumnos
     * utilizando los mismos filtros que utiliza la interfaz.
     */
    private function obtenerAsistencias(Request $request)
    {
        $query = AsistenciaAlumno::query()
            ->leftJoin(
                'alumnos',
                'asistencia_alumnos.codalumno',
                '=',
                'alumnos.codalumno'
            )
            ->select(
                'asistencia_alumnos.*',
                'alumnos.codalumno as codAlumno',
                'alumnos.apepaterno as apePaterno',
                'alumnos.apematerno as apeMaterno',
                'alumnos.nombres',
                'alumnos.especialidad',
                'alumnos.email'
            );

        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('codHorario')) {
            $query->where(
                'asistencia_alumnos.codhorario',
                $request->codHorario
            );
        }

        if ($request->filled('codDocente')) {
            $query->where(
                'asistencia_alumnos.coddocente',
                $request->codDocente
            );
        }

        if ($request->filled('codAlumno')) {
            $query->where(
                'asistencia_alumnos.codalumno',
                $request->codAlumno
            );
        }

        if ($request->filled('codCurso')) {
            $query->where(
                'asistencia_alumnos.codcurso',
                $request->codCurso
            );
        }

        if ($request->filled('secCurso')) {
            $query->where(
                'asistencia_alumnos.seccurso',
                $request->secCurso
            );
        }

        if ($request->filled('semestre')) {
            $query->where(
                'asistencia_alumnos.semestre',
                $request->semestre
            );
        }

        if ($request->filled('fecha')) {
            $query->whereDate(
                'asistencia_alumnos.fecha',
                $request->fecha
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ORDEN
        |--------------------------------------------------------------------------
        */

        $query->orderBy('alumnos.apepaterno')
            ->orderBy('alumnos.apematerno')
            ->orderBy('alumnos.nombres');

        return $query;
    }

    /**
     * Exportar asistencia a PDF.
     */
    public function asistenciaPdf(Request $request)
    {
        $asistencias = $this->obtenerAsistencias($request)->get();

        /*
        |--------------------------------------------------------------------------
        | DATOS DEL HORARIO
        |--------------------------------------------------------------------------
        */

        $horario = null;

        if ($request->filled('codHorario')) {
            $horario = Horario::where(
                'codhorario',
                $request->codHorario
            )->first();
        }

        /*
        |--------------------------------------------------------------------------
        | DOCENTE
        |--------------------------------------------------------------------------
        */

        $docente = null;

        $codDocente = $request->codDocente;

        if (!$codDocente && $horario) {
            $codDocente = $horario->coddocente ?? $horario->codDocente;
        }

        if ($codDocente) {
            $docente = Docente::where(
                'coddocente',
                $codDocente
            )->first();
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAR PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'exportaciones.asistencia-pdf',
            [
                'asistencias' => $asistencias,
                'horario' => $horario,
                'docente' => $docente,
                'filtros' => $request->all(),
            ]
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'asistencia-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }

    /**
     * Exportar asistencia a Excel.
     */
    public function asistenciaExcel(Request $request)
    {
        return Excel::download(
            new AsistenciaExport($request->all()),
            'asistencia-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
