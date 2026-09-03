{{-- =========================================================
    VISTA: ASISTENCIA DOCENTE
========================================================= --}}

<x-app-layout>

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <x-slot name="header">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

            <div class="d-flex align-items-center">

                <div class="uni-header-icon me-3">
                    <i class="fas fa-clipboard-check"></i>
                </div>

                <div>
                    <h2 class="h5 fw-bold mb-1 uni-title">
                        {{ __('Asistencia Docente') }}
                    </h2>

                    <span class="uni-subtitle">
                        Control y seguimiento de asistencia
                    </span>
                </div>

            </div>

            <div class="uni-institution">

                <i class="fas fa-university me-2"></i>

                UNIVERSIDAD NACIONAL DE INGENIERÍA

                <span class="uni-divider">|</span>

                FIM

            </div>

        </div>

    </x-slot>


    {{-- =====================================================
        FONT AWESOME
    ====================================================== --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    {{-- =====================================================
        ESTILOS
    ====================================================== --}}
    <style>

        .asistencia-page {
            background: #f5f7fa;
            min-height: calc(100vh - 70px);
        }

        .asistencia-container {
            max-width: 1450px;
            margin: auto;
            padding: 28px 25px 45px;
        }

        /* HEADER */

        .asistencia-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 22px;
        }

        .section-label {
            display: block;
            color: #800000;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .asistencia-heading h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
        }

        .asistencia-heading p {
            margin: 7px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .btn-pdf {
            border: 1px solid #800000;
            background: white;
            color: #800000;
            border-radius: 7px;
            padding: 9px 15px;
            font-weight: 700;
            font-size: 13px;
            transition: .2s;
        }

        .btn-pdf:hover {
            background: #800000;
            color: white;
        }


        /* INFORMACIÓN */

        .info-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 18px;
        }

        .info-card {
            background: white;
            border: 1px solid #e4e7eb;
            border-radius: 9px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3e8e8;
            color: #800000;
            font-size: 17px;
        }

        .info-card span {
            display: block;
            color: #8a8f98;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .info-card strong {
            display: block;
            margin-top: 3px;
            color: #252a31;
            font-size: 15px;
        }


        /* ESTADÍSTICAS */

        .stats-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e5e9;
            border-radius: 8px;
            padding: 12px 14px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #b8bec6;
        }

        .stat-card.programadas::before {
            background: #7b61d1;
        }

        .stat-card.puntuales::before {
            background: #28a745;
        }

        .stat-card.tardanzas::before {
            background: #f0ad00;
        }

        .stat-card.inasistencias::before {
            background: #dc3545;
        }

        .stat-card.no-aplica::before {
            background: #6c757d;
        }

        .stat-card.proceso::before {
            background: #1499d5;
        }

        .stat-title {
            font-size: 10px;
            font-weight: 800;
            color: #737981;
            display: block;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 800;
            color: #20252b;
            margin-top: 4px;
        }


        /* CALENDARIO */

        .calendar-card {
            background: white;
            border: 1px solid #dfe3e8;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 7px rgba(0,0,0,.04);
        }

        .calendar-header {
            padding: 17px 20px;
            border-bottom: 1px solid #e3e6e9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .calendar-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .calendar-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #800000;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calendar-title h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            color: #252a30;
        }

        .calendar-title p {
            margin: 3px 0 0;
            color: #8a9097;
            font-size: 11px;
        }

        .week-controls {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .week-controls button {
            border: 1px solid #d8dde2;
            background: white;
            color: #4b5563;
            width: 33px;
            height: 31px;
            border-radius: 5px;
        }

        .week-controls button:hover {
            background: #800000;
            color: white;
            border-color: #800000;
        }

        .week-label {
            min-width: 180px;
            text-align: center;
            font-size: 14px;
            font-weight: 800;
            color: #374151;
        }


        /* TABLA */

        .table-wrapper {
            overflow-x: auto;
        }

        .asistencia-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            min-width: 1100px;
        }

        .asistencia-table thead th {
            background: #edf8fc;
            border: 1px solid #dce8ed;
            height: 42px;
            font-size: 11px;
            color: #5d7680;
            font-weight: 800;
            text-align: center;
        }

        .asistencia-table thead th.hora-header {
            width: 90px;
        }

        .asistencia-table thead th.domingo {
            background: #f9e5e5;
            color: #a12d2d;
        }

        .asistencia-table tbody td {
            border: 1px solid #e1e5e8;
            height: 54px;
            padding: 0;
            vertical-align: top;
            background: #fff;
        }

        .asistencia-table tbody td.hora-cell {
            background: #fafbfc;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
        }

        .hora-inicio {
            font-size: 11px;
            font-weight: 800;
            color: #515860;
        }

        .hora-separador {
            font-size: 9px;
            color: #a3a8ae;
            margin: 1px 0;
        }

        .hora-fin {
            font-size: 10px;
            color: #777e86;
        }

        .domingo-cell {
            background: #fff4f4 !important;
        }

        .feriado-cell {
            background: #fff0f0 !important;
        }


        /* BLOQUES */

        .bloque-asistencia {
            height: 100%;
            min-height: 53px;
            padding: 8px 9px;
            margin: 1px;
            border-radius: 5px;
            background: #b8e5a5;
            border-left: 4px solid #5eb84d;
            cursor: pointer;
            position: relative;
            transition: .18s;
            overflow: hidden;
        }

        .bloque-asistencia:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,0,0,.12);
        }

        .bloque-programada {
            background: #c8b8f3;
            border-left-color: #7458c7;
        }

        .bloque-puntual {
            background: #b9e5a6;
            border-left-color: #55a83e;
        }

        .bloque-tardanza {
            background: #ffe3a3;
            border-left-color: #d79a00;
        }

        .bloque-inasistencia {
            background: #f4c4c4;
            border-left-color: #c93636;
        }

        .bloque-no-aplica {
            background: #e1e4e7;
            border-left-color: #7b8187;
        }

        .bloque-proceso {
            background: #bce6f7;
            border-left-color: #269bc9;
        }

        .bloque-curso {
            font-size: 11px;
            font-weight: 900;
            color: #283039;
        }

        .bloque-info {
            font-size: 9px;
            color: #4d555c;
            margin-top: 3px;
        }

        .bloque-estado {
            margin-top: 5px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .bloque-ojo {
            position: absolute;
            right: 7px;
            bottom: 6px;
            width: 23px;
            height: 23px;
            border-radius: 50%;
            border: 0;
            background: rgba(255,255,255,.7);
            color: #4b5563;
            cursor: pointer;
        }

        .bloque-ojo:hover {
            background: #800000;
            color: white;
        }


        /* LEYENDA */

        .legend {
            padding: 15px 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            border-top: 1px solid #e4e7ea;
        }

        .legend-title {
            font-size: 11px;
            font-weight: 800;
            color: #626a72;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            color: #626a72;
        }

        .legend-dot {
            width: 11px;
            height: 11px;
            border-radius: 3px;
        }

        .dot-programada { background: #c8b8f3; }
        .dot-puntual { background: #b9e5a6; }
        .dot-tardanza { background: #ffe3a3; }
        .dot-inasistencia { background: #f4c4c4; }
        .dot-no-aplica { background: #e1e4e7; }
        .dot-proceso { background: #bce6f7; }


        /* MODAL */

        .modal-header-uni {
            background: #800000;
            color: white;
            border-bottom: 4px solid #d5a900;
        }

        .student-row {
            padding: 11px 5px;
            border-bottom: 1px solid #e8eaed;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .student-name {
            font-weight: 700;
            font-size: 13px;
        }

        .student-code {
            color: #8a9097;
            font-size: 11px;
            margin-top: 3px;
        }

        .attendance-badge {
            font-size: 10px;
            font-weight: 800;
            padding: 5px 8px;
            border-radius: 12px;
            white-space: nowrap;
        }

        .badge-presente {
            background: #d8f1d1;
            color: #277321;
        }

        .badge-tarde {
            background: #fff0c5;
            color: #946600;
        }

        .badge-falta {
            background: #f7d4d4;
            color: #a32020;
        }


        /* RESPONSIVE */

        @media(max-width: 1000px) {

            .info-row {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }

            .asistencia-heading {
                align-items: flex-start;
                flex-direction: column;
            }

        }

        @media(max-width: 600px) {

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .asistencia-container {
                padding: 18px 10px;
            }

        }


        /* IMPRESIÓN */

        @media print {

            .week-controls,
            .btn-pdf {
                display: none !important;
            }

            .asistencia-page {
                background: white;
            }

            .calendar-card {
                box-shadow: none;
            }

        }

    </style>


    {{-- =====================================================
        CONTENIDO
    ====================================================== --}}

    <div class="asistencia-page">

        <div class="asistencia-container">


            {{-- =================================================
                ENCABEZADO
            ================================================== --}}

            <div class="asistencia-heading">

                <div>

                    <span class="section-label">
                        GESTIÓN ACADÉMICA
                    </span>

                    <h1>
                        Asistencia docente
                    </h1>

                    <p>
                        Consulte y supervise la asistencia correspondiente
                        a sus clases programadas.
                    </p>

                </div>


                <button class="btn-pdf"
                        type="button"
                        onclick="window.print()">

                    <i class="fas fa-file-pdf me-2"></i>

                    Exportar PDF

                </button>

            </div>


            {{-- =================================================
                INFORMACIÓN
            ================================================== --}}

            <div class="info-row">

                <div class="info-card">

                    <div class="info-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>

                    <div>

                        <span>
                            SEMESTRE ACADÉMICO
                        </span>

                        <select id="selectSemestre"
                                class="form-select form-select-sm mt-1">

                            <option value="20262">
                                2026-II
                            </option>

                            <option value="20261">
                                2026-I
                            </option>

                            <option value="20252">
                                2025-II
                            </option>

                        </select>

                    </div>

                </div>


                <div class="info-card">

                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>

                    <div>

                        <span>
                            DOCENTE
                        </span>

                        <strong id="lblDocente">
                            Dulce
                        </strong>

                    </div>

                </div>


                <div class="info-card">

                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div>

                        <span>
                            CARGA HORARIA
                        </span>

                        <strong id="lblCargaHoraria">
                            0 HRS
                        </strong>

                    </div>

                </div>

            </div>


            {{-- =================================================
                ESTADÍSTICAS
            ================================================== --}}

            <div class="stats-row">

                <div class="stat-card programadas">

                    <span class="stat-title">
                        PROGRAMADAS
                    </span>

                    <div class="stat-value"
                         id="statProgramadas">
                        0
                    </div>

                </div>


                <div class="stat-card puntuales">

                    <span class="stat-title">
                        PUNTUALES
                    </span>

                    <div class="stat-value"
                         id="statPuntuales">
                        0
                    </div>

                </div>


                <div class="stat-card tardanzas">

                    <span class="stat-title">
                        TARDANZAS
                    </span>

                    <div class="stat-value"
                         id="statTardanzas">
                        0
                    </div>

                </div>


                <div class="stat-card inasistencias">

                    <span class="stat-title">
                        INASISTENCIAS
                    </span>

                    <div class="stat-value"
                         id="statInasistencias">
                        0
                    </div>

                </div>


                <div class="stat-card no-aplica">

                    <span class="stat-title">
                        NO APLICA
                    </span>

                    <div class="stat-value"
                         id="statNoAplica">
                        0
                    </div>

                </div>


                <div class="stat-card proceso">

                    <span class="stat-title">
                        EN PROCESO DE CAMBIO
                    </span>

                    <div class="stat-value"
                         id="statProceso">
                        0
                    </div>

                </div>

            </div>


            {{-- =================================================
                CALENDARIO
            ================================================== --}}

            <div class="calendar-card">


                <div class="calendar-header">

                    <div class="calendar-title">

                        <div class="calendar-title-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>

                        <div>

                            <h3>
                                Programación de asistencia
                            </h3>

                            <p>
                                Clases programadas según horario docente
                            </p>

                        </div>

                    </div>


                    <div class="week-controls">

                        <button id="btnSemanaAnterior"
                                title="Semana anterior">

                            <i class="fas fa-chevron-left"></i>

                        </button>


                        <div class="week-label"
                             id="lblSemana">

                            --

                        </div>


                        <button id="btnSemanaSiguiente"
                                title="Semana siguiente">

                            <i class="fas fa-chevron-right"></i>

                        </button>


                        <button id="btnSemanaActual"
                                title="Semana actual">

                            <i class="fas fa-calendar-day"></i>

                        </button>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table class="asistencia-table"
                           id="tablaAsistencia">

                        <thead>

                            <tr>

                                <th class="hora-header">
                                    HORA
                                </th>

                                <th data-dia="LUNES">
                                    LUNES
                                </th>

                                <th data-dia="MARTES">
                                    MARTES
                                </th>

                                <th data-dia="MIÉRCOLES">
                                    MIÉRCOLES
                                </th>

                                <th data-dia="JUEVES">
                                    JUEVES
                                </th>

                                <th data-dia="VIERNES">
                                    VIERNES
                                </th>

                                <th data-dia="SÁBADO">
                                    SÁBADO
                                </th>

                                <th data-dia="DOMINGO"
                                    class="domingo">
                                    DOMINGO
                                </th>

                            </tr>

                        </thead>


                        <tbody id="tbodyAsistencia">

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    LEYENDA
                ================================================== --}}

                <div class="legend">

                    <span class="legend-title">
                        Referencia:
                    </span>


                    <span class="legend-item">
                        <span class="legend-dot dot-programada"></span>
                        Programada
                    </span>


                    <span class="legend-item">
                        <span class="legend-dot dot-puntual"></span>
                        Puntual
                    </span>


                    <span class="legend-item">
                        <span class="legend-dot dot-tardanza"></span>
                        Tardanza
                    </span>


                    <span class="legend-item">
                        <span class="legend-dot dot-inasistencia"></span>
                        Inasistencia
                    </span>


                    <span class="legend-item">
                        <span class="legend-dot dot-no-aplica"></span>
                        No aplica
                    </span>


                    <span class="legend-item">
                        <span class="legend-dot dot-proceso"></span>
                        En proceso de cambio
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        MODAL DE ALUMNOS
    ====================================================== --}}

    <div class="modal fade"
         id="modalAsistencia"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

            <div class="modal-content">

                <div class="modal-header modal-header-uni">

                    <div>

                        <h5 class="modal-title"
                            id="modalTitulo">

                            Asistencia de alumnos

                        </h5>

                        <small id="modalSubtitulo">

                            --

                        </small>

                    </div>


                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">

                    </button>

                </div>


                <div class="modal-body">

                    <div class="row mb-3">

                        <div class="col-md-4">

                            <small class="text-muted">
                                CURSO
                            </small>

                            <strong id="modalCurso"
                                    class="d-block">
                                --
                            </strong>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                HORARIO
                            </small>

                            <strong id="modalHorario"
                                    class="d-block">
                                --
                            </strong>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                ESTADO
                            </small>

                            <strong id="modalEstado"
                                    class="d-block">
                                --
                            </strong>

                        </div>

                    </div>


                    <hr>


                    <div id="listaAlumnos">

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">

                        CERRAR

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        URL DEL JSON
    ====================================================== --}}
<script>
    window.urlAsistenciaJson = "{{ asset('json/asistencia-docente.json') }}";
    window.urlHorarioJson = "{{ asset('json/horario.json') }}";
    window.urlAlumnosJson = "{{ asset('json/alumnos.json') }}";
</script>

<script src="{{ asset('js/asistencia.js') }}"></script>


</x-app-layout>