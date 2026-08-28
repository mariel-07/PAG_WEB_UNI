<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Horario;
use App\Models\Matricula;

class HorarioCompletoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insertar Alumnos de Ejemplo
        $alumnos = [
            [
                'codAlumno' => '20220101',
                'apePaterno' => 'Gomez',
                'apeMaterno' => 'Perez',
                'nombres' => 'Juan Carlos',
                'especialidad' => 'M3',
                'celular' => '987654321',
                'email' => 'juan.gomez@senati.pe'
            ],
            [
                'codAlumno' => '20220102',
                'apePaterno' => 'Ramos',
                'apeMaterno' => 'Lopez',
                'nombres' => 'Maria Fe',
                'especialidad' => 'M3',
                'celular' => '912345678',
                'email' => 'maria.ramos@senati.pe'
            ]
        ];

        foreach ($alumnos as $al) {
            Alumno::updateOrCreate(['codAlumno' => $al['codAlumno']], $al);
        }

        // 2. Insertar Horarios (Coloca aquí el código de Docente correspondiente a tu usuario logueado)
        // Cambia esto en la sección de $horarios:
$horarios = [
    [
        'semestre' => '20251',
        'dia' => 'LU',
        'hora' => '07-11',
        'codCurso' => 'CC101',
        'secCurso' => 'A',
        'teopra' => 'T',
        'codAula' => 1, // En lugar de 'LAB-01'
        'tope' => 30,
        'codDocente' => 'DOC001'
    ],
    [
        'semestre' => '20251',
        'dia' => 'MI',
        'hora' => '14-18',
        'codCurso' => 'CC102',
        'secCurso' => 'B',
        'teopra' => 'P',
        'codAula' => 204, // En lugar de 'AULA-204'
        'tope' => 25,
        'codDocente' => 'DOC001'
    ]
];

        foreach ($horarios as $h) {
            Horario::create($h);
        }

        // 3. Matricular a los alumnos en los Cursos del Horario
        $matriculas = [
            ['codAlumno' => '20220101', 'codCurso' => 'CC101', 'secCurso' => 'A', 'semestre' => '20251'],
            ['codAlumno' => '20220102', 'codCurso' => 'CC101', 'secCurso' => 'A', 'semestre' => '20251'],
            ['codAlumno' => '20220101', 'codCurso' => 'CC102', 'secCurso' => 'B', 'semestre' => '20251'],
        ];

        foreach ($matriculas as $m) {
            Matricula::create($m);
        }
    }
}

