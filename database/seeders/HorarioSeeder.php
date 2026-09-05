<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = public_path('json/horario.json');

        if (!file_exists($ruta)) {
            $this->command->error("No existe el archivo: {$ruta}");
            return;
        }

        $contenido = file_get_contents($ruta);
        $datos = json_decode($contenido, true);

        if (!is_array($datos)) {
            $this->command->error('El archivo horario.json no contiene un arreglo válido.');
            return;
        }

        foreach ($datos as $dato) {

            Horario::updateOrCreate(
                [
                    'codHorario' => $dato['codHorario'],
                ],
                [
                    'semestre'       => $dato['semestre'] ?? null,
                    'dia'            => $dato['dia'] ?? null,
                    'hora'           => $dato['hora'] ?? null,
                    'codCurso'       => $dato['codCurso'] ?? null,
                    'secCurso'       => $dato['secCurso'] ?? null,
                    'teopra'         => $dato['teopra'] ?? null,
                    'codAula'        => $dato['codAula'] ?? null,
                    'tope'           => $dato['tope'] ?? null,
                    'codDocente'     => $dato['codDocente'] ?? null,
                    'estado'         => $dato['estado'] ?? null,
                    'fecRegistrado'  => $dato['fecRegistrado'] ?? null,
                    'fecModificacion'=> $dato['fecModificacion'] ?? null,
                ]
            );
        }

        $this->command->info(
            'Horario importado correctamente: ' . count($datos) . ' registros.'
        );
    }
}