<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use Illuminate\Support\Facades\File;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = public_path('json/alumnos.json');

        if (!File::exists($ruta)) {
            $this->command->error(
                'No se encontró el archivo: public/json/alumnos.json'
            );

            return;
        }

        $contenido = File::get($ruta);

        $alumnos = json_decode($contenido, true);

        if (!is_array($alumnos)) {
            $this->command->error(
                'El archivo alumnos.json no contiene un JSON válido.'
            );

            return;
        }

        foreach ($alumnos as $dato) {

            Alumno::updateOrCreate(
                [
                    'codAlumno' => $dato['codAlumno']
                ],
                [
                    'apePaterno' => $dato['apePaterno'] ?? '',
                    'apeMaterno' => $dato['apeMaterno'] ?? '',
                    'nombres' => $dato['nombres'] ?? '',
                    'especialidad' => $dato['especialidad'] ?? null,
                    'celular' => $dato['celular'] ?? null,
                    'email' => $dato['email'] ?? null,
                ]
            );
        }

        $this->command->info(
            'Alumnos importados correctamente: ' . count($alumnos)
        );
    }
}

