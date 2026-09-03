// Variables globales
const dias = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];
let horariosData = [];
let alumnosDataCache = []; // Almacén en caché para los alumnos
let cargandoDatos = false; // Bandera para evitar bucles infinitos

// Variables temporales para el modal activo (para exportar)
window.currentAlumnosModal = [];
window.currentClaseModal = null;

// Evento inicial cuando carga la página
document.addEventListener("DOMContentLoaded", () => {
    cargarDatosCompletos();
    
    // Escuchar cambios en el selector de semestre de forma segura
    const selectSemestre = document.getElementById('selectSemestre');
    if (selectSemestre) {
        selectSemestre.addEventListener('change', () => {
            if (!cargandoDatos) {
                cargarDatosCompletos();
            }
        });
    }
});

// 1. Función principal para obtener ambos JSONs (Horarios y Alumnos) por adelantado
async function cargarDatosCompletos() {
    if (cargandoDatos) return;
    cargandoDatos = true;

    const selectSemestre = document.getElementById('selectSemestre');
    const valorRaw = selectSemestre ? selectSemestre.value : '20261';
    const semestreSeleccionado = String(valorRaw).replace('-', '').replace('I', '1').replace('II', '2');

    try {
        const urlHorario = window.urlHorarioJson || 'json/horario.json';
        const urlAlumnos = window.urlAlumnosJson || 'json/alumnos.json';

        // Hacemos fetch de ambos archivos en paralelo para mayor velocidad
        const [resHorarios, resAlumnos] = await Promise.all([
            fetch(urlHorario),
            fetch(urlAlumnos)
        ]);
        
        if (!resHorarios.ok) throw new Error('No se pudo cargar el JSON de horarios');
        if (!resAlumnos.ok) throw new Error('No se pudo cargar el JSON de alumnos');
        
        const dataHorarios = await resHorarios.json();
        alumnosDataCache = await resAlumnos.json();
        
        // Filtrar por el semestre seleccionado
        horariosData = dataHorarios.filter(item => String(item.semestre) === semestreSeleccionado);
        
        if (horariosData.length === 0) {
            console.warn("No hay datos exactos para el semestre, mostrando todo para prueba.");
            horariosData = dataHorarios; 
        }

        renderizarTablaHorario();

    } catch (error) {
        console.error('Error al cargar los datos:', error);
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

                    // Calculamos la cantidad real de alumnos usando la caché cargada
                    const idHorarioBuscado = Number(clase.codHorario);
                    const cantidadRealAlumnos = alumnosDataCache.filter(al => Number(al.idHorario) === idHorarioBuscado).length;

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
                                ${cantidadRealAlumnos} alumnos
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

// 3. Función para modal de alumnos usando el idHorario exacto
async function cargarYMostrarAlumnos(clase) {
    window.currentClaseModal = clase; // Guardamos la clase actual para exportar

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
        let todosLosAlumnos = alumnosDataCache;
        if (!todosLosAlumnos || todosLosAlumnos.length === 0) {
            const urlAlumnos = window.urlAlumnosJson || 'json/alumnos.json';
            const resAlumnos = await fetch(urlAlumnos);
            if (!resAlumnos.ok) throw new Error('No se pudo cargar el archivo alumnos.json');
            todosLosAlumnos = await resAlumnos.json();
            alumnosDataCache = todosLosAlumnos;
        }
        
        // Filtramos y guardamos en variable global para los botones de descarga
        const idHorarioBuscado = Number(clase.codHorario);
        window.currentAlumnosModal = todosLosAlumnos.filter(al => Number(al.idHorario) === idHorarioBuscado);
        const total = window.currentAlumnosModal.length;

        const lblCantidad = document.getElementById('modalCantidadAlumnos');
        if (lblCantidad) {
            lblCantidad.textContent = `Cantidad de alumnos: ${total}`;
        }
        
        if (container) {
            container.innerHTML = '';
            if (window.currentAlumnosModal.length === 0) {
                container.innerHTML = '<p class="text-center text-muted py-3">No hay alumnos matriculados en esta sección.</p>';
                return;
            }

            window.currentAlumnosModal.forEach((al, index) => {
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

// 4. Funciones de Exportación (PDF y Excel)
function exportarExcelAlumnos() {
    if (!window.currentAlumnosModal || window.currentAlumnosModal.length === 0) {
        alert("No hay alumnos para exportar en esta sección.");
        return;
    }

    let csvContent = "data:text/csv;charset=utf-8,\uFEFF"; // BOM para caracteres especiales en Excel
    csvContent += "N°,Codigo,Nombres,Correo,Asistencia,Observacion\r\n";

    window.currentAlumnosModal.forEach((al, index) => {
        const fila = [
            index + 1,
            `"${al.codigoEstudiante || ''}"`,
            `"${al.nombres || ''}"`,
            `"${al.correo || ''}"`,
            `"${al.asistencia || ''}"`,
            `"${al.observacion || ''}"`
        ].join(",");
        csvContent += fila + "\r\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `lista_alumnos_${window.currentClaseModal?.codCurso || 'curso'}_Seccion_${window.currentClaseModal?.secCurso || ''}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportarPDFAlumnos() {
    if (!window.currentAlumnosModal || window.currentAlumnosModal.length === 0) {
        alert("No hay alumnos para exportar en esta sección.");
        return;
    }

    const clase = window.currentClaseModal || {};
    const ventanaImpresion = window.open('', '_blank');
    
    let html = `
        <html>
            <head>
                <title>Lista de Alumnos - ${clase.codCurso}-${clase.secCurso}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 25px; color: #333; }
                    h2 { color: #800000; margin-bottom: 5px; }
                    .info { margin-bottom: 20px; font-size: 14px; color: #555; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; font-size: 13px; }
                    th { background-color: #f4f4f4; color: #333; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                </style>
            </head>
            <body>
                <h2>Curso: ${clase.codCurso} - Sección ${clase.secCurso}</h2>
                <div class="info">
                    <strong>Aula:</strong> ${clase.codAula} | <strong>Semestre:</strong> ${clase.semestre} | <strong>Total Alumnos:</strong> ${window.currentAlumnosModal.length}
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Código</th>
                            <th>Nombres y Apellidos</th>
                            <th>Correo</th>
                            <th>Asistencia</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    window.currentAlumnosModal.forEach((al, index) => {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>${al.codigoEstudiante || ''}</td>
                <td>${al.nombres || ''}</td>
                <td>${al.correo || ''}</td>
                <td>${al.asistencia || ''}</td>
                <td>${al.observacion || ''}</td>
            </tr>
        `;
    });

    html += `
                    </tbody>
                </table>
                <script>
                    window.onload = function() {
                        window.print();
                    }
                </script>
            </body>
        </html>
    `;

    ventanaImpresion.document.write(html);
    ventanaImpresion.document.close();
}