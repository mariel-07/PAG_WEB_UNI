/* ============================================================
   HORARIO ACADÉMICO DOCENTE - CONTROLADOR JAVASCRIPT
   UNIVERSIDAD NACIONAL DE INGENIERÍA - FACULTAD DE INGENIERÍA MECÁNICA
   ============================================================ */

/* ============================================================
   CONFIGURACIÓN GENERAL Y MAPAS
   ============================================================ */

const DIAS_SEMANA = [
    'LUNES',
    'MARTES',
    'MIÉRCOLES',
    'JUEVES',
    'VIERNES',
    'SÁBADO',
    'DOMINGO'
];

const DIAS_MAP = {
    'LU': 'LUNES',
    'L': 'LUNES',
    'LUNES': 'LUNES',

    'MA': 'MARTES',
    'MAR': 'MARTES',
    'M': 'MARTES',
    'MARTES': 'MARTES',

    'MI': 'MIÉRCOLES',
    'MIE': 'MIÉRCOLES',
    'X': 'MIÉRCOLES',
    'MIERCOLES': 'MIÉRCOLES',
    'MIÉRCOLES': 'MIÉRCOLES',

    'JU': 'JUEVES',
    'J': 'JUEVES',
    'JUEVES': 'JUEVES',

    'VI': 'VIERNES',
    'V': 'VIERNES',
    'VIERNES': 'VIERNES',

    'SA': 'SÁBADO',
    'SAB': 'SÁBADO',
    'S': 'SÁBADO',
    'SABADO': 'SÁBADO',
    'SÁBADO': 'SÁBADO',

    'DO': 'DOMINGO',
    'DOM': 'DOMINGO',
    'D': 'DOMINGO',
    'DOMINGO': 'DOMINGO'
};

/* ============================================================
   VARIABLES GLOBALES DE ESTADO
   ============================================================ */

let horariosData = [];
let alumnosPorHorario = {};
let alumnosPorCurso = {};
let cargandoDatos = false;
let vistaActiva = 'cuadricula';
let filtroCursoSeleccionado = 'TODOS';

window.currentAlumnosModal = [];
window.currentClaseModal = null;

/* ============================================================
   INICIALIZACIÓN
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
    inicializarEventos();
    cargarDatosCompletos();
});

function inicializarEventos() {
    // Cambio de semestre
    const selectSemestre = document.getElementById('selectSemestre');
    if (selectSemestre) {
        selectSemestre.addEventListener('change', () => {
            if (!cargandoDatos) {
                cargarDatosCompletos();
            }
        });
    }

    // Filtro por curso
    const filtroCurso = document.getElementById('filtroCurso');
    if (filtroCurso) {
        filtroCurso.addEventListener('change', (e) => {
            filtroCursoSeleccionado = e.target.value;
            renderizarTablaHorario();
            renderizarListaCursos();
        });
    }

    // Buscador en vivo de alumnos dentro del modal
    const buscarAlumnoInput = document.getElementById('buscarAlumnoInput');
    if (buscarAlumnoInput) {
        buscarAlumnoInput.addEventListener('input', (e) => {
            filtrarAlumnosModal(e.target.value);
        });
    }
}

/* ============================================================
   CARGA PRINCIPAL DE DATOS (API LARAVEL / MYSQL)
   ============================================================ */

async function cargarDatosCompletos() {
    if (cargandoDatos) return;
    cargandoDatos = true;

    mostrarEstadoCargando();

    const selectSemestre = document.getElementById('selectSemestre');
    const semestre = selectSemestre ? selectSemestre.value : '20262';

    try {
        const urlHorarioApi = window.urlHorarioApi || '/api/horarios';
        const urlParams = new URLSearchParams({ semestre: semestre });
        const endpoint = `${urlHorarioApi}?${urlParams.toString()}`;

        const res = await fetch(endpoint, {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            cache: 'no-store'
        });

        if (!res.ok) {
            throw new Error(`Error del servidor (${res.status}): No se pudo obtener el horario.`);
        }

        const data = await res.json();

        if (!data || data.success !== true) {
            throw new Error(data?.error || 'La respuesta del servidor no contiene datos válidos.');
        }

        horariosData = Array.isArray(data.data) ? data.data : [];

        // Limpieza de caché por cambio de semestre
        alumnosPorHorario = {};
        alumnosPorCurso = {};

        // Carga en segundo plano de la nómina de alumnos para cada horario
        await precargarAlumnosDeHorarios(semestre);

        // Actualizar selector de filtros de cursos
        actualizarSelectFiltroCursos();

        // Renderizar vistas
        renderizarTablaHorario();
        renderizarListaCursos();

    } catch (error) {
        console.error('Error al cargar datos del horario:', error);
        mostrarErrorHorario(error.message);
    } finally {
        cargandoDatos = false;
    }
}

/* ============================================================
   PRECARGA Y CACHÉ DE ALUMNOS MATRICULADOS
   ============================================================ */

async function precargarAlumnosDeHorarios(semestre) {
    const urlAlumnosApi = window.urlAlumnosApi || '/api/alumnos';

    const promesas = horariosData.map(async (clase) => {
        const codHorario = Number(clase.codHorario);
        const codCurso = clase.codCurso;
        const secCurso = clase.secCurso;
        const claveCurso = `${codCurso}_${secCurso}_${semestre}`;

        if (alumnosPorCurso[claveCurso]) {
            alumnosPorHorario[codHorario] = alumnosPorCurso[claveCurso];
            return alumnosPorCurso[claveCurso];
        }

        try {
            const params = new URLSearchParams({
                codCurso: codCurso,
                secCurso: secCurso,
                semestre: semestre
            });

            const res = await fetch(`${urlAlumnosApi}?${params.toString()}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });

            if (!res.ok) return [];

            const resJson = await res.json();
            const alumnos = (resJson && resJson.success && Array.isArray(resJson.alumnos))
                ? resJson.alumnos
                : [];

            alumnosPorCurso[claveCurso] = alumnos;
            alumnosPorHorario[codHorario] = alumnos;
            return alumnos;

        } catch (e) {
            console.warn(`No se pudo cargar alumnos para ${codCurso}-${secCurso}:`, e);
            alumnosPorHorario[codHorario] = [];
            return [];
        }
    });

    await Promise.all(promesas);
}

/* ============================================================
   ACTUALIZAR SELECTOR DE FILTRO DE ASIGNATURAS
   ============================================================ */

function actualizarSelectFiltroCursos() {
    const filtroSelect = document.getElementById('filtroCurso');
    if (!filtroSelect) return;

    const cursosUnicos = new Set();
    horariosData.forEach(h => {
        if (h.codCurso) cursosUnicos.add(h.codCurso.trim().toUpperCase());
    });

    const valorActual = filtroSelect.value;
    filtroSelect.innerHTML = '<option value="TODOS">Todas las asignaturas</option>';

    Array.from(cursosUnicos).sort().forEach(codigo => {
        const opt = document.createElement('option');
        opt.value = codigo;
        opt.textContent = codigo;
        filtroSelect.appendChild(opt);
    });

    if (cursosUnicos.has(valorActual)) {
        filtroSelect.value = valorActual;
        filtroCursoSeleccionado = valorActual;
    } else {
        filtroSelect.value = 'TODOS';
        filtroCursoSeleccionado = 'TODOS';
    }
}

/* ============================================================
   CONSTRUCCIÓN DE MAPA HORARIO Y FILAS
   ============================================================ */

function construirMapaHorario(horarios) {
    const mapa = {};
    DIAS_SEMANA.forEach(dia => { mapa[dia] = []; });

    horarios.forEach(clase => {
        const diaNormal = normalizarDia(clase.dia);
        const rango = obtenerRangoHorario(clase.hora);

        if (!diaNormal || !rango || !mapa[diaNormal]) {
            return;
        }

        mapa[diaNormal].push({
            ...clase,
            _inicio: rango.inicio,
            _fin: rango.fin
        });
    });

    // Ordenar por hora de inicio dentro de cada día
    DIAS_SEMANA.forEach(dia => {
        mapa[dia].sort((a, b) => a._inicio - b._inicio);
    });

    return mapa;
}

function generarFilasHorario() {
    const filas = [];
    // Bloques horarios de 07:00 a 22:00 (15 bloques de 1 hora)
    for (let min = 7 * 60; min < 22 * 60; min += 60) {
        filas.push({
            inicio: min,
            fin: min + 60
        });
    }
    return filas;
}

/* ============================================================
   RENDERIZADO DE LA TABLA CUADRÍCULA
   ============================================================ */

function renderizarTablaHorario() {
    const tbody = document.getElementById('tbodyHorario');
    if (!tbody) return;

    tbody.innerHTML = '';

    // Filtrado de horarios si hay filtro activo
    const listaHorarios = (filtroCursoSeleccionado === 'TODOS')
        ? horariosData
        : horariosData.filter(h => (h.codCurso || '').trim().toUpperCase() === filtroCursoSeleccionado);

    const mapa = construirMapaHorario(listaHorarios);
    const filas = generarFilasHorario();

    let totalMinutosSemana = 0;
    const ocupadas = {};
    DIAS_SEMANA.forEach(dia => { ocupadas[dia] = new Set(); });

    // Cálculo de estadísticas globales de horariosData (sin filtrar para que el KPI sea el total)
    let totalCargaMinutos = 0;
    const cursosUnicosSet = new Set();

    horariosData.forEach(clase => {
        const rango = obtenerRangoHorario(clase.hora);
        if (rango) {
            totalCargaMinutos += (rango.fin - rango.inicio);
        }
        if (clase.codCurso) {
            cursosUnicosSet.add(`${clase.codCurso}_${clase.secCurso || ''}`);
        }
    });

    actualizarKPIs(totalCargaMinutos / 60, cursosUnicosSet.size);

    // Si no hay horarios registrados
    if (horariosData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-calendar-xmark fa-2x mb-3 text-secondary"></i>
                        <div class="fw-bold fs-6">No se encontraron clases programadas</div>
                        <small>No hay registros de horarios para el semestre seleccionado.</small>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    filas.forEach((fila, indiceFila) => {
        const tr = document.createElement('tr');

        // Celda de hora
        const tdHora = document.createElement('td');
        tdHora.className = 'celda-hora';
        tdHora.innerHTML = `
            <span class="hora-inicio">${formatearHora(fila.inicio)}</span>
            <span class="hora-separador">a</span>
            <span class="hora-fin">${formatearHora(fila.fin)}</span>
        `;
        tr.appendChild(tdHora);

        // Columnas de días
        DIAS_SEMANA.forEach(dia => {
            if (ocupadas[dia].has(indiceFila)) {
                return; // Ocupado por rowspan anterior
            }

            const td = document.createElement('td');
            td.className = 'celda-dia';

            if (dia === 'SÁBADO' || dia === 'DOMINGO') {
                td.classList.add('celda-fin-semana');
            }

            // Buscar si alguna clase inicia en este bloque
            const clase = (mapa[dia] || []).find(c => c._inicio === fila.inicio);

            if (clase) {
                const duracionMin = clase._fin - clase._inicio;
                const duracionFilas = Math.max(1, Math.ceil(duracionMin / 60));

                td.rowSpan = duracionFilas;

                for (let i = 0; i < duracionFilas; i++) {
                    ocupadas[dia].add(indiceFila + i);
                }

                // Generar bloque visual enriquecido
                const bloque = crearElementoBloqueClase(clase);
                td.appendChild(bloque);
            }

            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    });
}

/* ============================================================
   CREACIÓN DEL ELEMENTO VISUAL DEL BLOQUE DE CLASE
   ============================================================ */

function crearElementoBloqueClase(clase) {
    const bloque = document.createElement('div');
    bloque.className = 'bloque-clase';

    const estilo = determinarEstiloClase(clase);
    bloque.classList.add(estilo.claseCss);

    const codHorario = Number(clase.codHorario);
    const alumnos = alumnosPorHorario[codHorario] || [];
    const totalAlumnos = alumnos.length;

    bloque.title = `${clase.codCurso} - Sec ${clase.secCurso || ''} (${estilo.tipoTexto}) | Clic para ver alumnos matriculados`;

    bloque.innerHTML = `
        <div class="bloque-cabecera">
            <div class="bloque-curso-codigo">
                <i class="fas fa-book me-1"></i>
                <span>${escapeHTML(clase.codCurso || 'CURSO')}</span>
            </div>
            <div class="bloque-badges">
                <span class="badge-seccion">Sec ${escapeHTML(clase.secCurso || '-')}</span>
                <span class="badge-tipo">${escapeHTML(estilo.tipoCorto)}</span>
            </div>
        </div>

        <div class="bloque-cuerpo">
            <div class="bloque-aula" title="Aula asignada">
                <i class="${estilo.esVirtual ? 'fas fa-laptop' : 'fas fa-location-dot'}"></i>
                <span>${escapeHTML(clase.codAula || 'POR ASIGNAR')}</span>
            </div>
            <div class="bloque-hora" title="Horario programado">
                <i class="far fa-clock"></i>
                <span>${formatearRangoTexto(clase.hora)}</span>
            </div>
        </div>

        <div class="bloque-pie">
            <span class="bloque-alumnos-pill">
                <i class="fas fa-user-graduate"></i>
                ${totalAlumnos} alum.
            </span>
            <span class="bloque-action-icon" title="Ver relación de estudiantes">
                <i class="fas fa-arrow-up-right-from-square"></i>
            </span>
        </div>
    `;

    bloque.addEventListener('click', () => {
        cargarYMostrarAlumnos(clase);
    });

    return bloque;
}

function determinarEstiloClase(clase) {
    const teopra = String(clase.teopra || '').trim().toUpperCase();
    const aula = String(clase.codAula || '').trim().toUpperCase();
    const esVirtual = aula.includes('VIRTUAL') || aula.includes('REMOTO');

    if (esVirtual) {
        return {
            claseCss: 'bloque-virtual',
            tipoTexto: 'Aula Virtual',
            tipoCorto: 'VIR',
            esVirtual: true
        };
    }

    if (teopra === 'T' || teopra === 'TEORIA' || teopra === 'TEORÍA') {
        return {
            claseCss: 'bloque-teoria',
            tipoTexto: 'Teoría',
            tipoCorto: 'TEO',
            esVirtual: false
        };
    }

    if (teopra === 'L' || teopra === 'LAB' || teopra === 'LABORATORIO') {
        return {
            claseCss: 'bloque-laboratorio',
            tipoTexto: 'Laboratorio',
            tipoCorto: 'LAB',
            esVirtual: false
        };
    }

    if (teopra === 'P' || teopra === 'PRACTICA' || teopra === 'PRÁCTICA') {
        return {
            claseCss: 'bloque-practica',
            tipoTexto: 'Práctica',
            tipoCorto: 'PRÁC',
            esVirtual: false
        };
    }

    return {
        claseCss: 'bloque-institucional',
        tipoTexto: 'Sesión',
        tipoCorto: 'CLASE',
        esVirtual: false
    };
}

/* ============================================================
   RENDERIZADO DE LA VISTA EN LISTA
   ============================================================ */

function renderizarListaCursos() {
    const tbody = document.getElementById('tbodyListaCursos');
    if (!tbody) return;

    tbody.innerHTML = '';

    const lista = (filtroCursoSeleccionado === 'TODOS')
        ? horariosData
        : horariosData.filter(h => (h.codCurso || '').trim().toUpperCase() === filtroCursoSeleccionado);

    if (lista.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">
                    <i class="fas fa-filter-circle-xmark fa-2x mb-2 text-secondary"></i>
                    <div>No hay asignaturas que coincidan con el filtro seleccionado.</div>
                </td>
            </tr>
        `;
        return;
    }

    lista.forEach((clase, index) => {
        const tr = document.createElement('tr');
        const codHorario = Number(clase.codHorario);
        const alumnos = alumnosPorHorario[codHorario] || [];
        const estilo = determinarEstiloClase(clase);

        tr.innerHTML = `
            <td class="text-muted fw-bold">${index + 1}</td>
            <td>
                <span class="curso-code-badge">${escapeHTML(clase.codCurso || '--')}</span>
            </td>
            <td>
                <span class="badge ${estilo.claseCss === 'bloque-teoria' ? 'bg-primary' : (estilo.claseCss === 'bloque-practica' ? 'bg-success' : 'bg-warning text-dark')}">
                    ${escapeHTML(estilo.tipoTexto)}
                </span>
            </td>
            <td><strong>${escapeHTML(clase.secCurso || '-')}</strong></td>
            <td>
                <i class="${estilo.esVirtual ? 'fas fa-laptop text-info' : 'fas fa-location-dot text-danger'} me-1"></i>
                ${escapeHTML(clase.codAula || 'Por asignar')}
            </td>
            <td><strong>${escapeHTML(normalizarDia(clase.dia))}</strong></td>
            <td class="font-monospace">${escapeHTML(formatearRangoTexto(clase.hora))}</td>
            <td class="text-center">
                <span class="badge bg-light text-dark border px-2 py-1">
                    <i class="fas fa-users me-1 text-primary"></i> ${alumnos.length}
                </span>
            </td>
            <td class="text-center">
                <button type="button" class="btn-consultar-alumnos" onclick='cargarYMostrarAlumnos(${JSON.stringify(clase)})'>
                    <i class="fas fa-users-viewfinder"></i> Alumnos
                </button>
            </td>
        `;

        tbody.appendChild(tr);
    });
}

/* ============================================================
   CONTROL DE VISTAS (CUADRÍCULA VS LISTA)
   ============================================================ */

function cambiarVista(tipo) {
    vistaActiva = tipo;
    const contCuadricula = document.getElementById('contenedorVistaCuadricula');
    const contLista = document.getElementById('contenedorVistaLista');
    const btnCuadricula = document.getElementById('btnVistaCuadricula');
    const btnLista = document.getElementById('btnVistaLista');

    if (tipo === 'cuadricula') {
        contCuadricula?.classList.remove('d-none');
        contLista?.classList.add('d-none');
        btnCuadricula?.classList.add('active');
        btnLista?.classList.remove('active');
    } else {
        contCuadricula?.classList.add('d-none');
        contLista?.classList.remove('d-none');
        btnCuadricula?.classList.remove('active');
        btnLista?.classList.add('active');
        renderizarListaCursos();
    }
}

/* ============================================================
   ACTUALIZACIÓN DE MÉTRICAS KPIS
   ============================================================ */

function actualizarKPIs(horasLectivas, totalAsignaturas) {
    const lblHoras = document.getElementById('lblCargaHoraria');
    if (lblHoras) {
        lblHoras.textContent = Number.isInteger(horasLectivas)
            ? horasLectivas
            : horasLectivas.toFixed(1);
    }

    const lblCursos = document.getElementById('lblTotalCursos');
    if (lblCursos) {
        lblCursos.textContent = totalAsignaturas;
    }
}

/* ============================================================
   MODAL DE ALUMNOS MATRICULADOS
   ============================================================ */

async function cargarYMostrarAlumnos(clase) {
    window.currentClaseModal = clase;

    // Actualizar encabezados del modal
    const tituloModal = document.getElementById('modalCursoTitulo');
    if (tituloModal) {
        tituloModal.textContent = `${clase.codCurso || 'CURSO'} - Sección ${clase.secCurso || '-'}`;
    }

    const badgeAula = document.getElementById('badgeModalAula');
    if (badgeAula) {
        badgeAula.innerHTML = `<i class="fas fa-location-dot me-1"></i> Aula: ${escapeHTML(clase.codAula || 'Por asignar')}`;
    }

    const badgeHorario = document.getElementById('badgeModalHorario');
    if (badgeHorario) {
        badgeHorario.innerHTML = `<i class="far fa-clock me-1"></i> ${normalizarDia(clase.dia)}: ${escapeHTML(formatearRangoTexto(clase.hora))}`;
    }

    const badgeSemestre = document.getElementById('badgeModalSemestre');
    if (badgeSemestre) {
        badgeSemestre.innerHTML = `<i class="fas fa-graduation-cap me-1"></i> Semestre: ${escapeHTML(String(clase.semestre || ''))}`;
    }

    // Limpiar input de búsqueda
    const inputSearch = document.getElementById('buscarAlumnoInput');
    if (inputSearch) inputSearch.value = '';

    const btnClear = document.getElementById('btnLimpiarBusqueda');
    if (btnClear) btnClear.classList.add('d-none');

    const filtradosCount = document.getElementById('modalFiltradosCount');
    if (filtradosCount) filtradosCount.classList.add('d-none');

    // Contenedor en estado cargando
    const container = document.getElementById('listaAlumnosContainer');
    if (container) {
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border" style="color: var(--uni-maroon);" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div class="mt-3 text-muted fw-semibold">Consultando nómina de alumnos matriculados...</div>
            </div>
        `;
    }

    // Abrir modal con Bootstrap
    const modalEl = document.getElementById('modalAlumnos');
    if (modalEl && typeof bootstrap !== 'undefined') {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    try {
        const codHorario = Number(clase.codHorario);
        let alumnos = alumnosPorHorario[codHorario];

        if (!alumnos) {
            const urlAlumnosApi = window.urlAlumnosApi || '/api/alumnos';
            const params = new URLSearchParams({
                codCurso: clase.codCurso,
                secCurso: clase.secCurso,
                semestre: clase.semestre || '20262'
            });

            const res = await fetch(`${urlAlumnosApi}?${params.toString()}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });

            if (!res.ok) throw new Error(`HTTP Error ${res.status}`);

            const data = await res.json();
            alumnos = (data && data.success && Array.isArray(data.alumnos)) ? data.alumnos : [];
            alumnosPorHorario[codHorario] = alumnos;
        }

        window.currentAlumnosModal = alumnos;

        const countTotal = document.getElementById('modalCantidadAlumnos');
        if (countTotal) countTotal.textContent = alumnos.length;

        renderizarListaAlumnosModal(alumnos);

    } catch (err) {
        console.error('Error al obtener lista de alumnos:', err);
        if (container) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-circle-exclamation fa-2x text-danger mb-2"></i>
                    <div class="fw-bold text-danger">No se pudo cargar la lista de alumnos</div>
                    <small class="text-muted">${escapeHTML(err.message)}</small>
                </div>
            `;
        }
    }
}

/* ============================================================
   RENDERIZADO DE LA NÓMINA DE ALUMNOS (DENTRO DEL MODAL)
   ============================================================ */

function renderizarListaAlumnosModal(alumnos) {
    const container = document.getElementById('listaAlumnosContainer');
    if (!container) return;

    container.innerHTML = '';

    if (!alumnos || alumnos.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                <h6 class="fw-bold mb-1 text-secondary">No se encontraron estudiantes</h6>
                <small class="text-muted">No hay alumnos registrados en esta sección o coincidentes con la búsqueda.</small>
            </div>
        `;
        return;
    }

    alumnos.forEach((al, index) => {
        const item = document.createElement('div');
        item.className = 'student-card-item';

        const nombre = al.nombre || 'Nombre no registrado';
        const codigo = al.codigo || '--';
        const email = al.email || '';
        const celular = al.celular || '';

        item.innerHTML = `
            <div class="student-main-info">
                <div class="student-index-badge">
                    ${index + 1}
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="student-name-text" title="${escapeHTML(nombre)}">
                        ${escapeHTML(nombre)}
                    </div>
                    <div class="student-contact-row">
                        ${email ? `
                            <a href="mailto:${escapeHTML(email)}" class="student-contact-link" title="Enviar correo a ${escapeHTML(email)}">
                                <i class="fas fa-envelope text-secondary"></i> ${escapeHTML(email)}
                            </a>
                        ` : ''}
                        ${celular ? `
                            <a href="tel:${escapeHTML(celular)}" class="student-contact-link" title="Llamar a ${escapeHTML(celular)}">
                                <i class="fas fa-phone text-secondary"></i> ${escapeHTML(celular)}
                            </a>
                        ` : ''}
                    </div>
                </div>
            </div>

            <span class="student-code-badge" title="Código institucional UNI">
                ${escapeHTML(codigo)}
            </span>
        `;

        container.appendChild(item);
    });
}

/* ============================================================
   BÚSQUEDA / FILTRO EN TIEMPO REAL DENTRO DEL MODAL
   ============================================================ */

function filtrarAlumnosModal(termino) {
    const term = normalizarTexto(termino);
    const btnClear = document.getElementById('btnLimpiarBusqueda');
    const filtradosRow = document.getElementById('modalFiltradosCount');
    const lblFiltrados = document.getElementById('lblAlumnosFiltrados');

    if (term.length > 0) {
        btnClear?.classList.remove('d-none');
    } else {
        btnClear?.classList.add('d-none');
    }

    if (!window.currentAlumnosModal) return;

    if (!term) {
        filtradosRow?.classList.add('d-none');
        renderizarListaAlumnosModal(window.currentAlumnosModal);
        return;
    }

    const filtrados = window.currentAlumnosModal.filter(al => {
        const nombre = normalizarTexto(al.nombre || '');
        const codigo = normalizarTexto(al.codigo || '');
        const email = normalizarTexto(al.email || '');
        return nombre.includes(term) || codigo.includes(term) || email.includes(term);
    });

    if (filtradosRow && lblFiltrados) {
        filtradosRow.classList.remove('d-none');
        lblFiltrados.textContent = filtrados.length;
    }

    renderizarListaAlumnosModal(filtrados);
}

function limpiarBusquedaAlumno() {
    const inputSearch = document.getElementById('buscarAlumnoInput');
    if (inputSearch) {
        inputSearch.value = '';
        filtrarAlumnosModal('');
        inputSearch.focus();
    }
}

/* ============================================================
   EXPORTACIÓN EXCEL / CSV CON SOPORTE UTF-8 (TILDES Y Ñ)
   ============================================================ */

function exportarExcelAlumnos() {
    if (!window.currentAlumnosModal || window.currentAlumnosModal.length === 0) {
        alert('No hay alumnos registrados para exportar en esta sección.');
        return;
    }

    const clase = window.currentClaseModal || {};
    const codCurso = clase.codCurso || 'CURSO';
    const secCurso = clase.secCurso || 'U';
    const semestre = clase.semestre || '20262';

    // Se agrega el caracter BOM (\uFEFF) para que Excel interprete correctamente acentos y la letra Ñ
    let csv = '\uFEFF';
    csv += 'N°,Código,Apellidos y Nombres,Correo Institucional,Teléfono\r\n';

    window.currentAlumnosModal.forEach((al, idx) => {
        const fila = [
            idx + 1,
            `"${limpiarCSV(al.codigo)}"`,
            `"${limpiarCSV(al.nombre)}"`,
            `"${limpiarCSV(al.email)}"`,
            `"${limpiarCSV(al.celular)}"`
        ].join(',');

        csv += fila + '\r\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');

    link.setAttribute('href', url);
    link.setAttribute('download', `Nomina_Alumnos_${codCurso}_Sec_${secCurso}_${semestre}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

function limpiarCSV(valor) {
    return String(valor ?? '').replaceAll('"', '""');
}

/* ============================================================
   EXPORTACIÓN PDF / IMPRESIÓN OFICIAL DE NÓMINA
   ============================================================ */

function exportarPDFAlumnos() {
    if (!window.currentAlumnosModal || window.currentAlumnosModal.length === 0) {
        alert('No hay alumnos registrados para exportar en esta sección.');
        return;
    }

    const clase = window.currentClaseModal || {};
    const win = window.open('', '_blank');

    if (!win) {
        alert('Por favor habilite las ventanas emergentes (pop-ups) para generar la impresión.');
        return;
    }

    let filasHtml = '';
    window.currentAlumnosModal.forEach((al, idx) => {
        filasHtml += `
            <tr>
                <td style="text-align:center;">${idx + 1}</td>
                <td style="font-family:monospace; font-weight:bold;">${escapeHTML(al.codigo || '')}</td>
                <td>${escapeHTML(al.nombre || '')}</td>
                <td>${escapeHTML(al.email || '')}</td>
                <td>${escapeHTML(al.celular || '')}</td>
            </tr>
        `;
    });

    const htmlDoc = `
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Nómina de Alumnos - ${escapeHTML(clase.codCurso || '')} Sec ${escapeHTML(clase.secCurso || '')}</title>
            <style>
                * { box-sizing: border-box; }
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    margin: 30px;
                    color: #1E293B;
                    background: #fff;
                    font-size: 11px;
                }
                .header-table {
                    width: 100%;
                    border-bottom: 3px solid #7A1015;
                    padding-bottom: 12px;
                    margin-bottom: 18px;
                }
                .uni-name {
                    font-size: 14px;
                    font-weight: 800;
                    color: #7A1015;
                    letter-spacing: 0.5px;
                }
                .fac-name {
                    font-size: 11px;
                    font-weight: bold;
                    color: #173F67;
                    margin-top: 2px;
                }
                .doc-title {
                    font-size: 18px;
                    font-weight: 800;
                    color: #000;
                    margin-top: 8px;
                }
                .meta-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 10px;
                    margin-bottom: 18px;
                    background: #F8FAFC;
                    padding: 12px;
                    border: 1px solid #E2E8F0;
                    border-radius: 6px;
                }
                .meta-item label {
                    display: block;
                    font-size: 9px;
                    font-weight: bold;
                    color: #64748B;
                    text-transform: uppercase;
                }
                .meta-item strong {
                    font-size: 12px;
                    color: #0F172A;
                }
                table.data-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }
                table.data-table th, table.data-table td {
                    border: 1px solid #CBD5E1;
                    padding: 7px 9px;
                    text-align: left;
                }
                table.data-table th {
                    background: #7A1015;
                    color: #ffffff;
                    font-weight: bold;
                    font-size: 10px;
                    text-transform: uppercase;
                }
                table.data-table tr:nth-child(even) {
                    background: #F8FAFC;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 15px;
                    border-top: 1px solid #E2E8F0;
                    display: flex;
                    justify-content: space-between;
                    font-size: 9px;
                    color: #64748B;
                }
                .signature-box {
                    margin-top: 50px;
                    text-align: center;
                    width: 250px;
                    border-top: 1px solid #000;
                    padding-top: 5px;
                    margin-left: auto;
                    margin-right: auto;
                    font-weight: bold;
                }
                @media print {
                    body { margin: 15px; }
                }
            </style>
        </head>
        <body>
            <div class="header-table">
                <div class="uni-name">UNIVERSIDAD NACIONAL DE INGENIERÍA</div>
                <div class="fac-name">FACULTAD DE INGENIERÍA MECÁNICA &bull; DIRECCIÓN DE ESCUELA PROFESIONAL</div>
                <div class="doc-title">NÓMINA OFICIAL DE ESTUDIANTES MATRICULADOS</div>
            </div>

            <div class="meta-grid">
                <div class="meta-item">
                    <label>Asignatura</label>
                    <strong>${escapeHTML(clase.codCurso || '--')}</strong>
                </div>
                <div class="meta-item">
                    <label>Sección</label>
                    <strong>${escapeHTML(clase.secCurso || '--')}</strong>
                </div>
                <div class="meta-item">
                    <label>Aula y Horario</label>
                    <strong>${escapeHTML(clase.codAula || '--')} (${escapeHTML(clase.hora || '')})</strong>
                </div>
                <div class="meta-item">
                    <label>Total Matriculados</label>
                    <strong>${window.currentAlumnosModal.length} alumnos</strong>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align:center;">N°</th>
                        <th style="width: 100px;">Código</th>
                        <th>Apellidos y Nombres</th>
                        <th>Correo Institucional</th>
                        <th style="width: 100px;">Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    ${filasHtml}
                </tbody>
            </table>

            <div class="signature-box">
                Firma del Docente Responsable
            </div>

            <div class="footer">
                <div>Sistema de Gestión Académica FIM UNI</div>
                <div>Fecha de emisión: ${new Date().toLocaleDateString('es-PE')} ${new Date().toLocaleTimeString('es-PE')}</div>
            </div>

            <script>
                window.onload = function() {
                    window.print();
                };
            <\/script>
        </body>
        </html>
    `;

    win.document.write(htmlDoc);
    win.document.close();
}

/* ============================================================
   UTILIDADES Y HELPERS
   ============================================================ */

function normalizarDia(dia) {
    if (!dia) return '';
    const val = String(dia).trim().toUpperCase();
    return DIAS_MAP[val] || val;
}

function horaAMinutos(hora) {
    if (!hora) return null;
    const str = String(hora).trim();
    const partes = str.split(':');
    const h = parseInt(partes[0], 10);
    const m = partes.length > 1 ? parseInt(partes[1], 10) : 0;
    if (isNaN(h) || isNaN(m)) return null;
    return h * 60 + m;
}

function obtenerRangoHorario(horario) {
    if (!horario) return null;
    const partes = String(horario).split('-').map(p => p.trim());
    if (partes.length < 2) return null;

    const inicio = horaAMinutos(partes[0]);
    const fin = horaAMinutos(partes[1]);

    if (inicio === null || fin === null || fin <= inicio) return null;
    return { inicio, fin };
}

function formatearHora(minutos) {
    const h = Math.floor(minutos / 60);
    const m = minutos % 60;
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
}

function formatearRangoTexto(horario) {
    const rango = obtenerRangoHorario(horario);
    if (!rango) return horario || '--';
    return `${formatearHora(rango.inicio)} - ${formatearHora(rango.fin)}`;
}

function normalizarTexto(txt) {
    return String(txt || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

function escapeHTML(valor) {
    return String(valor ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function mostrarEstadoCargando() {
    const tbody = document.getElementById('tbodyHorario');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="spinner-border" style="color: var(--uni-maroon);" role="status">
                        <span class="visually-hidden">Cargando horario...</span>
                    </div>
                    <div class="mt-2 text-muted fw-semibold">Sincronizando horarios con la base de datos...</div>
                </td>
            </tr>
        `;
    }
}

function mostrarErrorHorario(mensaje) {
    const tbody = document.getElementById('tbodyHorario');
    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center py-5">
                <i class="fas fa-circle-exclamation fa-2x text-danger mb-2"></i>
                <div class="fw-bold text-danger fs-6">No se pudo cargar la programación de horarios</div>
                <small class="text-muted d-block mt-1">${escapeHTML(mensaje)}</small>
                <button type="button" class="btn btn-sm btn-outline-danger mt-3" onclick="cargarDatosCompletos()">
                    <i class="fas fa-rotate me-1"></i> Reintentar
                </button>
            </td>
        </tr>
    `;
}