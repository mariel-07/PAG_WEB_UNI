// Variables globales
const dias = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];
let horariosData = [];
let cargandoDatos = false; // Bandera para evitar bucles infinitos

// Evento inicial cuando carga la página
document.addEventListener("DOMContentLoaded", () => {
    cargarHorariosDesdeJSON();
    
    // Escuchar cambios en el selector de semestre de forma segura
    const selectSemestre = document.getElementById('selectSemestre');
    if (selectSemestre) {
        selectSemestre.addEventListener('change', () => {
            if (!cargandoDatos) {
                cargarHorariosDesdeJSON();
            }
        });
    }
});

// 1. Función para obtener los datos del JSON 
async function cargarHorariosDesdeJSON() {
    if (cargandoDatos) return;
    cargandoDatos = true;

    const selectSemestre = document.getElementById('selectSemestre');
    const valorRaw = selectSemestre ? selectSemestre.value : '20242';
    const semestreSeleccionado = String(valorRaw).replace('-', '').replace('I', '1').replace('II', '2');

    try {
        // ⚠️ CAMBIA ESTA RUTA si tu archivo está en otra carpeta (ej: './json/horario.json', '../json/horario.json', o asset('json/horario.json'))
        const response = await fetch('/json/horario.json');
        
        if (!response.ok) throw new Error('No se pudo cargar el JSON de horarios');
        
        const data = await response.json();
        console.log("Datos crudos del JSON cargados con éxito:", data);
        
        // Filtrar por el semestre seleccionado
        horariosData = data.filter(item => String(item.semestre) === semestreSeleccionado);
        
        if (horariosData.length === 0) {
            console.warn("No hay datos exactos para el semestre, mostrando todo para prueba.");
            horariosData = data; 
        }

        renderizarTablaHorario();

    } catch (error) {
        console.error('Error al cargar los horarios:', error);
    } finally {
        cargandoDatos = false;
    }
}

// 2. Función para renderizar la tabla del horario y calcular la carga horaria
function renderizarTablaHorario() {
    const tbody = document.getElementById('tbodyHorario');
    if (!tbody) return;

    tbody.innerHTML = '';
    let totalHoras = 0;

    // Generar las filas de la grilla de 07:00 a 23:00
    for (let h = 7; h < 23; h++) {
        const horaInicio = h < 10 ? `0${h}` : `${h}`;
        const horaFin = (h + 1) < 10 ? `0${h + 1}` : `${h + 1}`;

        const tr = document.createElement('tr');

        // COLUMNA DE HORA
        const tdHora = document.createElement('td');
        tdHora.className = 'celda-hora';
        tdHora.innerHTML = `
            <div class="hora-inicio">${horaInicio}:00</div>
            <div class="hora-separador">a</div>
            <div class="hora-fin">${horaFin}:00</div>
        `;
        tr.appendChild(tdHora);

        // DÍAS DE LA SEMANA
        dias.forEach(dia => {
            const td = document.createElement('td');

            const clase = horariosData.find(c => {
                const diasMap = {
                    'LU': 'LUNES', 'L': 'LUNES',
                    'MA': 'MARTES', 'M': 'MARTES',
                    'MI': 'MIÉRCOLES', 'X': 'MIÉRCOLES',
                    'JU': 'JUEVES', 'J': 'JUEVES',
                    'VI': 'VIERNES', 'V': 'VIERNES',
                    'SA': 'SÁBADO', 'S': 'SÁBADO',
                    'DO': 'DOMINGO', 'D': 'DOMINGO'
                };
                const diaNormalizado = diasMap[c.dia.toUpperCase()] || c.dia.toUpperCase();
                if (diaNormalizado !== dia) return false;
                
                const partesHora = c.hora.split('-');
                if (partesHora.length < 2) return false;
                
                const inicio = parseInt(partesHora[0], 10);
                const fin = parseInt(partesHora[1], 10);

                return h >= inicio && h < fin;
            });

            if (clase) {
                const partesHora = clase.hora.split('-');
                const inicio = parseInt(partesHora[0], 10);
                const fin = parseInt(partesHora[1], 10);

                if (h === inicio) {
                    const duracion = fin - inicio;
                    td.rowSpan = duracion;
                    totalHoras += duracion;

                    const esEstiloClaro = clase.secCurso === 'F' || clase.secCurso === 'D';

                    td.innerHTML = `
                        <div
                            class="bloque-clase ${esEstiloClaro ? 'bloque-clase-claro' : ''}"
                            onclick='cargarYMostrarAlumnos(${JSON.stringify(clase)})'
                            title="Consultar alumnos matriculados"
                        >
                            <div class="bloque-curso">
                                <i class="fas fa-book-open me-1"></i>
                                ${clase.codCurso || 'CURSO'}
                            </div>
                            <div class="bloque-seccion">
                                Sección ${clase.secCurso || '-'}
                            </div>
                            <div class="bloque-aula">
                                <i class="fas fa-location-dot me-1"></i>
                                ${clase.codAula || 'AULA'}
                            </div>
                            <div class="bloque-tipo">
                                ${clase.teopra || ''}
                            </div>
                            <div class="bloque-alumnos">
                                <i class="fas fa-users me-1"></i>
                                ${clase.alumnos_count || 0} alumnos
                            </div>
                        </div>
                    `;
                } else {
                    return; // Omite celdas del rowspan
                }
            }

            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    }

    // Actualizar la carga horaria total
    const lblCarga = document.getElementById('lblCargaHoraria');
    if (lblCarga) {
        lblCarga.textContent = `${totalHoras} HRS`;
    }
}

// 3. Función corregida para modal de alumnos filtrando por curso, sección y semestre
// 3. Función para modal de alumnos usando el idHorario exacto
async function cargarYMostrarAlumnos(clase) {
    const tituloModal = document.getElementById('modalCursoTitulo');
    if (tituloModal) {
        tituloModal.textContent = `${clase.codCurso}-${clase.secCurso} / ${clase.codAula} | Semestre: ${clase.semestre}`;
    }

    const container = document.getElementById('listaAlumnosContainer');
    if (container) {
        container.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-info"></div></div>';
    }

    const modalElement = document.getElementById('modalAlumnos');
    if (modalElement) {
        const myModal = new bootstrap.Modal(modalElement);
        myModal.show();
    }

    try {
        const resAlumnos = await fetch('/json/alumnos.json');
        if (!resAlumnos.ok) throw new Error('No se pudo cargar el archivo alumnos.json');
        
        const todosLosAlumnos = await resAlumnos.json();
        
        // Filtramos directamente haciendo coincidir el idHorario del bloque de horario con el idHorario del alumno
        const idHorarioBuscado = Number(clase.codHorario);
        const lista = todosLosAlumnos.filter(al => Number(al.idHorario) === idHorarioBuscado);
        const total = lista.length;

        const lblCantidad = document.getElementById('modalCantidadAlumnos');
        if (lblCantidad) {
            lblCantidad.textContent = `Cantidad de alumnos: ${total}`;
        }
        
        if (container) {
            container.innerHTML = '';
            if (lista.length === 0) {
                container.innerHTML = '<p class="text-center text-muted py-3">No hay alumnos matriculados en esta sección.</p>';
                return;
            }

            lista.forEach((al, index) => {
                const item = document.createElement('div');
                item.className = 'p-2 border-bottom d-flex justify-content-between align-items-center';
                item.innerHTML = `
                    <div>
                        <div class="fw-bold text-danger small mb-1">
                            <i class="far fa-user me-1"></i> ${index + 1}. ${al.nombres}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="bg-light px-2 py-1 rounded border text-muted small">Estado: ${al.asistencia}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Código: <strong>${al.codigoEstudiante}</strong></div>
                        <a href="mailto:${al.correo}" class="text-decoration-none small text-dark d-block"><i class="fas fa-envelope me-1"></i>${al.correo}</a>
                        ${al.observacion ? `<span class="badge bg-warning text-dark mt-1">${al.observacion}</span>` : ''}
                    </div>
                `;
                container.appendChild(item);
            });
        }
    } catch (err) {
        console.error('Error al procesar alumnos desde JSON:', err);
        if (container) {
            container.innerHTML = '<p class="text-center text-danger py-3">Error al cargar la lista de alumnos desde el JSON.</p>';
        }
    }


}