<?php

namespace App\Exports;

use App\Models\AsistenciaAlumno;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AsistenciaExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected array $filtros;

    public function __construct(array $filtros = [])
    {
        $this->filtros = $filtros;
    }

    /**
     * Obtener los registros filtrados.
     */
    public function collection(): Collection
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

        if (!empty($this->filtros['codHorario'])) {
            $query->where(
                'asistencia_alumnos.codhorario',
                $this->filtros['codHorario']
            );
        }

        if (!empty($this->filtros['codDocente'])) {
            $query->where(
                'asistencia_alumnos.coddocente',
                $this->filtros['codDocente']
            );
        }

        if (!empty($this->filtros['codAlumno'])) {
            $query->where(
                'asistencia_alumnos.codalumno',
                $this->filtros['codAlumno']
            );
        }

        if (!empty($this->filtros['codCurso'])) {
            $query->where(
                'asistencia_alumnos.codcurso',
                $this->filtros['codCurso']
            );
        }

        if (!empty($this->filtros['secCurso'])) {
            $query->where(
                'asistencia_alumnos.seccurso',
                $this->filtros['secCurso']
            );
        }

        if (!empty($this->filtros['semestre'])) {
            $query->where(
                'asistencia_alumnos.semestre',
                $this->filtros['semestre']
            );
        }

        if (!empty($this->filtros['fecha'])) {
            $query->whereDate(
                'asistencia_alumnos.fecha',
                $this->filtros['fecha']
            );
        }

        return $query
            ->orderBy('alumnos.apepaterno')
            ->orderBy('alumnos.apematerno')
            ->orderBy('alumnos.nombres')
            ->get();
    }

    /**
     * Encabezados del archivo Excel.
     */
    public function headings(): array
    {
        return [
            'N°',
            'Código',
            'Estudiante',
            'Especialidad',
            'Correo',
            'Curso',
            'Sección',
            'Fecha',
            'Semestre',
            'Estado',
            'Observación',
        ];
    }

    /**
     * Convertir cada registro en una fila.
     */
    public function map($asistencia): array
    {
        static $numero = 0;

        $numero++;

        $apePat = $asistencia->apePaterno ?? $asistencia->apepaterno ?? '';
        $apeMat = $asistencia->apeMaterno ?? $asistencia->apematerno ?? '';
        $nom    = $asistencia->nombres ?? '';

        $nombreCompleto = trim("{$apePat} {$apeMat} {$nom}");

        $codAlumno = $asistencia->codAlumno ?? $asistencia->codalumno ?? '';
        $codCurso  = $asistencia->codCurso ?? $asistencia->codcurso ?? '';
        $secCurso  = $asistencia->secCurso ?? $asistencia->seccurso ?? '';

        return [
            $numero,
            $codAlumno,
            $nombreCompleto,
            $asistencia->especialidad ?? '',
            $asistencia->email ?? '',
            $codCurso,
            $secCurso,
            optional($asistencia->fecha)->format('d/m/Y'),
            $asistencia->semestre,
            $asistencia->estado,
            $asistencia->observacion ?? '',
        ];
    }
}
