<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asistencia Docente') }}
        </h2>
    </x-slot>

    <!-- FontAwesome & FullCalendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <style>
        .fc-event {
            cursor: pointer;
            border: none !important;
            padding: 4px;
            font-size: 0.85rem;
        }
        .fc-event-main {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .fc-highlight-feriado {
            background-color: #fde8e8 !important;
        }
    </style>

    <div class="container py-4">
        <!-- HEADER INDICADORES DE ESTADO -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h3 class="mb-0 text-primary fw-bold">Asistencia Docente</h3>
                        <span class="text-muted small">Asistencia de clases por día, semana y mes</span>
                    </div>
                    <a href="#" class="text-decoration-none small text-primary fw-bold"><i class="fas fa-eye me-1"></i>Mis asistencias</a>
                </div>
                
                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                    <span class="badge bg-secondary p-2">PROGRAMADAS <span class="badge bg-white text-dark rounded-circle ms-1">2</span></span>
                    <span class="badge bg-success p-2">PUNTUALES <span class="badge bg-white text-dark rounded-circle ms-1">53</span></span>
                    <span class="badge bg-warning text-dark p-2">TARDANZAS <span class="badge bg-white text-dark rounded-circle ms-1">2</span></span>
                    <span class="badge bg-danger p-2">INASISTENCIAS <span class="badge bg-white text-dark rounded-circle ms-1">0</span></span>
                    <span class="badge bg-info text-dark p-2">NO APLICA <span class="badge bg-white text-dark rounded-circle ms-1">11</span></span>
                    <span class="badge bg-primary p-2">EN PROCESO DE CAMBIO <span class="badge bg-white text-dark rounded-circle ms-1">0</span></span>
                </div>

                <div class="fw-bold text-secondary mb-2">Cursos de hoy: <span id="fecha-hoy-txt" class="text-dark">mié. 16 de jul.</span></div>

                <!-- TABLA DE CURSOS DEL DÍA -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0 text-center small">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>N°</th>
                                <th>CURSO</th>
                                <th>HR. CLASE</th>
                                <th>HR. MARCACION</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-cursos-hoy">
                            <tr>
                                <td colspan="6" class="text-muted py-3">No se encontraron registros</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECCIÓN Y CALENDARIO -->
        <div class="mb-2 fw-bold text-danger cursor-pointer" style="cursor: pointer;">
            <i class="fas fa-chevron-right me-1"></i> Mis solicitudes
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- MODAL VISTA DE ASISTENCIA -->
    <div class="modal fade" id="modalAsistencia" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-white border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary">Información de asistencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ENCABEZADO DE LA SESIÓN -->
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="row">
                            <div class="col-md-7">
                                <h6 class="fw-bold text-primary mb-2" id="modal-sesion-id">SESION N° 2510017672</h6>
                                <p class="mb-1 small"><strong>DOCENTE:</strong> <span id="modal-docente">RAMIREZ TENORIO, JULIAN</span></p>
                                <p class="mb-1 small"><strong>CURSO:</strong> <span id="modal-curso">MB545 - C (P) PROGRAMACION ORIENTADA A OBJETOS</span></p>
                                <p class="mb-0 small"><strong>DIA:</strong> <span id="modal-dia">martes, 24 de junio de 2025</span></p>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <p class="mb-1 small"><strong>HORA:</strong> <span id="modal-hora">09-12</span></p>
                                <p class="mb-1 small"><strong>H. ENTRADA:</strong> 09:08 &nbsp;|&nbsp; <strong>H. SALIDA:</strong> 12:00</p>
                                <p class="mb-0 small"><strong>ASISTENCIAS TOMADAS:</strong> <span class="badge bg-primary">1</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN TEMA -->
                    <div class="p-2 mb-3 rounded" style="background-color: #e0f2fe; border-left: 4px solid #0284c7;">
                        <span class="fw-bold text-uppercase small text-dark d-block">Tema:</span>
                        <span class="small text-secondary" id="modal-tema">1. Aplicaciones con Menús, como un sistema de opciones</span>
                    </div>

                    <!-- PESTAÑAS DEL MODAL -->
                    <ul class="nav nav-tabs nav-fill mb-3" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold small" data-bs-toggle="tab" data-bs-target="#tab-alumnos">
                                <i class="fas fa-user-graduate me-1"></i> Asistencia alumnos
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold small text-secondary" data-bs-toggle="tab" data-bs-target="#tab-solicitudes">
                                <i class="fas fa-clipboard-list me-1"></i> Solicitudes
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold small text-secondary" data-bs-toggle="tab" data-bs-target="#tab-acta">
                                <i class="fas fa-file-alt me-1"></i> Acta física
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold small text-secondary" data-bs-toggle="tab" data-bs-target="#tab-control">
                                <i class="fas fa-database me-1"></i> Control de cambios
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- PESTAÑA ALUMNOS -->
                        <div class="tab-pane fade show active" id="tab-alumnos">
                            <div class="d-flex justify-content-end mb-2">
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-file-pdf me-1"></i> Exportar PDF</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle text-center small">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N°</th>
                                            <th>COD.</th>
                                            <th class="text-start">APELLIDOS Y NOMBRES</th>
                                            <th>SEC.</th>
                                            <th>ULT. RESPONSABLE</th>
                                            <th>ACT. FIS.</th>
                                            <th>ACT. VIR.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-alumnos-body">
                                        <!-- Generado dinámicamente en JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- OTRAS PESTAÑAS -->
                        <div class="tab-pane fade py-3 text-center text-muted" id="tab-solicitudes">
                            No existen solicitudes registradas para esta clase.
                        </div>
                        <div class="tab-pane fade py-3 text-center text-muted" id="tab-acta">
                            Sin archivos de acta física adjuntos.
                        </div>
                        <div class="tab-pane fade py-3 text-center text-muted" id="tab-control">
                            Historial de cambios sin novedades.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="guardarAsistencia()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- LÓGICA JAVASCRIPT DE FULLCALENDAR Y MODAL -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Datos Mock de Eventos y Clases
            var eventosClases = [
                {
                    id: '1',
                    title: 'MB545-C-(P)\nCCLAB-1D',
                    start: '2025-06-24T09:00:00',
                    end: '2025-06-24T12:00:00',
                    backgroundColor: '#86efac', // Verde suave
                    borderColor: '#4ade80',
                    textColor: '#14532d',
                    extendedProps: {
                        curso: 'MB545 - C (P) PROGRAMACION ORIENTADA A OBJETOS',
                        sesion: '2510017672',
                        docente: 'RAMIREZ TENORIO, JULIAN',
                        horaText: '09-12',
                        diaText: 'martes, 24 de junio de 2025'
                    }
                },
                {
                    id: '2',
                    title: 'MB545-F-(P)\nCCLAB-2A',
                    start: '2025-06-24T13:00:00',
                    end: '2025-06-24T16:00:00',
                    backgroundColor: '#86efac',
                    borderColor: '#4ade80',
                    textColor: '#14532d',
                    extendedProps: {
                        curso: 'MB545 - F (P) ALGORITMOS Y ESTRUCTURAS DE DATOS',
                        sesion: '2510017673',
                        docente: 'RAMIREZ TENORIO, JULIAN',
                        horaText: '13-16',
                        diaText: 'martes, 24 de junio de 2025'
                    }
                },
                {
                    id: '3',
                    title: 'MB545-I-(P)\nCCLAB-2A',
                    start: '2025-06-25T19:00:00',
                    end: '2025-06-25T22:00:00',
                    backgroundColor: '#86efac',
                    textColor: '#14532d'
                },
                {
                    id: '4',
                    title: 'MB545-I-(P)\nCCLAB-2A',
                    start: '2025-06-27T19:00:00',
                    end: '2025-06-27T22:00:00',
                    backgroundColor: '#c084fc', // Púrpura de la imagen
                    textColor: '#3b0764'
                },
                {
                    title: 'San Pedro y San Pablo',
                    start: '2025-06-29',
                    end: '2025-06-30',
                    display: 'background',
                    color: '#fca5a5'
                }
            ];

            var listadoAlumnosBase = [
                { id: 1, cod: '2021001A', nombre: 'ALVAREZ FLORES, CARLOS', sec: 'C', resp: 'No establecido', fis: true, vir: true },
                { id: 2, cod: '2021002B', nombre: 'BENITEZ GOMEZ, LUCIA', sec: 'C', resp: 'No establecido', fis: false, vir: false },
                { id: 3, cod: '2021003C', nombre: 'CASTRO DIAZ, MARCOS', sec: 'C', resp: 'No establecido', fis: true, vir: false },
                { id: 4, cod: '2021004D', nombre: 'DELGADO LUNA, ANA', sec: 'C', resp: 'No establecido', fis: false, vir: false },
                { id: 5, cod: '2021005E', nombre: 'ESPINOSA MEZA, JORGE', sec: 'C', resp: 'No establecido', fis: false, vir: false }
            ];

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                initialDate: '2025-06-23',
                locale: 'es',
                firstDay: 1,
                allDaySlot: false,
                slotMinTime: '08:00:00',
                slotMaxTime: '22:00:00',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: eventosClases,
                // RENDERIZADO PERSONALIZADO DE EVENTOS CON EL ICONO DE OJO
                eventContent: function(arg) {
                    if (arg.event.display === 'background') return;

                    let container = document.createElement('div');
                    container.className = 'd-flex flex-column justify-content-between h-100 p-1';

                    let titleEl = document.createElement('div');
                    titleEl.className = 'fw-bold';
                    titleEl.style.whiteSpace = 'pre-wrap';
                    titleEl.innerText = arg.event.title;

                    let bottomEl = document.createElement('div');
                    bottomEl.className = 'text-end mt-1';

                    let eyeIcon = document.createElement('i');
                    eyeIcon.className = 'fas fa-eye text-dark cursor-pointer';
                    eyeIcon.title = 'Ver Asistencia';
                    eyeIcon.style.cursor = 'pointer';

                    eyeIcon.addEventListener('click', function(e) {
                        e.stopPropagation();
                        abrirModalAsistencia(arg.event);
                    });

                    bottomEl.appendChild(eyeIcon);
                    container.appendChild(titleEl);
                    container.appendChild(bottomEl);

                    return { domNodes: [container] };
                },
                eventClick: function(info) {
                    abrirModalAsistencia(info.event);
                }
            });

            calendar.render();

            // ABRIR Y POBLAR MODAL
            function abrirModalAsistencia(event) {
                if (event.display === 'background') return;

                const props = event.extendedProps;
                if (props.curso) {
                    document.getElementById('modal-sesion-id').innerText = 'SESION N° ' + (props.sesion || '2510017672');
                    document.getElementById('modal-docente').innerText = props.docente || 'RAMIREZ TENORIO, JULIAN';
                    document.getElementById('modal-curso').innerText = props.curso;
                    document.getElementById('modal-dia').innerText = props.diaText || 'martes, 24 de junio de 2025';
                    document.getElementById('modal-hora').innerText = props.horaText || '09-12';
                }

                renderAlumnos();
                var modal = new bootstrap.Modal(document.getElementById('modalAsistencia'));
                modal.show();
            }

            function renderAlumnos() {
                const tbody = document.getElementById('lista-alumnos-body');
                tbody.innerHTML = '';

                listadoAlumnosBase.forEach((al, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${al.cod}</td>
                        <td class="text-start font-semibold">${al.nombre}</td>
                        <td>${al.sec}</td>
                        <td class="text-muted">${al.resp}</td>
                        <td>
                            ${al.fis 
                                ? '<i class="fas fa-check text-success"></i>' 
                                : '<i class="fas fa-times text-danger"></i>'}
                        </td>
                        <td>
                            <div class="form-check form-switch d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" ${al.vir ? 'checked' : ''}>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            window.guardarAsistencia = function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        text: 'Asistencia guardada con éxito',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    alert('Asistencia guardada correctamente.');
                }
                bootstrap.Modal.getInstance(document.getElementById('modalAsistencia')).hide();
            };
        });
    </script>
</x-app-layout>