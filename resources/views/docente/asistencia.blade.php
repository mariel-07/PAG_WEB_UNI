<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asistencia Docente') }}
        </h2>
    </x-slot>

    <!-- FullCalendar CDN -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <div class="container py-4">
        <!-- Indicadores de Estado de Clases -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h4 class="mb-0 text-primary fw-bold">Asistencia Docente</h4>
                    <span class="text-muted small">Cursos de hoy: <strong>mié. 16 de jul.</strong></span>
                </div>
                
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="badge bg-secondary">PROGRAMADAS <span class="badge bg-white text-dark rounded-circle">2</span></span>
                    <span class="badge bg-success">PUNTUALES <span class="badge bg-white text-dark rounded-circle">53</span></span>
                    <span class="badge bg-warning text-dark">TARDANZAS <span class="badge bg-white text-dark rounded-circle">2</span></span>
                    <span class="badge bg-danger">INASISTENCIAS <span class="badge bg-white text-dark rounded-circle">0</span></span>
                    <span class="badge bg-info text-dark">NO APLICA <span class="badge bg-white text-dark rounded-circle">11</span></span>
                    <span class="badge bg-primary">EN PROCESO DE CAMBIO <span class="badge bg-white text-dark rounded-circle">0</span></span>
                </div>
            </div>
        </div>

        <!-- Contenedor del Calendario -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- MODAL INFORMACIÓN DE ASISTENCIA Y LISTADO DE ALUMNOS -->
    <div class="modal fade" id="modalAsistencia" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Información de asistencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">SESION N° 2510017672</h6>
                            <p class="mb-1"><strong>DOCENTE:</strong> RAMIREZ TENORIO, JULIAN</p>
                            <p class="mb-1"><strong>CURSO:</strong> MB545 - C (P) PROGRAMACION ORIENTADA A OBJETOS</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>DIA:</strong> martes, 24 de junio de 2025</p>
                            <p class="mb-1"><strong>HORA:</strong> 09-12</p>
                            <p class="mb-1"><strong>H. ENTRADA:</strong> 09:08 | <strong>H. SALIDA:</strong> 12:00</p>
                        </div>
                    </div>

                    <!-- Navegación de pestañas del modal -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#tab-alumnos">
                                <i class="fas fa-user-graduate me-1"></i> Asistencia alumnos
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#tab-solicitudes">
                                <i class="fas fa-clipboard-list me-1"></i> Solicitudes
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-alumnos">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N°</th><th>COD.</th><th>APELLIDOS Y NOMBRES</th><th>SEC.</th><th>ULT. RESPONSABLE</th><th>ACT. FIS.</th><th>ACT. VIR.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td><td>2021001A</td><td>ALVAREZ FLORES, CARLOS</td><td>C</td><td>No establecido</td>
                                            <td><i class="fas fa-check text-success"></i></td>
                                            <td>
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" checked>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td><td>2021002B</td><td>BENITEZ GOMEZ, LUCIA</td><td>C</td><td>No establecido</td>
                                            <td><i class="fas fa-times text-danger"></i></td>
                                            <td>
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-solicitudes">
                            <p class="text-muted p-3">No hay solicitudes pendientes para esta sesión.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="Swal.fire('Guardado', 'Asistencia de alumnos registrada', 'success')">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT INICIALIZACIÓN FULLCALENDAR -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '08:00:00',
                slotMaxTime: '22:00:00',
                events: [
                    {
                        title: 'MB545-C-(P) CCLAB-1D',
                        start: '2025-06-24T09:00:00',
                        end: '2025-06-24T12:00:00',
                        color: '#28a745'
                    },
                    {
                        title: 'MB545-I-(P) CCLAB-2A',
                        start: '2025-06-25T19:00:00',
                        end: '2025-06-25T22:00:00',
                        color: '#6f42c1'
                    },
                    {
                        title: 'San Pedro y San Pablo (Feriado)',
                        start: '2025-06-29',
                        end: '2025-06-29',
                        color: '#dc3545',
                        display: 'background'
                    }
                ],
                eventClick: function(info) {
                    var modal = new bootstrap.Modal(document.getElementById('modalAsistencia'));
                    modal.show();
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>