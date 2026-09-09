
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
        CSS DE ASISTENCIA
        ARCHIVO:
        public/css/asistencia.css
    ====================================================== --}}
    <link rel="stylesheet"
          href="{{ asset('css/asistencia.css') }}">


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

                {{-- SEMESTRE --}}
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


                {{-- DOCENTE --}}
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


                {{-- CARGA HORARIA --}}
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


                {{-- CABECERA DEL CALENDARIO --}}
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


                    {{-- CONTROLES --}}
                    <div class="week-controls">

                        <button id="btnSemanaAnterior"
                                type="button"
                                title="Semana anterior">

                            <i class="fas fa-chevron-left"></i>

                        </button>


                        <div class="week-label"
                             id="lblSemana">

                            --

                        </div>


                        <button id="btnSemanaSiguiente"
                                type="button"
                                title="Semana siguiente">

                            <i class="fas fa-chevron-right"></i>

                        </button>


                        <button id="btnSemanaActual"
                                type="button"
                                title="Semana actual">

                            <i class="fas fa-calendar-day"></i>

                        </button>

                    </div>

                </div>


                {{-- TABLA DEL CALENDARIO --}}
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

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">

            <div class="modal-content asistencia-modal-content">


                {{-- CABECERA DEL MODAL --}}
                <div class="modal-header modal-header-uni">

                    <div class="modal-header-info">

                        <div class="modal-header-icon">
                            <i class="fas fa-user-check"></i>
                        </div>

                        <div>

                            <h5 class="modal-title"
                                id="modalTitulo">

                                Asistencia de alumnos

                            </h5>

                            <small id="modalSubtitulo">

                                --

                            </small>

                        </div>

                    </div>


                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">

                    </button>

                </div>


                {{-- CUERPO DEL MODAL --}}
                <div class="modal-body">


                    {{-- INFORMACIÓN DE LA CLASE --}}
                    <div class="modal-info-grid">


                        <div class="modal-info-card">

                            <div class="modal-info-icon">
                                <i class="fas fa-book"></i>
                            </div>

                            <div>

                                <span>
                                    CURSO
                                </span>

                                <strong id="modalCurso">
                                    --
                                </strong>

                            </div>

                        </div>


                        <div class="modal-info-card">

                            <div class="modal-info-icon">
                                <i class="fas fa-clock"></i>
                            </div>

                            <div>

                                <span>
                                    HORARIO
                                </span>

                                <strong id="modalHorario">
                                    --
                                </strong>

                            </div>

                        </div>


                        <div class="modal-info-card">

                            <div class="modal-info-icon">
                                <i class="fas fa-circle-info"></i>
                            </div>

                            <div>

                                <span>
                                    ESTADO
                                </span>

                                <strong id="modalEstado">
                                    --
                                </strong>

                            </div>

                        </div>

                    </div>


                    <div class="modal-separator"></div>


                    {{-- LISTA GENERADA POR JAVASCRIPT --}}
                    <div id="listaAlumnos">

                    </div>


                </div>


                {{-- PIE DEL MODAL --}}
                <div class="modal-footer">

                    <button type="button"
                            class="btn-modal-cerrar"
                            data-bs-dismiss="modal">

                        <i class="fas fa-times"></i>

                        CERRAR

                    </button>

                </div>

            </div>

        </div>

    </div>


    <script>
    window.asistenciaApi = {
        asistencias: "{{ url('/api/asistencias') }}",
        horarios: "{{ url('/api/horarios') }}",
        alumnos: "{{ url('/api/alumnos') }}"
    };
</script>

<script src="{{ asset('js/asistencia.js') }}"></script>

</x-app-layout>

