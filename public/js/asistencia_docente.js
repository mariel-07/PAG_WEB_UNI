document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = null;
    window.eventosGlobales = [];
    var periodosGlobales = []; // Almacenar periodos para el selector

    if (calendarEl) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'es',
            firstDay: 1,
            allDaySlot: false,
            slotMinTime: '07:00:00',
            slotMaxTime: '22:00:00',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [],
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
    }

    // Inicializar cargas de datos
    cargarDatosCompletosCalendarioYHoy();
    inicializarFechaHoyTexto();

    async function cargarDatosCompletosCalendarioYHoy() {
        try {
            // 1. Cargar periodos
            const resFechas = await fetch('./json/fecha_asistencia.json');
            if (!resFechas.ok) throw new Error('No se pudo cargar fecha_asistencia.json');
            periodosGlobales = await resFechas.json();

            // Poblar el selector de Semestre Académico
            poblarSelectorSemestres(periodosGlobales);

            // 2. Cargar horarios base
            const resHorarios = await fetch('./json/horario.json');
            if (!resHorarios.ok) throw new Error('No se pudo cargar horario.json');
            const horariosBase = await resHorarios.json();

            // 3. Cargar asistencias registradas del docente
            let asistenciasDocenteMap = {};
            try {
                const resAsist = await fetch('./json/asistencia_docente.json');
                if (resAsist.ok) {
                    const dataAsist = await resAsist.json();
                    dataAsist.forEach(item => {
                        let key = `${item.idHorario}_${item.fechaMarcado}`;
                        asistenciasDocenteMap[key] = item;
                    });
                }
            } catch (e) {
                console.warn('No se pudo cargar asistencia_docente.json, se usarán valores por defecto.');
            }

            let eventosGenerados = [];

            const mapDias = {
                'LU': 1, 'L': 1, 'LUNES': 1,
                'MA': 2, 'M': 2, 'MARTES': 2,
                'MI': 3, 'X': 3, 'MIÉRCOLES': 3, 'MIERCOLES': 3,
                'JU': 4, 'J': 4, 'JUEVES': 4,
                'VI': 5, 'V': 5, 'VIERNES': 5,
                'SA': 6, 'S': 6, 'SÁBADO': 6, 'SABADO': 6,
                'DO': 0, 'D': 0, 'DOMINGO': 0
            };

            periodosGlobales.forEach(periodoItem => {
                const fechaInicio = new Date(periodoItem.fechInicioAsistencia + 'T00:00:00');
                const fechaFin = new Date(periodoItem.fechFinalAsistencia + 'T23:59:59');

                horariosBase.forEach((hItem) => {
                    let diaTarget = mapDias[hItem.dia.toUpperCase()];
                    if (diaTarget === undefined) return;

                    let partesHora = hItem.hora.split('-');
                    if (partesHora.length < 2) return;

                    let horaIn = partesHora[0].padStart(2, '0') + ':00:00';
                    let horaOut = partesHora[1].padStart(2, '0') + ':00:00';

                    let currDate = new Date(fechaInicio);
                    let dayOfWeek = currDate.getDay();
                    let distance = (diaTarget + 7 - dayOfWeek) % 7;
                    currDate.setDate(currDate.getDate() + distance);

                    while (currDate <= fechaFin) {
                        let fechaStr = currDate.toISOString().split('T')[0];
                        let asistenciaKey = `${hItem.codHorario}_${fechaStr}`;
                        let datosMarcacion = asistenciasDocenteMap[asistenciaKey] || null;

                        let bgCol = hItem.teopra === 'P' ? '#86efac' : '#c084fc';
                        let borderCol = hItem.teopra === 'P' ? '#4ade80' : '#a855f7';
                        let textCol = hItem.teopra === 'P' ? '#14532d' : '#3b0764';

                        eventosGenerados.push({
                            id: `p${periodoItem.id}_${hItem.codHorario}_${fechaStr}`,
                            title: `${hItem.codCurso}-${hItem.secCurso}-(${hItem.teopra})\n${hItem.codAula}`,
                            start: `${fechaStr}T${horaIn}`,
                            end: `${fechaStr}T${horaOut}`,
                            backgroundColor: bgCol,
                            borderColor: borderCol,
                            textColor: textCol,
                            extendedProps: {
                                periodoId: periodoItem.id,
                                periodo: periodoItem.periodo,
                                curso: `${hItem.codCurso} - ${hItem.secCurso} (${hItem.teopra})`,
                                sesion: String(hItem.codHorario),
                                docente: 'DOCENTE ASIGNADO',
                                horaText: `${partesHora[0]}-${partesHora[1]}`,
                                diaText: fechaStr,
                                teopra: hItem.teopra,
                                aula: hItem.codAula,
                                marcacion: datosMarcacion
                            }
                        });

                        currDate.setDate(currDate.getDate() + 7);
                    }
                });
            });

            window.eventosGlobales = eventosGenerados;
            filtrarEventosPorPeriodoSeleccionado();
            renderizarCursosHoy(eventosGenerados);

        } catch (error) {
            console.error('Error al procesar los datos de asistencia:', error);
        }
    }

    function poblarSelectorSemestres(periodos) {
        const select = document.getElementById('select-semestre');
        if (!select) return;

        select.innerHTML = '';
        periodos.forEach(p => {
            let option = document.createElement('option');
            option.value = p.id;
            option.text = p.periodo;
            if (p.periodoActual === 1) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        select.addEventListener('change', function() {
            filtrarEventosPorPeriodoSeleccionado();
        });
    }

    function filtrarEventosPorPeriodoSeleccionado() {
        const select = document.getElementById('select-semestre');
        const periodoIdSeleccionado = select ? parseInt(select.value) : null;

        let eventosFiltrados = window.eventosGlobales;
        if (periodoIdSeleccionado) {
            eventosFiltrados = window.eventosGlobales.filter(ev => ev.extendedProps.periodoId === periodoIdSeleccionado);
        }

        if (calendar) {
            calendar.removeAllEvents();
            eventosFiltrados.forEach(ev => calendar.addEvent(ev));

            let periodoActivo = periodosGlobales.find(p => p.id === periodoIdSeleccionado) || periodosGlobales[0];
            if (periodoActivo) {
                calendar.gotoDate(periodoActivo.fechInicioAsistencia);
            }
        }
    }

    function inicializarFechaHoyTexto() {
        const hoy = new Date();
        const opciones = { weekday: 'short', day: 'numeric', month: 'short' };
        let fechaFormateada = hoy.toLocaleDateString('es-ES', opciones);
        
        const spanFecha = document.getElementById('fecha-hoy-txt');
        if (spanFecha) {
            spanFecha.innerText = fechaFormateada;
        }
    }

    function renderizarCursosHoy(eventosGenerados) {
        const tbody = document.getElementById('tabla-cursos-hoy');
        if (!tbody) return;

        const hoyStr = new Date().toISOString().split('T')[0];
        const eventosHoy = eventosGenerados.filter(ev => ev.start.startsWith(hoyStr));

        tbody.innerHTML = '';

        if (eventosHoy.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-muted py-3">No se encontraron registros para hoy</td></tr>`;
            return;
        }

        eventosHoy.forEach((ev, index) => {
            const props = ev.extendedProps;
            let marcacion = props.marcacion;

            let hrMarcacion = marcacion ? `${marcacion.horEntrada || 'No reg.'} - ${marcacion.horSalida || 'No reg.'}` : 'No registrada';
            
            let estadoBadge = '<span class="badge bg-secondary">PROGRAMADA</span>';
            if (marcacion) {
                estadoBadge = marcacion.estado === 0 
                    ? '<span class="badge bg-success">PUNTUAL</span>' 
                    : '<span class="badge bg-warning text-dark">TARDANZA/FALTA</span>';
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td class="text-start fw-bold">${props.curso}</td>
                <td>${props.horaText} hrs</td>
                <td>${hrMarcacion}</td>
                <td>${estadoBadge}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.abrirModalPorId('${ev.id}')">
                        <i class="fas fa-eye"></i> Ver
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    window.abrirModalPorId = function(eventId) {
        const evento = window.eventosGlobales.find(e => e.id === eventId);
        if (evento) {
            abrirModalAsistencia(evento);
        }
    };

    window.renderAlumnosExterno = async function(props) {
        const tbodyAlumnos = document.getElementById('lista-alumnos-body');
        
        if (tbodyAlumnos) {
            tbodyAlumnos.innerHTML = `<tr><td colspan="7" class="text-center py-3"><div class="spinner-border text-primary spinner-border-sm"></div> Cargando alumnos...</td></tr>`;
        }
        
        try {
            const response = await fetch('./json/alumnos.json');
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            
            const alumnosData = await response.json();
            const alumnosDelCurso = alumnosData.filter(a => {
                let idAl = String(a.idHorario || a.codHorario || '').trim();
                let idSes = String(props.sesion || '').trim();
                return idAl === idSes;
            });

            if (tbodyAlumnos) {
                tbodyAlumnos.innerHTML = '';

                if (alumnosDelCurso.length === 0) {
                    tbodyAlumnos.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">No hay alumnos para la sesión ${props.sesion}.</td></tr>`;
                    return;
                }

                alumnosDelCurso.forEach((al, index) => {
                    let estadoAsistencia = (al.asistencia || '').toUpperCase();
                    let isChecked = !estadoAsistencia.includes('FALTA');

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center">${index + 1}</td>
                        <td>${al.codigoEstudiante || al.codigo || 'N/A'}</td>
                        <td class="text-start">${al.nombres || al.nombre || ''}</td>
                        <td>${al.seccion || 'A'}</td>
                        <td>${al.responsable || 'DOCENTE'}</td>
                        <td class="text-center"><input type="checkbox" class="form-check-input act-fisica" ${isChecked ? 'checked' : ''}></td>
                        <td class="text-center"><input type="checkbox" class="form-check-input act-virtual" ${isChecked ? 'checked' : ''}></td>
                    `;
                    tbodyAlumnos.appendChild(tr);
                });
            }
        } catch (error) {
            console.error('Fallo crítico al cargar alumnos.json:', error);
            if (tbodyAlumnos) {
                tbodyAlumnos.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-3">Error al cargar alumnos.json.</td></tr>`;
            }
        }
    };

    window.abrirModalAsistencia = function(event) {
        if (event.display === 'background') return;

        const props = event.extendedProps;
        if (props.curso) {
            document.getElementById('modal-sesion-id').innerText = 'SESION N° ' + (props.sesion || 'N/A');
            document.getElementById('modal-docente').innerText = props.docente || 'DOCENTE';
            document.getElementById('modal-curso').innerText = props.curso;
            document.getElementById('modal-dia').innerText = props.diaText || '';
            document.getElementById('modal-hora').innerText = props.horaText || '';
        }

        window.renderAlumnosExterno(props);
    };

    window.guardarAsistencia = function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: '¡Guardado!', text: 'Asistencia guardada con éxito', timer: 1500, showConfirmButton: false });
        } else {
            alert('Asistencia guardada correctamente.');
        }
        var modalEl = document.getElementById('modalAsistencia');
        if(modalEl) {
            var modalInstance = bootstrap.Modal.getInstance(modalEl);
            if(modalInstance) modalInstance.hide();
        }
    };
});