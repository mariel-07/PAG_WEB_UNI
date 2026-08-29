<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asistencia Docente') }}
        </h2>
    </x-slot>

<!-- FontAwesome, FullCalendar & CSS Externo -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/asistencia_docente.css') }}" />

<div class="container py-4">
    
    <!-- TÍTULO Y BOTÓN SUPERIOR (Estilo Horario Docente) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.5rem; letter-spacing: -0.5px;">Asistencia Docente</h3>
            <p class="text-muted small mb-0">Consulte y registre la asistencia de clases por día, semana y mes.</p>
        </div>
        <a href="#" class="btn btn-outline-danger btn-sm px-3 shadow-sm d-flex align-items-center gap-1">
            <i class="fas fa-eye"></i> Mis asistencias
        </a>
    </div>

    <!-- TARJETAS DE INDICADORES SUPERIORES -->
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white">
                <div class="card-body py-2">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                        <span class="badge bg-secondary px-3 py-2 fw-normal">PROGRAMADAS <span class="badge bg-white text-dark rounded-circle ms-1">2</span></span>
                        <span class="badge bg-success px-3 py-2 fw-normal">PUNTUALES <span class="badge bg-white text-dark rounded-circle ms-1">53</span></span>
                        <span class="badge bg-warning text-dark px-3 py-2 fw-normal">TARDANZAS <span class="badge bg-white text-dark rounded-circle ms-1">2</span></span>
                        <span class="badge bg-danger px-3 py-2 fw-normal">INASISTENCIAS <span class="badge bg-white text-dark rounded-circle ms-1">0</span></span>
                        <span class="badge bg-info text-dark px-3 py-2 fw-normal">NO APLICA <span class="badge bg-white text-dark rounded-circle ms-1">11</span></span>
                        <span class="badge px-3 py-2 fw-normal" style="background-color: #1e3a8a; color: white;">EN PROCESO DE CAMBIO <span class="badge bg-white text-dark rounded-circle ms-1">0</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- TARJETAS DE INDICADORES SUPERIORES CON SEMESTRE ACADÉMICO -->

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-graduation-cap text-primary me-2"></i>
                <span class="text-uppercase small fw-bold text-muted">Semestre Académico</span>
            </div>
            <select id="select-semestre" class="form-select form-select-sm fw-bold text-dark">
                <!-- Se llena dinámicamente con JS -->
            </select>
            <span class="badge bg-success-subtle text-success mt-2 w-fit small" style="width: fit-content;">DEFINITIVO</span>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3 p-2 bg-white h-100 d-flex flex-column justify-content-center">
            <div class="card-body py-1">
                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                    <span class="badge bg-secondary px-3 py-2 fw-normal">PROGRAMADAS <span class="badge bg-white text-dark rounded-circle ms-1">2</span></span>
                    <span class="badge bg-success px-3 py-2 fw-normal">PUNTUALES <span class="badge bg-white text-dark rounded-circle ms-1">53</span></span>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-normal">TARDANZAS <span class="badge bg-white text-dark rounded-circle ms-1">2</span></span>
                    <span class="badge bg-danger px-3 py-2 fw-normal">INASISTENCIAS <span class="badge bg-white text-dark rounded-circle ms-1">0</span></span>
                    <span class="badge bg-info text-dark px-3 py-2 fw-normal">NO APLICA <span class="badge bg-white text-dark rounded-circle ms-1">11</span></span>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- SECCIÓN DE CURSOS DEL DÍA -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark m-0" style="font-size: 1.1rem;">
                    <i class="fas fa-calendar-day text-primary me-2"></i> Cursos de hoy: <span id="fecha-hoy-txt" class="text-muted fw-normal">mié. 16 de jul.</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center small">
                        <thead class="table-light text-secondary border-bottom">
                            <tr>
                                <th class="py-3">N°</th>
                                <th class="py-3 text-start ps-4">CURSO</th>
                                <th class="py-3">HR. CLASE</th>
                                <th class="py-3">HR. MARCACION</th>
                                <th class="py-3">ESTADO</th>
                                <th class="py-3">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-cursos-hoy">
                            <tr>
                                <td colspan="6" class="text-muted py-4">No se encontraron registros</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- SECCIÓN DE CALENDARIO GENERAL -->
    <div class="mb-3 fw-bold text-dark d-flex align-items-center" style="cursor: pointer;">
        <i class="fas fa-chevron-right text-primary me-2"></i> Mis solicitudes
    </div>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body bg-white p-4">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- MODAL VISTA DE ASISTENCIA -->
<div class="modal fade" id="modalAsistencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark">Información de asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- ENCABEZADO DE LA SESIÓN -->
                <div class="p-3 rounded-3 mb-3 bg-white border shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-7 border-end">
                            <h6 class="fw-bold text-primary mb-2" id="modal-sesion-id">SESION N° 2510017672</h6>
                            <p class="mb-1 small text-secondary"><strong>DOCENTE:</strong> <span id="modal-docente" class="text-dark">RAMIREZ TENORIO, JULIAN</span></p>
                            <p class="mb-1 small text-secondary"><strong>CURSO:</strong> <span id="modal-curso" class="text-dark">MB545 - C (P) PROGRAMACION ORIENTADA A OBJETOS</span></p>
                            <p class="mb-0 small text-secondary"><strong>DIA:</strong> <span id="modal-dia" class="text-dark">martes, 24 de junio de 2025</span></p>
                        </div>
                        <div class="col-md-5 ps-md-4 mt-3 mt-md-0">
                            <p class="mb-1 small text-secondary"><strong>HORA:</strong> <span id="modal-hora" class="text-dark">09-12</span></p>
                            <p class="mb-1 small text-secondary"><strong>H. ENTRADA:</strong> <span class="text-dark">09:08</span> &nbsp;|&nbsp; <strong>H. SALIDA:</strong> <span class="text-dark">12:00</span></p>
                            <p class="mb-0 small text-secondary"><strong>ASISTENCIAS TOMADAS:</strong> <span class="badge bg-primary">1</span></p>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN TEMA -->
                <div class="p-3 mb-4 rounded-3 bg-light border-start border-4 border-primary shadow-sm">
                    <span class="fw-bold text-uppercase small text-primary d-block mb-1">Tema:</span>
                    <span class="small text-secondary" id="modal-tema">1. Aplicaciones con Menús, como un sistema de opciones</span>
                </div>

                <!-- PESTAÑAS DEL MODAL -->
                <ul class="nav nav-tabs nav-fill border-bottom mb-3" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold small py-2" data-bs-toggle="tab" data-bs-target="#tab-alumnos">
                            <i class="fas fa-user-graduate me-1 text-primary"></i> Asistencia alumnos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold small text-secondary py-2" data-bs-toggle="tab" data-bs-target="#tab-solicitudes">
                            <i class="fas fa-clipboard-list me-1"></i> Solicitudes
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold small text-secondary py-2" data-bs-toggle="tab" data-bs-target="#tab-acta">
                            <i class="fas fa-file-alt me-1"></i> Acta física
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold small text-secondary py-2" data-bs-toggle="tab" data-bs-target="#tab-control">
                            <i class="fas fa-database me-1"></i> Control de cambios
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- PESTAÑA ALUMNOS -->
                    <div class="tab-pane fade show active" id="tab-alumnos">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-outline-danger btn-sm shadow-sm"><i class="fas fa-file-pdf me-1"></i> Exportar PDF</button>
                        </div>
                        <div class="table-responsive border rounded-3 overflow-hidden shadow-sm">
                            <table class="table table-striped table-hover align-middle text-center small mb-0">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th class="py-3">N°</th>
                                        <th class="py-3">COD.</th>
                                        <th class="py-3 text-start ps-3">APELLIDOS Y NOMBRES</th>
                                        <th class="py-3">SEC.</th>
                                        <th class="py-3">ULT. RESPONSABLE</th>
                                        <th class="py-3">ACT. FIS.</th>
                                        <th class="py-3">ACT. VIR.</th>
                                    </tr>
                                </thead>
                                <tbody id="lista-alumnos-body">
                                    <!-- Generado dinámicamente en JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- OTRAS PESTAÑAS -->
                    <div class="tab-pane fade py-4 text-center text-muted" id="tab-solicitudes">
                        No existen solicitudes registradas para esta clase.
                    </div>
                    <div class="tab-pane fade py-4 text-center text-muted" id="tab-acta">
                        Sin archivos de acta física adjuntos.
                    </div>
                    <div class="tab-pane fade py-4 text-center text-muted" id="tab-control">
                        Historial de cambios sin novedades.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btn-sm px-4" onclick="guardarAsistencia()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.appUrl = "{{ asset('') }}";
</script>

<script src="{{ asset('js/fecha_asistencia.js') }}"></script>

</x-app-layout>