<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnoMatriculaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insertar Alumnos de Prueba
        DB::table('alumnos')->insertOrIgnore([
            [
                'codAlumno' => '20230001A',
                'apePaterno' => 'Gómez',
                'apeMaterno' => 'Pérez',
                'nombres' => 'Juan Carlos',
                'celular' => '987654321',
                'especialidad' => 'M3',
                'email' => 'juan.gomez@uni.edu.pe',
            ],
            [
                'codAlumno' => '20230002B',
                'apePaterno' => 'Ramos',
                'apeMaterno' => 'López',
                'nombres' => 'María Fernanda',
                'celular' => '912345678',
                'especialidad' => 'M3',
                'email' => 'maria.ramos@uni.edu.pe',
            ],
        ]);

        // 2. Matricularlos en el curso CC101 / Sección A / Semestre 20251
        DB::table('matricula')->insertOrIgnore([
            [
                'codAlumno' => '20230001A',
                'codCurso' => 'CC101',
                'secCurso' => 'A',
                'semestre' => '20251',
            ],
            [
                'codAlumno' => '20230002B',
                'codCurso' => 'CC101',
                'secCurso' => 'A',
                'semestre' => '20251',
            ],
        ]);
    }
}