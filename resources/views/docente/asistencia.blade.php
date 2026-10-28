<x-app-layout>

    <!-- =========================================================
         LIBRERÍAS Y ESTILOS
         ========================================================= -->
    <link rel="stylesheet"
          href="{{ asset('css/asistencia.css') }}">

    <!-- =========================================================
         CONTENIDO PRINCIPAL
         ========================================================= -->
    <div class="asistencia-page">

        <div class="asistencia-container">

            <!-- =====================================================
                 ENCABEZADO INSTITUCIONAL
                 ===================================================== -->
            <div class="asistencia-heading-block">

                <div class="asistencia-heading-left">

                    <div class="asistencia-header-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>

                    <div class="asistencia-heading-text">

                        <div class="asistencia-institution-badge">
                            <i class="fas fa-university me-1"></i>
                            UNIVERSIDAD NACIONAL DE INGENIERÍA &bull; FIM
                        </div>

                        <h1 class="asistencia-title">
                            Control de Asistencia Docente
                        </h1>

                        <p class="asistencia-subtitle">
                            Supervisión de sesiones lectivas, registro de firmas y control de asistencia estudiantil.
                        </p>

                    </div>

                </div>


                <!-- =================================================
                     ACCIONES DEL ENCABEZADO
                     ================================================= -->
                <div class="asistencia-heading-actions">

                    <!-- ACTUALIZAR -->
                    <button type="button"
                            class="btn-asistencia-action btn-asistencia-outline"
                            id="btnRecargarAsistencia"
                            title="Recargar datos de asistencia"
                            onclick="cargarDatos()">

                        <i class="fas fa-rotate me-2"></i>

                        <span>
                            Actualizar
                        </span>

                    </button>


                    <!-- EXPORTAR PDF -->
                    <button type="button"
                            class="btn-asistencia-action btn-asistencia-pdf"
                            id="btnExportarPdfAsistencia"
                            title="Exportar reporte de asistencia en PDF">

                        <i class="fas fa-file-pdf me-2"></i>

                        <span>
                            Exportar PDF
                        </span>

                    </button>

                </div>

            </div>


            <!-- =====================================================
                 TARJETAS DE INFORMACIÓN ACADÉMICA
                 ===================================================== -->
            <div class="asistencia-info-grid">

                <!-- SEMESTRE -->
                <div class="info-card card-semestre">

                    <div class="info-icon-wrapper">
                        <i class="fas fa-graduation-cap"></i>
                    </div>

                    <div class="info-body">

                        <span class="info-label">
                            SEMESTRE ACADÉMICO
                        </span>

                        <div class="select-wrapper">

                            <select id="selectSemestre"
                                    class="form-select form-select-sm uni-select">

                                <option value="20262" selected>
                                    2026 - II
                                </option>

                                <option value="20261">
                                    2026 - I
                                </option>

                                <option value="20252">
                                    2025 - II
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <!-- DOCENTE -->
                <div class="info-card card-docente">

                    <div class="info-icon-wrapper">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>

                    <div class="info-body">

                        <span class="info-label">
                            DOCENTE RESPONSABLE
                        </span>

                        <strong class="info-value-text"
                                id="lblDocente">

                            {{ $nombreDocente ?? (Auth::user()->name ?? 'Docente') }}

                        </strong>

                        <small class="info-meta-text">

                            <i class="fas fa-id-badge me-1"></i>

                            Personal Docente &bull; FIM

                        </small>

                    </div>

                </div>


                <!-- CARGA HORARIA -->
                <div class="info-card card-horas">

                    <div class="info-icon-wrapper">
                        <i class="fas fa-business-time"></i>
                    </div>

                    <div class="info-body">

                        <span class="info-label">
                            CARGA HORARIA PROGRAMADA
                        </span>

                        <div class="horas-value-box">

                            <strong class="horas-number"
                                    id="lblCargaHoraria">
                                0
                            </strong>

                            <span class="horas-unit">
                                HRS / SEMANA
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 ESTADÍSTICAS DE ASISTENCIA
                 ===================================================== -->
            <div class="asistencia-stats-grid">

                <div class="stat-card stat-programadas">

                    <div class="stat-top">

                        <span class="stat-title">
                            PROGRAMADAS
                        </span>

                        <i class="fas fa-calendar-check stat-icon"></i>

                    </div>

                    <div class="stat-value"
                         id="statProgramadas">
                        0
                    </div>

                    <span class="stat-desc">
                        Sesiones en ciclo
                    </span>

                </div>


                <div class="stat-card stat-puntuales">

                    <div class="stat-top">

                        <span class="stat-title">
                            PUNTUALES
                        </span>

                        <i class="fas fa-circle-check stat-icon"></i>

                    </div>

                    <div class="stat-value"
                         id="statPuntuales">
                        0
                    </div>

                    <span class="stat-desc">
                        A tiempo (&le; 10 min)
                    </span>

                </div>


                <div class="stat-card stat-tardanzas">

                    <div class="stat-top">

                        <span class="stat-title">
                            TARDANZAS
                        </span>

                        <i class="fas fa-clock-rotate-left stat-icon"></i>

                    </div>

                    <div class="stat-value"
                         id="statTardanzas">
                        0
                    </div>

                    <span class="stat-desc">
                        Ingreso tardío
                    </span>

                </div>


                <div class="stat-card stat-inasistencias">

                    <div class="stat-top">

                        <span class="stat-title">
                            INASISTENCIAS
                        </span>

                        <i class="fas fa-circle-xmark stat-icon"></i>

                    </div>

                    <div class="stat-value"
                         id="statInasistencias">
                        0
                    </div>

                    <span class="stat-desc">
                        Faltas registradas
                    </span>

                </div>


                <div class="stat-card stat-no-aplica">

                    <div class="stat-top">

                        <span class="stat-title">
                            NO APLICA
                        </span>

                        <i class="fas fa-ban stat-icon"></i>

                    </div>

                    <div class="stat-value"
                         id="statNoAplica">
                        0
                    </div>

                    <span class="stat-desc">
                        Exonerado / Feriado
                    </span>

                </div>


                <div class="stat-card stat-proceso">

                    <div class="stat-top">

                        <span class="stat-title">
                            EN PROCESO
                        </span>

                        <i class="fas fa-arrows-rotate stat-icon"></i>

                    </div>

                    <div class="stat-value"
                         id="statProceso">
                        0
                    </div>

                    <span class="stat-desc">
                        Reprogramación
                    </span>

                </div>

            </div>


            <!-- =====================================================
                 CALENDARIO
                 ===================================================== -->
            <div class="calendar-card">

                <!-- CABECERA -->
                <div class="calendar-header">

                    <div class="calendar-title-box">

                        <div class="calendar-title-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>

                        <div>

                            <h2 class="calendar-heading">
                                Programación de Clases y Asistencia
                            </h2>

                            <p class="calendar-subheading">
                                Seleccione una clase para registrar o modificar la asistencia de los alumnos.
                            </p>

                        </div>

                    </div>


                    <!-- NAVEGACIÓN SEMANAL -->
                    <div class="week-navigation-controls">

                        <button id="btnSemanaAnterior"
                                type="button"
                                class="btn-week-nav"
                                title="Semana anterior">

                            <i class="fas fa-chevron-left"></i>

                        </button>


                        <div class="week-display-badge"
                             id="lblSemana">

                            <i class="far fa-calendar me-2 text-primary"></i>

                            <span>
                                Cargando semana...
                            </span>

                        </div>


                        <button id="btnSemanaSiguiente"
                                type="button"
                                class="btn-week-nav"
                                title="Semana siguiente">

                            <i class="fas fa-chevron-right"></i>

                        </button>


                        <button id="btnSemanaActual"
                                type="button"
                                class="btn-week-today"
                                title="Ir a la semana actual">

                            <i class="fas fa-calendar-day me-1"></i>

                            <span>
                                Hoy
                            </span>

                        </button>

                    </div>

                </div>


                <!-- TABLA -->
                <div class="table-wrapper">

                    <table class="asistencia-table"
                           id="tablaAsistencia">

                        <thead>

                            <tr>

                                <th class="hora-header">

                                    <i class="far fa-clock me-1"></i>

                                    HORA

                                </th>


                                <th data-dia="LUNES">

                                    <div class="dia-name">
                                        LUNES
                                    </div>

                                    <span class="dia-code">
                                        LU
                                    </span>

                                </th>


                                <th data-dia="MARTES">

                                    <div class="dia-name">
                                        MARTES
                                    </div>

                                    <span class="dia-code">
                                        MA
                                    </span>

                                </th>


                                <th data-dia="MIÉRCOLES">

                                    <div class="dia-name">
                                        MIÉRCOLES
                                    </div>

                                    <span class="dia-code">
                                        MI
                                    </span>

                                </th>


                                <th data-dia="JUEVES">

                                    <div class="dia-name">
                                        JUEVES
                                    </div>

                                    <span class="dia-code">
                                        JU
                                    </span>

                                </th>


                                <th data-dia="VIERNES">

                                    <div class="dia-name">
                                        VIERNES
                                    </div>

                                    <span class="dia-code">
                                        VI
                                    </span>

                                </th>


                                <th data-dia="SÁBADO"
                                    class="col-sabado">

                                    <div class="dia-name">
                                        SÁBADO
                                    </div>

                                    <span class="dia-code">
                                        SA
                                    </span>

                                </th>


                                <th data-dia="DOMINGO"
                                    class="col-domingo">

                                    <div class="dia-name">
                                        DOMINGO
                                    </div>

                                    <span class="dia-code">
                                        DO
                                    </span>

                                </th>

                            </tr>

                        </thead>


                        <tbody id="tbodyAsistencia">

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5">

                                    <div class="spinner-border text-primary"
                                         role="status">

                                        <span class="visually-hidden">
                                            Cargando...
                                        </span>

                                    </div>

                                    <div class="mt-2 text-muted fw-semibold">

                                        Cargando programación de asistencia...

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- LEYENDA -->
                <div class="legend">

                    <div class="legend-list">

                        <span class="legend-title">

                            <i class="fas fa-tags me-1"></i>

                            Estado de la sesión:

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

                            En proceso

                        </span>

                    </div>


                    <div class="legend-action-hint">

                        <i class="fas fa-arrow-pointer me-1"></i>

                        <span>
                            Haga clic en una clase para tomar lista a los estudiantes.
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         MODAL DE ASISTENCIA
         ========================================================= -->
    <div class="modal fade"
         id="modalAsistencia"
         tabindex="-1"
         aria-labelledby="modalTitulo"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">

            <div class="modal-content asistencia-modal-content">


                <!-- CABECERA -->
                <div class="modal-header modal-header-uni">

                    <div class="modal-header-info">

                        <div class="modal-header-icon">

                            <i class="fas fa-user-check"></i>

                        </div>


                        <div>

                            <div class="modal-header-pretitle">

                                CONTROL ACADÉMICO DE ASISTENCIA

                            </div>


                            <h4 class="modal-title"
                                id="modalTitulo">

                                Asistencia de Alumnos

                            </h4>


                            <div class="modal-subtitle-text"
                                 id="modalSubtitulo">

                                --

                            </div>

                        </div>

                    </div>


                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>


                <!-- CUERPO -->
                <div class="modal-body modal-asistencia-body">


                    <!-- DETALLE DE CLASE -->
                    <div class="modal-class-meta-grid">


                        <div class="class-meta-box">

                            <div class="meta-box-icon">

                                <i class="fas fa-book"></i>

                            </div>

                            <div>

                                <span class="meta-box-label">
                                    ASIGNATURA
                                </span>

                                <strong class="meta-box-value"
                                        id="modalCurso">
                                    --
                                </strong>

                            </div>

                        </div>


                        <div class="class-meta-box">

                            <div class="meta-box-icon">

                                <i class="far fa-clock"></i>

                            </div>

                            <div>

                                <span class="meta-box-label">
                                    HORARIO Y DÍA
                                </span>

                                <strong class="meta-box-value"
                                        id="modalHorario">
                                    --
                                </strong>

                            </div>

                        </div>


                        <div class="class-meta-box">

                            <div class="meta-box-icon">

                                <i class="fas fa-location-dot"></i>

                            </div>

                            <div>

                                <span class="meta-box-label">
                                    AULA ASIGNADA
                                </span>

                                <strong class="meta-box-value"
                                        id="modalAula">
                                    --
                                </strong>

                            </div>

                        </div>


                        <div class="class-meta-box">

                            <div class="meta-box-icon">

                                <i class="fas fa-signal"></i>

                            </div>

                            <div>

                                <span class="meta-box-label">
                                    ESTADO DE SESIÓN
                                </span>

                                <strong class="meta-box-value"
                                        id="modalEstado">
                                    --
                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- HERRAMIENTAS -->
                    <div class="asistencia-actions-toolbar">


                        <!-- BUSCADOR -->
                        <div class="asistencia-search-box">

                            <i class="fas fa-search search-icon"></i>

                            <input type="text"
                                   id="buscarAlumnoAsistenciaInput"
                                   class="form-control form-control-sm input-search-asistencia"
                                   placeholder="Filtrar por apellidos, nombres o código de estudiante...">

                        </div>


                        <!-- ACCIONES -->
                        <div class="asistencia-quick-tools">


                            <button type="button"
                                    class="btn-tool-action btn-mark-all"
                                    id="btnMarcarTodosAsistio"
                                    title="Marcar a todos los alumnos como asistió">

                                <i class="fas fa-check-double me-1 text-success"></i>

                                <span>
                                    Marcar Todos Asistió
                                </span>

                            </button>


                            <button type="button"
                                    class="btn-tool-action btn-export-excel"
                                    id="btnExportarExcelAsistencia"
                                    title="Exportar reporte de asistencia en Excel (.xlsx)">

                                <i class="fas fa-file-excel me-1 text-success"></i>

                                <span>
                                    Excel
                                </span>

                            </button>

                        </div>

                    </div>


                    <!-- INDICADOR DE GUARDADO -->
                    <div class="save-feedback-bar">

                        <div class="save-status-badge"
                             id="saveStatusBadge">

                            <i class="fas fa-circle-check text-success"></i>

                            <span>
                                Las marcas de asistencia se sincronizan automáticamente con MySQL.
                            </span>

                        </div>

                    </div>


                    <!-- LISTA DE ALUMNOS -->
                    <div id="listaAlumnos"
                         class="asistencia-students-container">

                    </div>

                </div>


                <!-- PIE -->
                <div class="modal-footer modal-asistencia-footer">

                    <span class="footer-note">

                        <i class="fas fa-shield-halved text-success me-1"></i>

                        Registro oficial de asistencia estudiantil &bull; FIM UNI.

                    </span>


                    <button type="button"
                            class="btn-modal-cerrar-asistencia"
                            data-bs-dismiss="modal">

                        <i class="fas fa-check me-1"></i>

                        Finalizar y Cerrar

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         ALERTA PERSONALIZADA
         IMPORTANTE:
         ESTÁ FUERA DEL MODAL
         ========================================================= -->
    <div id="alertaAsistencia"
         class="alerta-asistencia-overlay">

        <div class="alerta-asistencia"
             role="alertdialog"
             aria-modal="true"
             aria-labelledby="alertaAsistenciaTitulo"
             aria-describedby="alertaAsistenciaMensaje">


            <div class="alerta-asistencia-icon">

                <i class="fas fa-calendar-check"></i>

            </div>


            <div class="alerta-asistencia-content">

                <h5 id="alertaAsistenciaTitulo">
                    Selecciona una clase
                </h5>

                <p id="alertaAsistenciaMensaje">

                    Primero selecciona una clase del calendario para exportar su asistencia.

                </p>

            </div>


            <button type="button"
                    id="btnCerrarAlertaAsistencia"
                    class="alerta-asistencia-close"
                    aria-label="Cerrar">

                <i class="fas fa-times"></i>

            </button>


            <button type="button"
                    id="btnAceptarAlertaAsistencia"
                    class="alerta-asistencia-button">

                <i class="fas fa-check me-1"></i>

                Entendido

            </button>

        </div>

    </div>


    <!-- =========================================================
         ESTILOS DE LA ALERTA
         ========================================================= -->
    <style>

        .alerta-asistencia-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(15, 23, 42, 0.55);

            backdrop-filter: blur(5px);

            opacity: 0;
            visibility: hidden;

            transition:
                opacity .20s ease,
                visibility .20s ease;
        }


        .alerta-asistencia-overlay.mostrar {
            opacity: 1;
            visibility: visible;
        }


        .alerta-asistencia {
            position: relative;

            width: min(430px, 100%);

            background: #ffffff;

            border-radius: 18px;

            padding: 32px 28px 26px;

            text-align: center;

            box-shadow:
                0 25px 70px rgba(15, 23, 42, .28);

            transform:
                translateY(12px)
                scale(.97);

            transition:
                transform .20s ease;
        }


        .alerta-asistencia-overlay.mostrar
        .alerta-asistencia {

            transform:
                translateY(0)
                scale(1);
        }


        .alerta-asistencia-icon {

            width: 68px;
            height: 68px;

            margin: 0 auto 18px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #fff7ed;

            color: #d97706;

            font-size: 29px;
        }


        .alerta-asistencia-content h5 {

            margin: 0 0 8px;

            color: #1e293b;

            font-size: 20px;

            font-weight: 700;
        }


        .alerta-asistencia-content p {

            margin: 0 auto 24px;

            max-width: 350px;

            color: #64748b;

            font-size: 14px;

            line-height: 1.6;
        }


        .alerta-asistencia-close {

            position: absolute;

            top: 12px;
            right: 12px;

            width: 34px;
            height: 34px;

            border: none;

            border-radius: 50%;

            background: #f8fafc;

            color: #64748b;

            cursor: pointer;

            display: flex;
            align-items: center;
            justify-content: center;

            transition: .2s ease;
        }


        .alerta-asistencia-close:hover {

            background: #f1f5f9;

            color: #1e293b;

        }


        .alerta-asistencia-button {

            border: none;

            border-radius: 10px;

            padding: 10px 24px;

            background: #7b1113;

            color: #ffffff;

            font-weight: 600;

            cursor: pointer;

            transition: .2s ease;
        }


        .alerta-asistencia-button:hover {

            background: #5f0d0f;

            transform: translateY(-1px);

        }


        body.alerta-asistencia-abierta {
            overflow: hidden;
        }

    </style>


    <!-- =========================================================
         CONFIGURACIÓN Y SCRIPTS
         ========================================================= -->
    <script>

        window.asistenciaApi = {

            codDocente: "{{ $codDocente ?? '' }}",

            asistencias: "{{ url('/api/asistencias') }}",

            asistenciaAlumno: "{{ url('/api/asistencias/alumno') }}",

            asistenciasAlumnos: "{{ url('/api/asistencias/alumnos') }}",

            horarios: "{{ url('/api/horarios') }}",

            alumnos: "{{ url('/api/alumnos') }}"

        };


        window.csrfToken = "{{ csrf_token() }}";

    </script>


    <script src="{{ asset('js/asistencia.js') }}"></script>

</x-app-layout>