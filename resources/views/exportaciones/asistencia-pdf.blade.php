
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>Reporte de Asistencia</title>

    <style>
        @page {
            margin: 35px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        .encabezado {
            text-align: center;
            margin-bottom: 15px;
        }

        .universidad {
            font-size: 15px;
            font-weight: bold;
        }

        .facultad {
            font-size: 12px;
            margin-top: 3px;
        }

        .titulo {
            font-size: 13px;
            font-weight: bold;
            margin-top: 12px;
        }

        .informacion {
            width: 100%;
            margin-bottom: 12px;
        }

        .informacion td {
            padding: 3px;
        }

        .etiqueta {
            font-weight: bold;
            width: 90px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #7b1113;
            color: white;
            padding: 6px 4px;
            border: 1px solid #555;
            text-align: center;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #aaa;
        }

        .centro {
            text-align: center;
        }

        .pie {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="encabezado">

        <div class="universidad">
            UNIVERSIDAD NACIONAL DE INGENIERÍA
        </div>

        <div class="facultad">
            FACULTAD DE INGENIERÍA MECÁNICA
        </div>

        <div class="titulo">
            REPORTE DE ASISTENCIA
        </div>

    </div>


    <table class="informacion">
        <tr>

            <td class="etiqueta">
                Docente:
            </td>

            <td>
                @if($docente)
                    {{ $docente->apePaterno }}
                    {{ $docente->apeMaterno }},
                    {{ $docente->nombres }}
                @else
                    —
                @endif
            </td>

            <td class="etiqueta">
                Semestre:
            </td>

            <td>
                {{ $filtros['semestre'] ?? ($horario->semestre ?? '—') }}
            </td>

        </tr>

        <tr>

            <td class="etiqueta">
                Curso:
            </td>

            <td>
                {{ $filtros['codCurso'] ?? ($horario->codCurso ?? '—') }}
            </td>

            <td class="etiqueta">
                Sección:
            </td>

            <td>
                {{ $filtros['secCurso'] ?? ($horario->secCurso ?? '—') }}
            </td>

        </tr>

        <tr>

            <td class="etiqueta">
                Fecha:
            </td>

            <td>
                {{ $filtros['fecha'] ?? '—' }}
            </td>

            <td class="etiqueta">
                Registros:
            </td>

            <td>
                {{ $asistencias->count() }}
            </td>

        </tr>
    </table>


    <table>

        <thead>
            <tr>
                <th>N°</th>
                <th>Código</th>
                <th>Estudiante</th>
                <th>Especialidad</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Observación</th>
            </tr>
        </thead>

        <tbody>

            @forelse($asistencias as $index => $asistencia)

                <tr>

                    <td class="centro">
                        {{ $index + 1 }}
                    </td>

                    <td class="centro">
                        {{ $asistencia->codAlumno }}
                    </td>

                    <td>
                        {{ $asistencia->apePaterno }}
                        {{ $asistencia->apeMaterno }},
                        {{ $asistencia->nombres }}
                    </td>

                    <td>
                        {{ $asistencia->especialidad ?? '—' }}
                    </td>

                    <td>
                        {{ $asistencia->email ?? '—' }}
                    </td>

                    <td class="centro">
                        {{ $asistencia->estado }}
                    </td>

                    <td>
                        {{ $asistencia->observacion ?? '' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="centro">
                        No existen registros de asistencia
                        para los filtros seleccionados.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="pie">
        Generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>

