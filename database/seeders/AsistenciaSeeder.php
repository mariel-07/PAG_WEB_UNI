<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asistencia;
use Illuminate\Support\Facades\File;

class AsistenciaSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = public_path('json/asistencia-docente.json');

        if (!File::exists($ruta)) {
            $this->command->error(
                'No se encontró el archivo: ' . $ruta
            );

            return;
        }

        $contenido = File::get($ruta);
        $asistencias = json_decode($contenido, true);

        if (!is_array($asistencias)) {
            $this->command->error(
                'El archivo asistencia-docente.json no contiene un JSON válido.'
            );

            return;
        }

        foreach ($asistencias as $asistencia) {

            Asistencia::updateOrCreate(
                [
                    'id' => $asistencia['id'],
                ],
                [
                    'codHorario' => $asistencia['codHorario'] ?? null,
                    'codDocente' => $asistencia['codDocente'],
                    'fecha' => $asistencia['fecha'],
                    'codCurso' => $asistencia['codCurso'],
                    'secCurso' => $asistencia['secCurso'],
                    'teopra' => $asistencia['teopra'] ?? null,
                    'hora' => $asistencia['hora'] ?? null,
                    'horEntrada' => $asistencia['horEntrada'] ?? null,
                    'horSalida' => $asistencia['horSalida'] ?? null,
                    'semestre' => $asistencia['semestre'],
                    'estado' => $asistencia['estado'],
                ]
            );
        }

        $this->command->info(
            'Asistencias importadas correctamente: ' . count($asistencias)
        );
    }
}