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

                <div class="asistencia-heading-actions">
                    <button type="button"
                            class="btn-asistencia-action btn-asistencia-outline"
                            id="btnRecargarAsistencia"
                            title="Recargar datos de asistencia"
                            onclick="cargarDatos()">
                        <i class="fas fa-rotate me-2"></i>
                        <span>Actualizar</span>
                    </button>

                    <button type="button"
                            class="btn-asistencia-action btn-asistencia-pdf"
                            onclick="window.print()"
                            title="Exportar reporte en formato PDF o imprimir">
                        <i class="fas fa-file-pdf me-2"></i>
                        <span>Exportar PDF</span>
                    </button>
                </div>

            </div>

            <!-- =====================================================
                 TARJETAS DE INFORMACIÓN (ACADÉMICA)
                 ===================================================== -->
            <div class="asistencia-info-grid">

                <!-- SEMESTRE -->
                <div class="info-card card-semestre">
                    <div class="info-icon-wrapper">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="info-body">
                        <span class="info-label">SEMESTRE ACADÉMICO</span>
                        <div class="select-wrapper">
                            <select id="selectSemestre" class="form-select form-select-sm uni-select">
                                <option value="20262" selected>2026 - II</option>
                                <option value="20261">2026 - I</option>
                                <option value="20252">2025 - II</option>
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
                        <span class="info-label">DOCENTE RESPONSABLE</span>
                        <strong class="info-value-text" id="lblDocente">
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
                        <span class="info-label">CARGA HORARIA PROGRAMADA</span>
                        <div class="horas-value-box">
                            <strong class="horas-number" id="lblCargaHoraria">0</strong>
                            <span class="horas-unit">HRS / SEMANA</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- =====================================================
                 ESTADÍSTICAS DE ASISTENCIA DEL DOCENTE (KPIS)
                 ===================================================== -->
            <div class="asistencia-stats-grid">

                <div class="stat-card stat-programadas">
                    <div class="stat-top">
                        <span class="stat-title">PROGRAMADAS</span>
                        <i class="fas fa-calendar-check stat-icon"></i>
                    </div>
                    <div class="stat-value" id="statProgramadas">0</div>
                    <span class="stat-desc">Sesiones en ciclo</span>
                </div>

                <div class="stat-card stat-puntuales">
                    <div class="stat-top">
                        <span class="stat-title">PUNTUALES</span>
                        <i class="fas fa-circle-check stat-icon"></i>
                    </div>
                    <div class="stat-value" id="statPuntuales">0</div>
                    <span class="stat-desc">A tiempo (&le; 10 min)</span>
                </div>

                <div class="stat-card stat-tardanzas">
                    <div class="stat-top">
                        <span class="stat-title">TARDANZAS</span>
                        <i class="fas fa-clock-rotate-left stat-icon"></i>
                    </div>
                    <div class="stat-value" id="statTardanzas">0</div>
                    <span class="stat-desc">Ingreso tardío</span>
                </div>

                <div class="stat-card stat-inasistencias">
                    <div class="stat-top">
                        <span class="stat-title">INASISTENCIAS</span>
                        <i class="fas fa-circle-xmark stat-icon"></i>
                    </div>
                    <div class="stat-value" id="statInasistencias">0</div>
                    <span class="stat-desc">Faltas registradas</span>
                </div>

                <div class="stat-card stat-no-aplica">
                    <div class="stat-top">
                        <span class="stat-title">NO APLICA</span>
                        <i class="fas fa-ban stat-icon"></i>
                    </div>
                    <div class="stat-value" id="statNoAplica">0</div>
                    <span class="stat-desc">Exonerado / Feriado</span>
                </div>

                <div class="stat-card stat-proceso">
                    <div class="stat-top">
                        <span class="stat-title">EN PROCESO</span>
                        <i class="fas fa-arrows-rotate stat-icon"></i>
                    </div>
                    <div class="stat-value" id="statProceso">0</div>
                    <span class="stat-desc">Reprogramación</span>
                </div>

            </div>

            <!-- =====================================================
                 CALENDARIO SEMANAL DE ASISTENCIA
                 ===================================================== -->
            <div class="calendar-card">

                <!-- CABECERA DEL CALENDARIO Y NAVEGACIÓN -->
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

                    <!-- CONTROLES DE NAVEGACIÓN SEMANAL -->
                    <div class="week-navigation-controls">
                        <button id="btnSemanaAnterior"
                                type="button"
                                class="btn-week-nav"
                                title="Semana anterior">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <div class="week-display-badge" id="lblSemana">
                            <i class="far fa-calendar me-2 text-primary"></i>
                            <span>Cargando semana...</span>
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
                            <span>Hoy</span>
                        </button>
                    </div>

                </div>

                <!-- TABLA DEL CALENDARIO -->
                <div class="table-wrapper">
                    <table class="asistencia-table" id="tablaAsistencia">
                        <thead>
                            <tr>
                                <th class="hora-header">
                                    <i class="far fa-clock me-1"></i>
                                    HORA
                                </th>
                                <th data-dia="LUNES">
                                    <div class="dia-name">LUNES</div>
                                    <span class="dia-code">LU</span>
                                </th>
                                <th data-dia="MARTES">
                                    <div class="dia-name">MARTES</div>
                                    <span class="dia-code">MA</span>
                                </th>
                                <th data-dia="MIÉRCOLES">
                                    <div class="dia-name">MIÉRCOLES</div>
                                    <span class="dia-code">MI</span>
                                </th>
                                <th data-dia="JUEVES">
                                    <div class="dia-name">JUEVES</div>
                                    <span class="dia-code">JU</span>
                                </th>
                                <th data-dia="VIERNES">
                                    <div class="dia-name">VIERNES</div>
                                    <span class="dia-code">VI</span>
                                </th>
                                <th data-dia="SÁBADO" class="col-sabado">
                                    <div class="dia-name">SÁBADO</div>
                                    <span class="dia-code">SA</span>
                                </th>
                                <th data-dia="DOMINGO" class="col-domingo">
                                    <div class="dia-name">DOMINGO</div>
                                    <span class="dia-code">DO</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody id="tbodyAsistencia">
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <div class="mt-2 text-muted fw-semibold">
                                        Cargando programación de asistencia...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- LEYENDA PEDAGÓGICA -->
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
                        <span>Haga clic en una clase para tomar lista a los estudiantes.</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- =========================================================
         MODAL PROFESIONAL: REGISTRO Y TOMA DE ASISTENCIA
         ========================================================= -->
    <div class="modal fade"
         id="modalAsistencia"
         tabindex="-1"
         aria-labelledby="modalTitulo"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">

            <div class="modal-content asistencia-modal-content">

                <!-- CABECERA DEL MODAL -->
                <div class="modal-header modal-header-uni">
                    <div class="modal-header-info">
                        <div class="modal-header-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <div class="modal-header-pretitle">
                                CONTROL ACADÉMICO DE ASISTENCIA
                            </div>
                            <h4 class="modal-title" id="modalTitulo">
                                Asistencia de Alumnos
                            </h4>
                            <div class="modal-subtitle-text" id="modalSubtitulo">
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

                <!-- CUERPO DEL MODAL -->
                <div class="modal-body modal-asistencia-body">

                    <!-- TARJETAS DE DETALLE DE LA CLASE -->
                    <div class="modal-class-meta-grid">
                        <div class="class-meta-box">
                            <div class="meta-box-icon"><i class="fas fa-book"></i></div>
                            <div>
                                <span class="meta-box-label">ASIGNATURA</span>
                                <strong class="meta-box-value" id="modalCurso">--</strong>
                            </div>
                        </div>

                        <div class="class-meta-box">
                            <div class="meta-box-icon"><i class="far fa-clock"></i></div>
                            <div>
                                <span class="meta-box-label">HORARIO Y DÍA</span>
                                <strong class="meta-box-value" id="modalHorario">--</strong>
                            </div>
                        </div>

                        <div class="class-meta-box">
                            <div class="meta-box-icon"><i class="fas fa-location-dot"></i></div>
                            <div>
                                <span class="meta-box-label">AULA ASIGNADA</span>
                                <strong class="meta-box-value" id="modalAula">--</strong>
                            </div>
                        </div>

                        <div class="class-meta-box">
                            <div class="meta-box-icon"><i class="fas fa-signal"></i></div>
                            <div>
                                <span class="meta-box-label">ESTADO DE SESIÓN</span>
                                <strong class="meta-box-value" id="modalEstado">--</strong>
                            </div>
                        </div>
                    </div>

                    <!-- BARRA DE HERRAMIENTAS Y ACCIONES RÁPIDAS -->
                    <div class="asistencia-actions-toolbar">

                        <!-- BUSCADOR -->
                        <div class="asistencia-search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   id="buscarAlumnoAsistenciaInput"
                                   class="form-control form-control-sm input-search-asistencia"
                                   placeholder="Filtrar por apellidos, nombres o código de estudiante...">
                        </div>

                        <!-- BOTONES DE MARCA RÁPIDA -->
                        <div class="asistencia-quick-tools">
                            <button type="button"
                                    class="btn-tool-action btn-mark-all"
                                    id="btnMarcarTodosAsistio"
                                    title="Marcar a todos los alumnos como asistió">
                                <i class="fas fa-check-double me-1 text-success"></i>
                                <span>Marcar Todos Asistió</span>
                            </button>

                            <button type="button"
                                    class="btn-tool-action btn-export-excel"
                                    id="btnExportarExcelAsistencia"
                                    title="Exportar reporte de asistencia en Excel (.csv)">
                                <i class="fas fa-file-excel me-1 text-success"></i>
                                <span>Excel</span>
                            </button>
                        </div>

                    </div>

                    <!-- INDICADOR DE GUARDADO EN VIVO -->
                    <div class="save-feedback-bar">
                        <div class="save-status-badge" id="saveStatusBadge">
                            <i class="fas fa-circle-check text-success"></i>
                            <span>Las marcas de asistencia se sincronizan automáticamente con MySQL.</span>
                        </div>
                    </div>

                    <!-- LISTA / TABLA DE ALUMNOS (GENERADA DINÁMICAMENTE) -->
                    <div id="listaAlumnos" class="asistencia-students-container">
                        <!-- Generado por asistencia.js -->
                    </div>

                </div>

                <!-- PIE DEL MODAL -->
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
