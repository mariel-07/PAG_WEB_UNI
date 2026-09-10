<x-app-layout>

    <!-- =========================================================
         LIBRERÍAS Y ESTILOS
         ========================================================= -->
    <link rel="stylesheet"
          href="{{ asset('css/horario.css') }}">

    <!-- =========================================================
         CONTENEDOR PRINCIPAL
         ========================================================= -->
    <div class="horario-page">

        <!-- =====================================================
             ENCABEZADO INSTITUCIONAL DEL MÓDULO
             ===================================================== -->
        <div class="horario-header-block">

            <div class="horario-header-left">
                <div class="horario-header-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>

                <div class="horario-header-text">
                    <div class="horario-institution-badge">
                        <i class="fas fa-university me-1"></i>
                        UNIVERSIDAD NACIONAL DE INGENIERÍA &bull; FIM
                    </div>
                    <h1 class="horario-title">
                        Horario Académico Docente
                    </h1>
                    <p class="horario-subtitle">
                        Distribución horaria semanal de asignaturas, aulas y nómina de alumnos matriculados.
                    </p>
                </div>
            </div>

            <div class="horario-header-actions">
                <button type="button"
                        class="btn-horario-action btn-horario-outline"
                        id="btnRecargarHorario"
                        title="Actualizar datos del horario"
                        onclick="cargarDatosCompletos()">
                    <i class="fas fa-rotate me-2"></i>
                    <span>Actualizar</span>
                </button>

                <button type="button"
                        class="btn-horario-action btn-horario-pdf"
                        onclick="window.print()"
                        title="Exportar horario en formato PDF o imprimir">
                    <i class="fas fa-file-pdf me-2"></i>
                    <span>Exportar Horario (PDF)</span>
                </button>
            </div>

        </div>

        <!-- =====================================================
             TARJETAS DE INFORMACIÓN Y MÉTRICAS (KPIs)
             ===================================================== -->
        <div class="horario-kpi-grid">

            <!-- KPI 1: SEMESTRE ACADÉMICO -->
            <div class="horario-kpi-card kpi-semestre">
                <div class="kpi-icon-wrapper">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">SEMESTRE ACADÉMICO</span>
                    <div class="kpi-select-wrapper">
                        <select id="selectSemestre" class="form-select form-select-sm uni-select-semestre">
                            <option value="20262" selected>2026 - II</option>
                            <option value="20261">2026 - I</option>
                            <option value="20252">2025 - II</option>
                        </select>
                    </div>
                    <div class="kpi-status-badge status-oficial">
                        <i class="fas fa-circle-check"></i>
                        <span>Programación Oficial</span>
                    </div>
                </div>
            </div>

            <!-- KPI 2: DOCENTE ASIGNADO -->
            <div class="horario-kpi-card kpi-docente">
                <div class="kpi-icon-wrapper">
                    <i class="fas fa-chalkboard-user"></i>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">DOCENTE ASIGNADO</span>
                    <strong class="kpi-docente-nombre" id="lblDocenteNombre">
                        {{ Auth::user()->name ?? 'Docente' }}
                    </strong>
                    <small class="kpi-docente-meta">
                        <i class="fas fa-id-badge me-1"></i>
                        Personal Docente Ordinario &bull; FIM
                    </small>
                </div>
            </div>

            <!-- KPI 3: CARGA HORARIA SEMANAL -->
            <div class="horario-kpi-card kpi-horas">
                <div class="kpi-icon-wrapper">
                    <i class="fas fa-business-time"></i>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">CARGA HORARIA SEMANAL</span>
                    <div class="kpi-value-container">
                        <strong class="kpi-value-number" id="lblCargaHoraria">
                            0
                        </strong>
                        <span class="kpi-value-unit">HRS</span>
                    </div>
                    <small class="kpi-docente-meta">
                        <i class="fas fa-clock me-1"></i>
                        Horas lectivas semanales
                    </small>
                </div>
            </div>

            <!-- KPI 4: ASIGNATURAS PROGRAMADAS -->
            <div class="horario-kpi-card kpi-cursos">
                <div class="kpi-icon-wrapper">
                    <i class="fas fa-book-bookmark"></i>
                </div>
                <div class="kpi-body">
                    <span class="kpi-label">ASIGNATURAS / SECCIONES</span>
                    <div class="kpi-value-container">
                        <strong class="kpi-value-number" id="lblTotalCursos">
                            0
                        </strong>
                        <span class="kpi-value-unit">GRUPOS</span>
                    </div>
                    <small class="kpi-docente-meta">
                        <i class="fas fa-layer-group me-1"></i>
                        Cursos activos en periodo
                    </small>
                </div>
            </div>

        </div>

        <!-- =====================================================
             TARJETA PRINCIPAL DEL HORARIO
             ===================================================== -->
        <div class="horario-main-card">

            <!-- BARRA SUPERIOR DE LA TARJETA -->
            <div class="horario-card-toolbar">

                <div class="toolbar-title-group">
                    <div class="toolbar-calendar-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <h2 class="toolbar-title">
                            Programación Semanal de Clases
                        </h2>
                        <p class="toolbar-subtitle">
                            Distribución por día lectivo y bloque horario (07:00 a 22:00)
                        </p>
                    </div>
                </div>

                <!-- CONTROLES Y FILTROS -->
                <div class="toolbar-controls-group">

                    <!-- FILTRO POR CURSO -->
                    <div class="filter-wrapper">
                        <label for="filtroCurso" class="filter-label">
                            <i class="fas fa-filter me-1"></i> Filtrar curso:
                        </label>
                        <select id="filtroCurso" class="form-select form-select-sm uni-filter-select">
                            <option value="TODOS">Todas las asignaturas</option>
                        </select>
                    </div>

                    <!-- SELECTOR DE VISTA -->
                    <div class="view-toggle-pills">
                        <button type="button"
                                class="btn-toggle-view active"
                                id="btnVistaCuadricula"
                                onclick="cambiarVista('cuadricula')"
                                title="Ver horario en cuadrícula semanal">
                            <i class="fas fa-table-cells me-1"></i>
                            <span>Cuadrícula</span>
                        </button>
                        <button type="button"
                                class="btn-toggle-view"
                                id="btnVistaLista"
                                onclick="cambiarVista('lista')"
                                title="Ver listado consolidado de asignaturas">
                            <i class="fas fa-list-ul me-1"></i>
                            <span>Lista detallada</span>
                        </button>
                    </div>

                </div>

            </div>

            <!-- =================================================
                 VISTA 1: CUADRÍCULA SEMANAL DE HORARIO
                 ================================================= -->
            <div id="contenedorVistaCuadricula" class="vista-seccion">

                <div class="horario-table-wrapper">
                    <table class="table tbl-horario mb-0" id="tablaHorario">
                        <thead>
                            <tr>
                                <th class="col-hora">
                                    <div class="col-header-hora">
                                        <i class="far fa-clock me-1"></i>
                                        HORA
                                    </div>
                                </th>
                                <th class="col-dia">
                                    <div class="dia-title">LUNES</div>
                                    <span class="dia-subtitle">LU</span>
                                </th>
                                <th class="col-dia">
                                    <div class="dia-title">MARTES</div>
                                    <span class="dia-subtitle">MA</span>
                                </th>
                                <th class="col-dia">
                                    <div class="dia-title">MIÉRCOLES</div>
                                    <span class="dia-subtitle">MI</span>
                                </th>
                                <th class="col-dia">
                                    <div class="dia-title">JUEVES</div>
                                    <span class="dia-subtitle">JU</span>
                                </th>
                                <th class="col-dia">
                                    <div class="dia-title">VIERNES</div>
                                    <span class="dia-subtitle">VI</span>
                                </th>
                                <th class="col-dia col-sabado">
                                    <div class="dia-title">SÁBADO</div>
                                    <span class="dia-subtitle">SA</span>
                                </th>
                                <th class="col-dia col-domingo">
                                    <div class="dia-title">DOMINGO</div>
                                    <span class="dia-subtitle">DO</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody id="tbodyHorario">
                            <!-- Filas generadas dinámicamente mediante horario.js -->
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando horario...</span>
                                    </div>
                                    <div class="mt-2 text-muted fw-semibold">
                                        Cargando programación académica...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- =================================================
                     LEYENDA PEDAGÓGICA Y ESTADOS
                     ================================================= -->
                <div class="horario-legend-bar">
                    <div class="legend-items">
                        <span class="legend-title">
                            <i class="fas fa-tags me-1"></i>
                            Referencia de sesiones:
                        </span>

                        <div class="legend-badge legend-teoria">
                            <span class="legend-dot dot-teoria"></span>
                            <span>Teoría (T)</span>
                        </div>

                        <div class="legend-badge legend-practica">
                            <span class="legend-dot dot-practica"></span>
                            <span>Práctica (P)</span>
                        </div>

                        <div class="legend-badge legend-laboratorio">
                            <span class="legend-dot dot-laboratorio"></span>
                            <span>Laboratorio (L)</span>
                        </div>

                        <div class="legend-badge legend-virtual">
                            <span class="legend-dot dot-virtual"></span>
                            <span>Aula Virtual</span>
                        </div>
                    </div>

                    <div class="legend-hint">
                        <i class="fas fa-mouse-pointer me-1"></i>
                        <span>Haga clic en cualquier clase para consultar y exportar la lista de alumnos matriculados.</span>
                    </div>
                </div>

            </div>

            <!-- =================================================
                 VISTA 2: LISTA RESUMEN DE ASIGNATURAS (CONSOLIDADO)
                 ================================================= -->
            <div id="contenedorVistaLista" class="vista-seccion d-none">
                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle table-resumen-cursos mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Sección</th>
                                <th>Aula</th>
                                <th>Día</th>
                                <th>Horario</th>
                                <th class="text-center">Alumnos</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyListaCursos">
                            <!-- Generado dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- =====================================================
             PIE DE PÁGINA INFORMATIVO
             ===================================================== -->
        <div class="row g-3 mt-2">
            <div class="col-lg-8">
                <div class="horario-note-card">
                    <div class="note-icon">
                        <i class="fas fa-circle-info"></i>
                    </div>
                    <div>
                        <h4 class="note-title">Información Importante del Horario</h4>
                        <p class="note-description">
                            La asignación de aulas y horarios corresponde a la carga académica oficial registrada
                            en la base de datos de la Facultad de Ingeniería Mecánica. Cualquier variación o reprogramación
                            debe gestionarse mediante la Dirección de Escuela Profesional correspondiente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="horario-system-card">
                    <div class="system-icon">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div>
                        <span class="system-label">ESTADO DEL SERVICIO</span>
                        <div class="system-status">
                            <span class="status-indicator-dot"></span>
                            <strong>Sistema en línea &bull; Datos sincronizados</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- =========================================================
         MODAL PROFESIONAL: LISTA DE ALUMNOS MATRICULADOS
         ========================================================= -->
    <div class="modal fade"
         id="modalAlumnos"
         tabindex="-1"
         aria-labelledby="modalCursoTitulo"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">

            <div class="modal-content modal-alumnos-content">

                <!-- ENCABEZADO DEL MODAL -->
                <div class="modal-alumnos-header">
                    <div class="modal-header-main">
                        <div class="modal-header-icon">
                            <i class="fas fa-users-rectangle"></i>
                        </div>
                        <div>
                            <div class="modal-header-pretitle">
                                NÓMINA OFICIAL DE ESTUDIANTES
                            </div>
                            <h3 class="modal-title" id="modalCursoTitulo">
                                Curso / Sección
                            </h3>
                            <div class="modal-header-meta" id="modalCursoMeta">
                                <span class="badge-modal-meta" id="badgeModalAula">
                                    <i class="fas fa-location-dot me-1"></i> Aula: --
                                </span>
                                <span class="badge-modal-meta" id="badgeModalHorario">
                                    <i class="far fa-clock me-1"></i> Horario: --
                                </span>
                                <span class="badge-modal-meta" id="badgeModalSemestre">
                                    <i class="fas fa-graduation-cap me-1"></i> Semestre: --
                                </span>
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
                <div class="modal-body modal-alumnos-body">

                    <!-- BARRA DE ACCIONES Y BUSCADOR -->
                    <div class="modal-toolbar-card">
                        <div class="modal-search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   id="buscarAlumnoInput"
                                   class="form-control form-control-sm input-search-student"
                                   placeholder="Buscar por nombre, apellido o código de alumno...">
                            <button type="button"
                                    id="btnLimpiarBusqueda"
                                    class="btn-clear-search d-none"
                                    onclick="limpiarBusquedaAlumno()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-export-buttons">
                            <button type="button"
                                    class="btn-export btn-export-excel"
                                    onclick="exportarExcelAlumnos()"
                                    title="Exportar nómina a formato Excel (.csv)">
                                <i class="fas fa-file-excel me-1 text-success"></i>
                                <span>Excel</span>
                            </button>

                            <button type="button"
                                    class="btn-export btn-export-pdf"
                                    onclick="exportarPDFAlumnos()"
                                    title="Exportar nómina a formato PDF o imprimir">
                                <i class="fas fa-file-pdf me-1 text-danger"></i>
                                <span>PDF</span>
                            </button>
                        </div>
                    </div>

                    <!-- CONTADOR / RESUMEN -->
                    <div class="modal-student-count-row">
                        <div class="count-badge">
                            <i class="fas fa-user-graduate me-1 text-primary"></i>
                            <span>Total matriculados:</span>
                            <strong id="modalCantidadAlumnos">0</strong>
                        </div>
                        <div class="count-filtered text-muted d-none" id="modalFiltradosCount">
                            Mostrando: <strong id="lblAlumnosFiltrados">0</strong>
                        </div>
                    </div>

                    <!-- CONTENEDOR DE LA LISTA DE ALUMNOS -->
                    <div id="listaAlumnosContainer" class="students-list-wrapper">
                        <!-- Generado dinámicamente por horario.js -->
                    </div>

                </div>

                <!-- PIE DEL MODAL -->
                <div class="modal-footer modal-alumnos-footer">
                    <span class="modal-footer-note">
                        <i class="fas fa-circle-check text-success me-1"></i>
                        Datos sincronizados desde la base de datos de matrícula UNI.
                    </span>
                    <button type="button"
                            class="btn-modal-close"
                            data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>

            </div>

        </div>

    </div>

    <!-- =========================================================
         CONFIGURACIÓN Y SCRIPTS
         ========================================================= -->
    <script>
        window.urlHorarioApi = "{{ url('/api/horarios') }}";
        window.urlAlumnosApi = "{{ url('/api/alumnos') }}";
    </script>
    <script src="{{ asset('js/horario.js') }}"></script>

</x-app-layout>