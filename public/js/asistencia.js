/* ============================================================
   CONTROL DE ASISTENCIA DOCENTE Y ESTUDIANTIL
   UNIVERSIDAD NACIONAL DE INGENIERÍA - FACULTAD DE INGENIERÍA MECÁNICA
   ============================================================ */


/* ============================================================
   1. CONSTANTES Y CONFIGURACIÓN
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

const HORA_INICIO = 7;
const HORA_FIN = 22;

const API_URLS = {
    asistencias:
        window.asistenciaApi?.asistencias ||
        '/api/asistencias',

    asistenciaAlumno:
        window.asistenciaApi?.asistenciaAlumno ||
        '/api/asistencias/alumno',

    asistenciasAlumnos:
        window.asistenciaApi?.asistenciasAlumnos ||
        '/api/asistencias/alumnos',

    horarios:
        window.asistenciaApi?.horarios ||
        '/api/horarios',

    alumnos:
        window.asistenciaApi?.alumnos ||
        '/api/alumnos'
};


/* ============================================================
   2. RUTAS DE EXPORTACIÓN
   ============================================================ */

const EXPORT_URLS = {
    pdf: '/exportaciones/asistencia/pdf',
    excel: '/exportaciones/asistencia/excel'
};


/* ============================================================
   3. ESTADO GLOBAL DE LA APLICACIÓN
   ============================================================ */

let asistenciaData = [];
let horarioData = [];

let docenteActual =
    window.asistenciaApi?.codDocente || null;

let nombreDocenteActual = 'Docente';

let semestreActual = '20262';

let semanaActual =
    obtenerInicioSemana(new Date());

let sesionActiva = null;
let alumnosSesion = [];

let timerObservacion = null;
let guardandoEnServidor = false;


/* ============================================================
   4. INICIALIZACIÓN
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    inicializarControles();

    cargarDatos();

});


function inicializarControles() {

    /* --------------------------------------------------------
       Selector de Semestre
       -------------------------------------------------------- */

    const selectSemestre =
        document.getElementById('selectSemestre');

    if (selectSemestre) {

        if (!selectSemestre.value) {
            selectSemestre.value = '20262';
        }

        semestreActual =
            selectSemestre.value;

        selectSemestre.addEventListener(
            'change',
            function () {

                semestreActual =
                    this.value || '20262';

                semanaActual =
                    obtenerInicioSemana(
                        obtenerInicioPeriodo(
                            semestreActual
                        )
                    );

                cargarDatos();
            }
        );
    }


    /* --------------------------------------------------------
       Navegación semanal - anterior
       -------------------------------------------------------- */

    const btnAnterior =
        document.getElementById(
            'btnSemanaAnterior'
        );

    if (btnAnterior) {

        btnAnterior.addEventListener(
            'click',
            () => {

                const nueva =
                    sumarDias(
                        semanaActual,
                        -7
                    );

                if (
                    semanaDentroDelPeriodo(
                        nueva,
                        semestreActual
                    )
                ) {

                    semanaActual = nueva;

                    renderizarTodo();
                }
            }
        );
    }


    /* --------------------------------------------------------
       Navegación semanal - siguiente
       -------------------------------------------------------- */

    const btnSiguiente =
        document.getElementById(
            'btnSemanaSiguiente'
        );

    if (btnSiguiente) {

        btnSiguiente.addEventListener(
            'click',
            () => {

                const nueva =
                    sumarDias(
                        semanaActual,
                        7
                    );

                if (
                    semanaDentroDelPeriodo(
                        nueva,
                        semestreActual
                    )
                ) {

                    semanaActual = nueva;

                    renderizarTodo();
                }
            }
        );
    }


    /* --------------------------------------------------------
       Botón semana actual
       -------------------------------------------------------- */

    const btnHoy =
        document.getElementById(
            'btnSemanaActual'
        );

    if (btnHoy) {

        btnHoy.addEventListener(
            'click',
            () => {

                const hoy = new Date();

                if (
                    fechaDentroDelPeriodo(
                        hoy,
                        semestreActual
                    )
                ) {

                    semanaActual =
                        obtenerInicioSemana(
                            hoy
                        );

                } else {

                    semanaActual =
                        obtenerInicioSemana(
                            obtenerInicioPeriodo(
                                semestreActual
                            )
                        );
                }

                renderizarTodo();
            }
        );
    }


    /* --------------------------------------------------------
       Buscador de alumnos
       -------------------------------------------------------- */

    const inputBuscar =
        document.getElementById(
            'buscarAlumnoAsistenciaInput'
        );

    if (inputBuscar) {

        inputBuscar.addEventListener(
            'input',
            (e) => {

                filtrarAlumnosModal(
                    e.target.value
                );
            }
        );
    }


    /* --------------------------------------------------------
       Botón marcar todos como asistió
       -------------------------------------------------------- */

    const btnMarcarTodos =
        document.getElementById(
            'btnMarcarTodosAsistio'
        );

    if (btnMarcarTodos) {

        btnMarcarTodos.addEventListener(
            'click',
            () => {

                marcarTodosAsistio();
            }
        );
    }


    /* ========================================================
       EXPORTAR EXCEL
       ======================================================== */

    const btnExportarExcel =
        document.getElementById(
            'btnExportarExcelAsistencia'
        );

    if (btnExportarExcel) {

        btnExportarExcel.addEventListener(
            'click',
            () => {

                exportarExcelAsistencia();
            }
        );
    }


    /* ========================================================
       EXPORTAR PDF
       ======================================================== */

    const btnExportarPdf =
        document.getElementById(
            'btnExportarPdfAsistencia'
        );

    if (btnExportarPdf) {

        btnExportarPdf.addEventListener(
            'click',
            () => {

                exportarPdfAsistencia();
            }
        );
    }


    /* ========================================================
       ALERTA PERSONALIZADA
       ======================================================== */

    const btnCerrarAlerta =
        document.getElementById(
            'btnCerrarAlertaAsistencia'
        );

    const btnAceptarAlerta =
        document.getElementById(
            'btnAceptarAlertaAsistencia'
        );

    const alertaAsistencia =
        document.getElementById(
            'alertaAsistencia'
        );


    if (btnCerrarAlerta) {

        btnCerrarAlerta.addEventListener(
            'click',
            cerrarAlertaAsistencia
        );
    }


    if (btnAceptarAlerta) {

        btnAceptarAlerta.addEventListener(
            'click',
            cerrarAlertaAsistencia
        );
    }


    /* --------------------------------------------------------
       Cerrar haciendo clic fuera de la alerta
       -------------------------------------------------------- */

    if (alertaAsistencia) {

        alertaAsistencia.addEventListener(
            'click',
            (e) => {

                if (
                    e.target === alertaAsistencia
                ) {

                    cerrarAlertaAsistencia();
                }
            }
        );
    }


    /* --------------------------------------------------------
       Cerrar alerta con ESC
       -------------------------------------------------------- */

    document.addEventListener(
        'keydown',
        (e) => {

            if (
                e.key === 'Escape'
            ) {

                cerrarAlertaAsistencia();
            }
        }
    );
}


/* ============================================================
   5. MANEJO DE PERIODOS ACADÉMICOS Y SEMANAS
   ============================================================ */

function obtenerPeriodoAcademico(semestre) {

    const cod =
        String(semestre || '').trim();


    if (cod === '20261') {

        return {
            inicio: new Date(2026, 1, 1),
            fin: new Date(2026, 6, 31)
        };
    }


    if (cod === '20262') {

        return {
            inicio: new Date(2026, 7, 1),
            fin: new Date(2026, 11, 31)
        };
    }


    if (cod === '20252') {

        return {
            inicio: new Date(2025, 7, 1),
            fin: new Date(2025, 11, 31)
        };
    }


    const anio =
        parseInt(
            cod.substring(0, 4),
            10
        ) ||
        new Date().getFullYear();


    const periodo =
        cod.substring(4);


    if (periodo === '1') {

        return {
            inicio: new Date(anio, 1, 1),
            fin: new Date(anio, 6, 31)
        };
    }


    return {
        inicio: new Date(anio, 7, 1),
        fin: new Date(anio, 11, 31)
    };
}


function obtenerInicioPeriodo(semestre) {

    return new Date(
        obtenerPeriodoAcademico(
            semestre
        ).inicio
    );
}


function fechaDentroDelPeriodo(
    fecha,
    semestre
) {

    const periodo =
        obtenerPeriodoAcademico(
            semestre
        );

    const f =
        new Date(fecha);

    f.setHours(
        0,
        0,
        0,
        0
    );

    return (
        f >= periodo.inicio &&
        f <= periodo.fin
    );
}


function semanaDentroDelPeriodo(
    semana,
    semestre
) {

    const inicio =
        obtenerInicioSemana(
            semana
        );

    const fin =
        sumarDias(
            inicio,
            6
        );

    const periodo =
        obtenerPeriodoAcademico(
            semestre
        );

    return (
        fin >= periodo.inicio &&
        inicio <= periodo.fin
    );
}


function ajustarSemanaAlPeriodo(
    semana,
    semestre
) {

    if (
        semanaDentroDelPeriodo(
            semana,
            semestre
        )
    ) {

        return semana;
    }

    return obtenerInicioSemana(
        obtenerInicioPeriodo(
            semestre
        )
    );
}


function obtenerInicioSemana(fecha) {

    const d =
        new Date(fecha);

    d.setHours(
        0,
        0,
        0,
        0
    );

    const day =
        d.getDay();

    const diff =
        day === 0
            ? -6
            : 1 - day;

    d.setDate(
        d.getDate() + diff
    );

    return d;
}


function sumarDias(
    fecha,
    dias
) {

    const res =
        new Date(fecha);

    res.setDate(
        res.getDate() + dias
    );

    return res;
}


function formatearFechaISO(
    fecha
) {

    const d =
        new Date(fecha);

    const anio =
        d.getFullYear();

    const mes =
        String(
            d.getMonth() + 1
        ).padStart(2, '0');

    const dia =
        String(
            d.getDate()
        ).padStart(2, '0');

    return `${anio}-${mes}-${dia}`;
}


function formatearFechaCorta(
    fecha
) {

    const d =
        new Date(fecha);

    const dia =
        String(
            d.getDate()
        ).padStart(2, '0');

    const meses = [
        'Ene',
        'Feb',
        'Mar',
        'Abr',
        'May',
        'Jun',
        'Jul',
        'Ago',
        'Set',
        'Oct',
        'Nov',
        'Dic'
    ];

    return `${dia} ${meses[d.getMonth()]}`;
}


function formatearFechaLarga(
    fecha
) {

    if (!fecha) return '--';

    const partes =
        String(fecha).split('-');

    if (partes.length === 3) {

        const d =
            new Date(
                parseInt(
                    partes[0],
                    10
                ),
                parseInt(
                    partes[1],
                    10
                ) - 1,
                parseInt(
                    partes[2],
                    10
                )
            );

        const dias = [
            'Domingo',
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado'
        ];

        const meses = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        return `${dias[d.getDay()]}, ${d.getDate()} de ${meses[d.getMonth()]} de ${d.getFullYear()}`;
    }

    return String(fecha);
}


function calcularNumeroSemana(
    fecha,
    semestre
) {

    const inicioCiclo =
        obtenerInicioPeriodo(
            semestre
        );

    const inicioSemana =
        obtenerInicioSemana(
            fecha
        );

    const diffMs =
        inicioSemana.getTime() -
        inicioCiclo.getTime();

    const diffSemanas =
        Math.floor(
            diffMs /
            (7 * 24 * 60 * 60 * 1000)
        );

    return Math.max(
        1,
        diffSemanas + 1
    );
}


function normalizarDia(dia) {

    if (!dia) return '';

    const texto =
        String(dia)
            .trim()
            .toUpperCase();

    return (
        DIAS_MAP[texto] ||
        texto
    );
}


function normalizarTexto(val) {

    return String(val || '')
        .trim()
        .toUpperCase();
}


function escapeHTML(val) {

    if (
        val === null ||
        val === undefined
    ) {

        return '';
    }

    return String(val)
        .replace(
            /&/g,
            '&amp;'
        )
        .replace(
            /</g,
            '&lt;'
        )
        .replace(
            />/g,
            '&gt;'
        )
        .replace(
            /"/g,
            '&quot;'
        )
        .replace(
            /'/g,
            '&#039;'
        );
}


/* ============================================================
   6. CARGA DE DATOS DESDE LARAVEL / MYSQL
   ============================================================ */

async function cargarDatos() {

    window.cargarDatos =
        cargarDatos;

    mostrarCargaCalendario();

    try {

        const semestre =
            semestreActual || '20262';


        const resAsist =
            await fetch(
                `${API_URLS.asistencias}?semestre=${encodeURIComponent(semestre)}`,
                {
                    method: 'GET',
                    headers: {
                        'Accept':
                            'application/json'
                    },
                    credentials:
                        'same-origin'
                }
            );


        if (!resAsist.ok) {

            throw new Error(
                `Error en servidor asistencias (${resAsist.status})`
            );
        }


        const jsonAsist =
            await resAsist.json();


        if (jsonAsist.codDocente) {

            docenteActual =
                jsonAsist.codDocente;
        }


        asistenciaData =
            Array.isArray(jsonAsist)
                ? jsonAsist
                : Array.isArray(
                    jsonAsist.data
                )
                    ? jsonAsist.data
                    : [];


        if (
            !docenteActual &&
            asistenciaData.length > 0
        ) {

            docenteActual =
                asistenciaData[0]
                    .codDocente;
        }


        let urlHorarios =
            `${API_URLS.horarios}?semestre=${encodeURIComponent(semestre)}`;


        if (docenteActual) {

            urlHorarios +=
                `&codDocente=${encodeURIComponent(docenteActual)}`;
        }


        const resHor =
            await fetch(
                urlHorarios,
                {
                    method: 'GET',
                    headers: {
                        'Accept':
                            'application/json'
                    },
                    credentials:
                        'same-origin'
                }
            );


        if (resHor.ok) {

            const jsonHor =
                await resHor.json();

            horarioData =
                Array.isArray(jsonHor)
                    ? jsonHor
                    : Array.isArray(
                        jsonHor.data
                    )
                        ? jsonHor.data
                        : [];

        } else {

            horarioData = [];
        }


        semanaActual =
            ajustarSemanaAlPeriodo(
                semanaActual,
                semestreActual
            );


        renderizarTodo();

    } catch (err) {

        console.error(
            'Error al cargar datos de asistencia:',
            err
        );


        const tbody =
            document.getElementById(
                'tbodyAsistencia'
            );


        if (tbody) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="8"
                        class="text-center py-5 text-danger">

                        <i class="fas fa-triangle-exclamation fa-2x mb-2"></i>

                        <div class="fw-bold">
                            No se pudieron cargar los datos de asistencia
                        </div>

                        <small class="text-muted">
                            ${escapeHTML(err.message)}
                        </small>

                    </td>
                </tr>
            `;
        }
    }
}


/* ============================================================
   7. RENDERIZADO GLOBAL
   ============================================================ */

function renderizarTodo() {

    actualizarInformacionDocente();

    actualizarEstadisticasDocente();

    renderizarCalendario();
}


function actualizarInformacionDocente() {

    const lblDocente =
        document.getElementById(
            'lblDocente'
        );


    if (
        lblDocente &&
        lblDocente.textContent.trim()
    ) {

        nombreDocenteActual =
            lblDocente.textContent.trim();
    }


    const setHorarios =
        new Set();

    let totalHoras = 0;


    horarioData.forEach(item => {

        const clave =
            `${item.codHorario || item.codCurso}-${item.dia}-${item.hora}`;


        if (!setHorarios.has(clave)) {

            setHorarios.add(clave);

            const rango =
                obtenerHorasRango(
                    item.hora
                );


            if (rango) {

                totalHoras +=
                    Math.max(
                        0,
                        rango.fin -
                        rango.inicio
                    );
            }
        }
    });


    const lblCarga =
        document.getElementById(
            'lblCargaHoraria'
        );


    if (lblCarga) {

        lblCarga.textContent =
            Math.round(totalHoras);
    }
}


function actualizarEstadisticasDocente() {

    let programadas = 0;
    let puntuales = 0;
    let tardanzas = 0;
    let inasistencias = 0;
    let noAplica = 0;
    let proceso = 0;


    asistenciaData.forEach(item => {

        const est =
            obtenerEstadoAsistencia(
                item
            );


        switch (est) {

            case 'PUNTUAL':
                puntuales++;
                break;

            case 'TARDANZA':
                tardanzas++;
                break;

            case 'INASISTENCIA':
                inasistencias++;
                break;

            case 'NO APLICA':
                noAplica++;
                break;

            case 'EN PROCESO DE CAMBIO':
                proceso++;
                break;

            default:
                programadas++;
                break;
        }
    });


    if (
        asistenciaData.length === 0 &&
        horarioData.length > 0
    ) {

        programadas =
            horarioData.length * 16;
    }


    setTexto(
        'statProgramadas',
        programadas
    );

    setTexto(
        'statPuntuales',
        puntuales
    );

    setTexto(
        'statTardanzas',
        tardanzas
    );

    setTexto(
        'statInasistencias',
        inasistencias
    );

    setTexto(
        'statNoAplica',
        noAplica
    );

    setTexto(
        'statProceso',
        proceso
    );
}


function setTexto(
    id,
    texto
) {

    const el =
        document.getElementById(id);

    if (el) {

        el.textContent =
            texto ?? '0';
    }
}


/* ============================================================
   8. CALENDARIO SEMANAL
   ============================================================ */

function renderizarCalendario() {

    const tbody =
        document.getElementById(
            'tbodyAsistencia'
        );


    if (!tbody) return;


    tbody.innerHTML = '';


    actualizarEtiquetaSemana();

    actualizarCabecerasDias();


    const inicioSemana =
        obtenerInicioSemana(
            semanaActual
        );


    const ocupadas = {};


    DIAS_SEMANA.forEach(d => {

        ocupadas[d] = {};
    });


    for (
        let hora = HORA_INICIO;
        hora < HORA_FIN;
        hora++
    ) {

        const tr =
            document.createElement(
                'tr'
            );


        const tdHora =
            document.createElement(
                'td'
            );


        tdHora.className =
            'celda-hora';


        tdHora.innerHTML = `
            <span class="hora-text">
                ${String(hora).padStart(2, '0')}:00
            </span>

            <span class="hora-subtext">
                ${String(hora + 1).padStart(2, '0')}:00
            </span>
        `;


        tr.appendChild(
            tdHora
        );


        DIAS_SEMANA.forEach(
            (dia, indexDia) => {

                if (
                    ocupadas[dia][hora]
                ) {

                    return;
                }


                const td =
                    document.createElement(
                        'td'
                    );


                td.className =
                    'celda-dia';


                if (
                    dia === 'DOMINGO'
                ) {

                    td.classList.add(
                        'col-domingo'
                    );
                }


                if (
                    dia === 'SÁBADO'
                ) {

                    td.classList.add(
                        'col-sabado'
                    );
                }


                const horario =
                    buscarHorarioEnHora(
                        dia,
                        hora
                    );


                if (horario) {

                    const rango =
                        obtenerHorasRango(
                            horario.hora
                        );


                    const duracion =
                        rango
                            ? Math.max(
                                1,
                                Math.ceil(
                                    rango.fin
                                ) -
                                Math.floor(
                                    rango.inicio
                                )
                            )
                            : 1;


                    if (
                        duracion > 1
                    ) {

                        td.rowSpan =
                            duracion;


                        for (
                            let h = hora + 1;
                            h < hora + duracion;
                            h++
                        ) {

                            ocupadas[dia][h] =
                                true;
                        }
                    }


                    const fechaDia =
                        sumarDias(
                            inicioSemana,
                            indexDia
                        );


                    const fechaStr =
                        formatearFechaISO(
                            fechaDia
                        );


                    const asistencia =
                        buscarAsistenciaHorarioFecha(
                            horario,
                            fechaStr
                        );


                    const bloque =
                        crearBloqueClase(
                            horario,
                            asistencia,
                            fechaStr,
                            dia
                        );


                    td.appendChild(
                        bloque
                    );
                }


                tr.appendChild(
                    td
                );
            }
        );


        tbody.appendChild(
            tr
        );
    }
}


function actualizarEtiquetaSemana() {

    const lbl =
        document.getElementById(
            'lblSemana'
        );


    if (!lbl) return;


    const inicio =
        obtenerInicioSemana(
            semanaActual
        );


    const fin =
        sumarDias(
            inicio,
            6
        );


    const numSemana =
        calcularNumeroSemana(
            inicio,
            semestreActual
        );


    lbl.innerHTML = `
        <i class="far fa-calendar me-2 text-primary"></i>

        <span>
            Semana ${numSemana}
            &bull;
            ${formatearFechaCorta(inicio)}
            -
            ${formatearFechaCorta(fin)}
        </span>
    `;
}


function actualizarCabecerasDias() {

    const thElements =
        document.querySelectorAll(
            '#tablaAsistencia thead th[data-dia]'
        );


    const inicio =
        obtenerInicioSemana(
            semanaActual
        );


    thElements.forEach(
        (th, index) => {

            const diaNombre =
                th.getAttribute(
                    'data-dia'
                );


            const fechaDia =
                sumarDias(
                    inicio,
                    index
                );


            const diaNum =
                String(
                    fechaDia.getDate()
                ).padStart(2, '0');


            const mesNum =
                String(
                    fechaDia.getMonth() + 1
                ).padStart(2, '0');


            th.innerHTML = `
                <div class="dia-name">
                    ${diaNombre}
                </div>

                <span class="dia-code">
                    ${diaNum}/${mesNum}
                </span>
            `;
        }
    );
}


function buscarHorarioEnHora(
    dia,
    hora
) {

    return horarioData.find(
        item => {

            if (
                normalizarDia(item.dia) !==
                dia
            ) {

                return false;
            }


            const rango =
                obtenerHorasRango(
                    item.hora
                );


            if (!rango) {
                return false;
            }


            return (
                Math.floor(
                    rango.inicio
                ) === hora
            );
        }
    );
}


function buscarAsistenciaHorarioFecha(
    horario,
    fechaStr
) {

    if (
        !horario ||
        !fechaStr
    ) {

        return null;
    }


    return asistenciaData.find(
        asist => {

            if (
                asist.fecha !==
                fechaStr
            ) {

                return false;
            }


            if (
                asist.codHorario &&
                horario.codHorario
            ) {

                return (
                    String(
                        asist.codHorario
                    ) ===
                    String(
                        horario.codHorario
                    )
                );
            }


            return (
                normalizarTexto(
                    asist.codCurso
                ) ===
                normalizarTexto(
                    horario.codCurso
                )

                &&

                normalizarTexto(
                    asist.secCurso
                ) ===
                normalizarTexto(
                    horario.secCurso
                )
            );
        }
    ) || null;
}


/* ============================================================
   9. BLOQUE DE CLASE
   ============================================================ */

function crearBloqueClase(
    horario,
    asistencia,
    fechaStr,
    diaNombre
) {

    const bloque =
        document.createElement(
            'div'
        );


    const estado =
        asistencia
            ? obtenerEstadoAsistencia(
                asistencia
            )
            : 'PROGRAMADA';


    const claseModificador =
        obtenerClaseEstado(
            estado
        );


    bloque.className =
        `bloque-asistencia ${claseModificador}`;


    const codCurso =
        horario.codCurso ||
        'CURSO';


    const secCurso =
        horario.secCurso ||
        '--';


    const teopra =
        horario.teopra
            ? horario.teopra.toUpperCase()
            : 'TEO';


    const horaTexto =
        horario.hora ||
        '--';


    const nombreCurso =
        horario.nomCurso ||
        horario.nombreCurso ||
        horario.curso ||
        codCurso;


    const aulaTexto =
        horario.aula ||
        'Aula FIM';


    bloque.innerHTML = `
        <div class="bloque-cabecera">

            <span class="bloque-curso">
                ${escapeHTML(codCurso)}
            </span>

            <span class="bloque-seccion">
                Sec: ${escapeHTML(secCurso)}
                (${escapeHTML(teopra)})
            </span>

        </div>

        <div
            class="bloque-nombre"
            title="${escapeHTML(nombreCurso)}"
        >
            ${escapeHTML(nombreCurso)}
        </div>

        <div class="bloque-informacion">

            <div>
                <i class="far fa-clock me-1"></i>
                ${escapeHTML(horaTexto)}
            </div>

            <div>
                <i class="fas fa-location-dot me-1"></i>
                ${escapeHTML(aulaTexto)}
            </div>

        </div>

        <div class="bloque-footer">

            <span class="bloque-badge-estado">
                ${escapeHTML(estado)}
            </span>

            <button
                type="button"
                class="bloque-ojo"
                title="Registrar asistencia de los estudiantes"
            >
                <i class="fas fa-clipboard-user"></i>
            </button>

        </div>
    `;


    const sesionInfo = {

        codHorario:
            horario.codHorario ||
            null,

        codDocente:
            docenteActual,

        codCurso:
            codCurso,

        secCurso:
            secCurso,

        teopra:
            teopra,

        hora:
            horaTexto,

        aula:
            aulaTexto,

        fecha:
            fechaStr,

        dia:
            diaNombre,

        semestre:
            horario.semestre ||
            semestreActual,

        estado:
            estado,

        nombreCurso:
            nombreCurso
    };


    bloque.addEventListener(
        'click',
        (e) => {

            e.preventDefault();

            abrirModalAsistencia(
                sesionInfo
            );
        }
    );


    return bloque;
}


function obtenerEstadoAsistencia(
    registro
) {

    if (!registro) {
        return 'PROGRAMADA';
    }


    const estado =
        String(
            registro.estado || ''
        )
            .trim()
            .toUpperCase();


    if (
        estado.includes('NO APLICA') ||
        estado === 'N/A'
    ) {

        return 'NO APLICA';
    }


    if (
        estado.includes('PROCESO') ||
        estado.includes('CAMBIO')
    ) {

        return 'EN PROCESO DE CAMBIO';
    }


    if (
        estado.includes('INASIST') ||
        estado.includes('FALTA')
    ) {

        return 'INASISTENCIA';
    }


    if (
        estado.includes('TARD')
    ) {

        return 'TARDANZA';
    }


    if (
        estado.includes('PUNT') ||
        estado.includes('ASIST') ||
        estado.includes('PRESENT')
    ) {

        return 'PUNTUAL';
    }


    if (!registro.horEntrada) {
        return 'PROGRAMADA';
    }


    const rango =
        obtenerHorasRango(
            registro.hora
        );


    if (!rango) {
        return 'PROGRAMADA';
    }


    const entrada =
        convertirHoraAMinutos(
            registro.horEntrada
        );


    if (entrada === null) {
        return 'PROGRAMADA';
    }


    const programadaMin =
        Math.round(
            rango.inicio * 60
        );


    if (
        entrada <=
        programadaMin + 10
    ) {

        return 'PUNTUAL';
    }


    return 'TARDANZA';
}


function obtenerClaseEstado(
    estado
) {

    switch (estado) {

        case 'PUNTUAL':
            return 'bloque-puntual';

        case 'TARDANZA':
            return 'bloque-tardanza';

        case 'INASISTENCIA':
            return 'bloque-inasistencia';

        case 'NO APLICA':
            return 'bloque-no-aplica';

        case 'EN PROCESO DE CAMBIO':
            return 'bloque-proceso';

        default:
            return 'bloque-programada';
    }
}


/* ============================================================
   10. MODAL DE TOMA DE ASISTENCIA
   ============================================================ */

async function abrirModalAsistencia(
    sesion
) {

    sesionActiva = sesion;


    setTexto(
        'modalTitulo',
        `Asistencia de Alumnos - Sec. ${sesion.secCurso}`
    );


    setTexto(
        'modalSubtitulo',
        `${formatearFechaLarga(sesion.fecha)} • ${sesion.hora}`
    );


    setTexto(
        'modalCurso',
        `${sesion.codCurso} - ${sesion.nombreCurso}`
    );


    setTexto(
        'modalHorario',
        `${sesion.dia} ${sesion.hora}`
    );


    setTexto(
        'modalAula',
        sesion.aula
    );


    setTexto(
        'modalEstado',
        sesion.estado
    );


    const inputBuscar =
        document.getElementById(
            'buscarAlumnoAsistenciaInput'
        );


    if (inputBuscar) {
        inputBuscar.value = '';
    }


    const contenedor =
        document.getElementById(
            'listaAlumnos'
        );


    if (contenedor) {

        contenedor.innerHTML = `
            <div class="text-center py-5">

                <div
                    class="spinner-border text-primary"
                    role="status"
                ></div>

                <div class="mt-2 text-muted fw-semibold">
                    Consultando lista de matriculados y asistencias registradas...
                </div>

            </div>
        `;
    }


    actualizarIndicadorGuardado(
        'ready'
    );


    const modalEl =
        document.getElementById(
            'modalAsistencia'
        );


    if (
        modalEl &&
        typeof bootstrap !== 'undefined' &&
        bootstrap.Modal
    ) {

        bootstrap.Modal
            .getOrCreateInstance(
                modalEl
            )
            .show();
    }


    try {

        const urlMatriculados =
            `${API_URLS.asistencias}` +
            `?semestre=${encodeURIComponent(sesion.semestre)}` +
            `&codCurso=${encodeURIComponent(sesion.codCurso)}` +
            `&secCurso=${encodeURIComponent(sesion.secCurso)}`;


        const resAlumnos =
            await fetch(
                urlMatriculados,
                {
                    method: 'GET',
                    headers: {
                        'Accept':
                            'application/json'
                    },
                    credentials:
                        'same-origin'
                }
            );


        const jsonAlumnos =
            await resAlumnos.json();


        const matriculados =
            Array.isArray(
                jsonAlumnos.alumnos
            )
                ? jsonAlumnos.alumnos
                : [];


        let guardados = [];


        try {

            const urlGuardados =
                `${API_URLS.asistenciasAlumnos}` +
                `?fecha=${encodeURIComponent(sesion.fecha)}` +
                `&codCurso=${encodeURIComponent(sesion.codCurso)}` +
                `&secCurso=${encodeURIComponent(sesion.secCurso)}` +
                `&semestre=${encodeURIComponent(sesion.semestre)}`;


            const resGuardados =
                await fetch(
                    urlGuardados,
                    {
                        method: 'GET',
                        headers: {
                            'Accept':
                                'application/json'
                        },
                        credentials:
                            'same-origin'
                    }
                );


            if (resGuardados.ok) {

                const jsonGuardados =
                    await resGuardados.json();


                guardados =
                    Array.isArray(
                        jsonGuardados.data
                    )
                        ? jsonGuardados.data
                        : [];
            }

        } catch (e) {

            console.warn(
                'No se pudieron consultar asistencias previas en MySQL:',
                e
            );
        }


        const mapaGuardado = {};


        guardados.forEach(
            item => {

                const cod =
                    String(
                        item.codAlumno
                    )
                        .trim()
                        .toUpperCase();


                mapaGuardado[cod] = {

                    estado:
                        normalizarAsistencia(
                            item.estado
                        ),

                    observacion:
                        item.observacion ||
                        ''
                };
            }
        );


        alumnosSesion =
            matriculados.map(
                alumno => {

                    const cod =
                        String(
                            alumno.codAlumno ||
                            alumno.codigo ||
                            ''
                        )
                            .trim()
                            .toUpperCase();


                    const previo =
                        mapaGuardado[cod] ||
                        {};


                    return {

                        ...alumno,

                        codAlumno:
                            cod,

                        nombreCompleto:
                            [
                                alumno.apePaterno,
                                alumno.apeMaterno,
                                alumno.nombres
                            ]
                                .filter(Boolean)
                                .join(' ')
                            ||
                            alumno.nombre
                            ||
                            'Sin nombre',

                        estado:
                            previo.estado ||
                            '',

                        observacion:
                            previo.observacion ||
                            ''
                    };
                }
            );


        renderizarListaAlumnos(
            alumnosSesion
        );

    } catch (err) {

        console.error(
            'Error al cargar alumnos de la sesión:',
            err
        );


        if (contenedor) {

            contenedor.innerHTML = `
                <div
                    class="alumnos-vacio text-danger text-center py-4"
                >

                    <i
                        class="fas fa-circle-exclamation fa-2x mb-2"
                    ></i>

                    <h6>
                        Error al cargar la lista de alumnos
                    </h6>

                    <p class="text-muted">
                        ${escapeHTML(err.message)}
                    </p>

                </div>
            `;
        }
    }
}


/* ============================================================
   11. RENDERIZADO DE ALUMNOS
   ============================================================ */

function renderizarListaAlumnos(
    alumnos
) {

    const contenedor =
        document.getElementById(
            'listaAlumnos'
        );


    if (!contenedor) return;


    contenedor.innerHTML = '';


    if (
        !alumnos ||
        alumnos.length === 0
    ) {

        contenedor.innerHTML = `
            <div class="alumnos-vacio text-center py-5">

                <i
                    class="fas fa-users-slash fa-2x text-muted mb-2"
                ></i>

                <h6 class="text-secondary">
                    No se encontraron estudiantes matriculados
                </h6>

                <p class="text-muted small">
                    No existen registros en la base de datos
                    para este curso, sección y semestre.
                </p>

            </div>
        `;

        return;
    }


    const resumen =
        calcularResumenAlumnos(
            alumnosSesion
        );


    const divResumen =
        document.createElement(
            'div'
        );


    divResumen.className =
        'alumnos-resumen';


    divResumen.id =
        'barraResumenAlumnos';


    divResumen.innerHTML = `
        <div class="alumno-resumen-item total">
            <strong>${resumen.total}</strong>
            <span>TOTAL</span>
        </div>

        <div class="alumno-resumen-item asistio">
            <strong>${resumen.asistio}</strong>
            <span>ASISTIERON</span>
        </div>

        <div class="alumno-resumen-item falta">
            <strong>${resumen.falto}</strong>
            <span>FALTAS</span>
        </div>

        <div class="alumno-resumen-item tardanza">
            <strong>${resumen.tardanza}</strong>
            <span>TARDANZAS</span>
        </div>

        <div class="alumno-resumen-item justificado">
            <strong>${resumen.justificado}</strong>
            <span>JUSTIFICADOS</span>
        </div>

        <div class="alumno-resumen-item sin-marcar">
            <strong>${resumen.sinMarcar}</strong>
            <span>SIN MARCAR</span>
        </div>
    `;


    contenedor.appendChild(
        divResumen
    );


    const tableWrapper =
        document.createElement(
            'div'
        );


    tableWrapper.className =
        'table-responsive';


    const table =
        document.createElement(
            'table'
        );


    table.className =
        'table alumnos-table';


    table.innerHTML = `
        <thead>
            <tr>

                <th style="width: 40px;">
                    #
                </th>

                <th style="width: 110px;">
                    CÓDIGO
                </th>

                <th>
                    ESTUDIANTE
                </th>

                <th
                    style="width: 110px; text-align: center;"
                >
                    ESTADO
                </th>

                <th style="min-width: 320px;">
                    MARCAR ASISTENCIA
                </th>

                <th style="min-width: 200px;">
                    OBSERVACIÓN
                </th>

            </tr>
        </thead>

        <tbody id="tbodyAlumnosModal"></tbody>
    `;


    tableWrapper.appendChild(
        table
    );


    contenedor.appendChild(
        tableWrapper
    );


    const tbody =
        table.querySelector(
            'tbody'
        );


    alumnos.forEach(
        (alumno, idx) => {

            const tr =
                document.createElement(
                    'tr'
                );


            tr.id =
                `fila-alumno-${alumno.codAlumno}`;


            const estado =
                normalizarAsistencia(
                    alumno.estado
                );


            const badgeInfo =
                obtenerBadgeAsistencia(
                    estado
                );


            tr.innerHTML = `
                <td
                    class="text-center text-muted fw-bold"
                >
                    ${idx + 1}
                </td>

                <td>
                    <span class="student-code">
                        ${escapeHTML(alumno.codAlumno)}
                    </span>
                </td>

                <td>

                    <div class="student-name">
                        ${escapeHTML(alumno.nombreCompleto)}
                    </div>

                    <div class="student-correo">
                        ${escapeHTML(
                            alumno.email ||
                            alumno.especialidad ||
                            ''
                        )}
                    </div>

                </td>

                <td
                    class="text-center"
                    id="badge-estado-${alumno.codAlumno}"
                >
                    <span
                        class="badge ${badgeInfo.clase}"
                    >
                        ${badgeInfo.texto}
                    </span>
                </td>

                <td>

                    <div
                        class="asistencia-opciones"
                        data-alumno-cod="${escapeHTML(alumno.codAlumno)}"
                    >

                        <button
                            type="button"
                            class="btn-marca btn-marca-asistio ${estado === 'ASISTIO' ? 'active' : ''}"
                            data-estado="ASISTIO"
                            title="Marcar Asistió"
                        >
                            <i class="fas fa-check"></i>
                            ASISTIÓ
                        </button>

                        <button
                            type="button"
                            class="btn-marca btn-marca-falto ${estado === 'FALTO' ? 'active' : ''}"
                            data-estado="FALTO"
                            title="Marcar Falta"
                        >
                            <i class="fas fa-xmark"></i>
                            FALTÓ
                        </button>

                        <button
                            type="button"
                            class="btn-marca btn-marca-tardanza ${estado === 'TARDANZA' ? 'active' : ''}"
                            data-estado="TARDANZA"
                            title="Marcar Tardanza"
                        >
                            <i class="fas fa-clock"></i>
                            TARDANZA
                        </button>

                        <button
                            type="button"
                            class="btn-marca btn-marca-justificado ${estado === 'JUSTIFICADO' ? 'active' : ''}"
                            data-estado="JUSTIFICADO"
                            title="Marcar Justificado"
                        >
                            <i class="fas fa-file-shield"></i>
                            JUSTIF.
                        </button>

                        <button
                            type="button"
                            class="btn-marca btn-marca-limpiar"
                            data-estado=""
                            title="Limpiar registro"
                        >
                            <i class="fas fa-eraser"></i>
                        </button>

                    </div>

                </td>

                <td>

                    <input
                        type="text"
                        class="form-control form-control-sm alumno-observacion"
                        value="${escapeHTML(alumno.observacion || '')}"
                        placeholder="Nota opcional..."
                        data-observacion-cod="${escapeHTML(alumno.codAlumno)}"
                    >

                </td>
            `;


            const btns =
                tr.querySelectorAll(
                    '.btn-marca'
                );


            btns.forEach(
                btn => {

                    btn.addEventListener(
                        'click',
                        (e) => {

                            e.preventDefault();

                            const nuevoEstado =
                                btn.getAttribute(
                                    'data-estado'
                                );


                            marcarAsistenciaIndividual(
                                alumno.codAlumno,
                                nuevoEstado
                            );
                        }
                    );
                }
            );


            const inputObs =
                tr.querySelector(
                    '.alumno-observacion'
                );


            if (inputObs) {

                inputObs.addEventListener(
                    'input',
                    (e) => {

                        const obsTexto =
                            e.target.value;


                        alumno.observacion =
                            obsTexto;


                        clearTimeout(
                            timerObservacion
                        );


                        timerObservacion =
                            setTimeout(
                                () => {

                                    guardarEnBaseDeDatos(
                                        alumno.codAlumno,
                                        alumno.estado,
                                        obsTexto
                                    );

                                },
                                600
                            );
                    }
                );
            }


            tbody.appendChild(
                tr
            );
        }
    );
}


function calcularResumenAlumnos(
    alumnos
) {

    const res = {

        total:
            alumnos.length,

        asistio:
            0,

        falto:
            0,

        tardanza:
            0,

        justificado:
            0,

        sinMarcar:
            0
    };


    alumnos.forEach(
        a => {

            const est =
                normalizarAsistencia(
                    a.estado
                );


            if (
                est === 'ASISTIO'
            ) {

                res.asistio++;

            } else if (
                est === 'FALTO'
            ) {

                res.falto++;

            } else if (
                est === 'TARDANZA'
            ) {

                res.tardanza++;

            } else if (
                est === 'JUSTIFICADO'
            ) {

                res.justificado++;

            } else {

                res.sinMarcar++;
            }
        }
    );


    return res;
}


function actualizarBarraResumen() {

    const divResumen =
        document.getElementById(
            'barraResumenAlumnos'
        );


    if (!divResumen) return;


    const r =
        calcularResumenAlumnos(
            alumnosSesion
        );


    divResumen.innerHTML = `
        <div class="alumno-resumen-item total">
            <strong>${r.total}</strong>
            <span>TOTAL</span>
        </div>

        <div class="alumno-resumen-item asistio">
            <strong>${r.asistio}</strong>
            <span>ASISTIERON</span>
        </div>

        <div class="alumno-resumen-item falta">
            <strong>${r.falto}</strong>
            <span>FALTAS</span>
        </div>

        <div class="alumno-resumen-item tardanza">
            <strong>${r.tardanza}</strong>
            <span>TARDANZAS</span>
        </div>

        <div class="alumno-resumen-item justificado">
            <strong>${r.justificado}</strong>
            <span>JUSTIFICADOS</span>
        </div>

        <div class="alumno-resumen-item sin-marcar">
            <strong>${r.sinMarcar}</strong>
            <span>SIN MARCAR</span>
        </div>
    `;
}


function normalizarAsistencia(
    valor
) {

    if (!valor) return '';


    const v =
        String(valor)
            .trim()
            .toUpperCase();


    if (
        v === 'PRESENTE' ||
        v === 'ASISTENCIA' ||
        v === 'ASISTIÓ' ||
        v === 'ASISTIO'
    ) {

        return 'ASISTIO';
    }


    if (
        v === 'FALTA' ||
        v === 'AUSENTE' ||
        v === 'FALTO' ||
        v === 'FALTÓ'
    ) {

        return 'FALTO';
    }


    if (
        v === 'TARDE' ||
        v === 'TARDIO' ||
        v === 'TARDÍO' ||
        v === 'TARDANZA'
    ) {

        return 'TARDANZA';
    }


    if (
        v === 'JUSTIFICADA' ||
        v === 'JUSTIFICADO'
    ) {

        return 'JUSTIFICADO';
    }


    return v;
}


function obtenerBadgeAsistencia(
    estado
) {

    switch (estado) {

        case 'ASISTIO':

            return {
                clase: 'badge-presente',
                texto: 'ASISTIÓ'
            };


        case 'FALTO':

            return {
                clase: 'badge-falta',
                texto: 'FALTÓ'
            };


        case 'TARDANZA':

            return {
                clase: 'badge-tarde',
                texto: 'TARDANZA'
            };


        case 'JUSTIFICADO':

            return {
                clase: 'badge-justificado',
                texto: 'JUSTIFICADO'
            };


        default:

            return {
                clase: 'badge-sin-registro',
                texto: 'SIN MARCAR'
            };
    }
}


/* ============================================================
   12. MARCAR ASISTENCIA Y PERSISTENCIA
   ============================================================ */

async function marcarAsistenciaIndividual(
    codAlumno,
    nuevoEstado
) {

    const alumno =
        alumnosSesion.find(
            a =>
                a.codAlumno ===
                codAlumno
        );


    if (
        !alumno ||
        !sesionActiva
    ) {

        return;
    }


    alumno.estado =
        normalizarAsistencia(
            nuevoEstado
        );


    actualizarFilaEstudiante(
        alumno
    );


    actualizarBarraResumen();


    await guardarEnBaseDeDatos(
        alumno.codAlumno,
        alumno.estado,
        alumno.observacion
    );
}


function actualizarFilaEstudiante(
    alumno
) {

    const contenedorBotones =
        document.querySelector(
            `.asistencia-opciones[data-alumno-cod="${alumno.codAlumno}"]`
        );


    if (contenedorBotones) {

        const btns =
            contenedorBotones.querySelectorAll(
                '.btn-marca'
            );


        btns.forEach(
            btn => {

                const btnEstado =
                    btn.getAttribute(
                        'data-estado'
                    );


                if (
                    alumno.estado &&
                    btnEstado ===
                    alumno.estado
                ) {

                    btn.classList.add(
                        'active'
                    );

                } else {

                    btn.classList.remove(
                        'active'
                    );
                }
            }
        );
    }


    const badgeTd =
        document.getElementById(
            `badge-estado-${alumno.codAlumno}`
        );


    if (badgeTd) {

        const badgeInfo =
            obtenerBadgeAsistencia(
                alumno.estado
            );


        badgeTd.innerHTML = `
            <span
                class="badge ${badgeInfo.clase}"
            >
                ${badgeInfo.texto}
            </span>
        `;
    }
}


async function guardarEnBaseDeDatos(
    codAlumno,
    estado,
    observacion
) {

    if (!sesionActiva) {
        return;
    }


    actualizarIndicadorGuardado(
        'saving'
    );


    const payload = {

        codHorario:
            sesionActiva.codHorario ||
            null,

        codDocente:
            String(
                docenteActual ||
                sesionActiva.codDocente ||
                'DOCENTE'
            ),

        codAlumno:
            String(codAlumno),

        fecha:
            sesionActiva.fecha,

        codCurso:
            sesionActiva.codCurso,

        secCurso:
            sesionActiva.secCurso,

        semestre:
            sesionActiva.semestre ||
            semestreActual,

        estado:
            estado ||
            'SIN MARCAR',

        observacion:
            observacion ||
            ''
    };


    const token =
        window.csrfToken ||
        document
            .querySelector(
                'meta[name="csrf-token"]'
            )
            ?.getAttribute(
                'content'
            ) ||
        '';


    try {

        const respuesta =
            await fetch(
                API_URLS.asistenciaAlumno,
                {
                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            token
                    },

                    credentials:
                        'same-origin',

                    body:
                        JSON.stringify(
                            payload
                        )
                }
            );


        if (!respuesta.ok) {

            const errData =
                await respuesta
                    .json()
                    .catch(
                        () => ({})
                    );


            throw new Error(
                errData.message ||
                `Error del servidor (${respuesta.status})`
            );
        }


        const jsonResp =
            await respuesta.json();


        if (jsonResp.success) {

            const hora =
                new Date()
                    .toLocaleTimeString(
                        [],
                        {
                            hour:
                                '2-digit',

                            minute:
                                '2-digit',

                            second:
                                '2-digit'
                        }
                    );


            actualizarIndicadorGuardado(
                'saved',
                `Guardado en MySQL (${hora})`
            );

        } else {

            throw new Error(
                jsonResp.message ||
                'No se pudo guardar la asistencia.'
            );
        }

    } catch (err) {

        console.error(
            'Error al guardar asistencia del alumno en MySQL:',
            err
        );


        actualizarIndicadorGuardado(
            'error',
            'Error al sincronizar con el servidor.'
        );
    }
}


function actualizarIndicadorGuardado(
    estado,
    mensaje
) {

    const badge =
        document.getElementById(
            'saveStatusBadge'
        );


    if (!badge) return;


    switch (estado) {

        case 'saving':

            badge.innerHTML = `
                <i
                    class="fas fa-spinner fa-spin text-primary me-1"
                ></i>

                <span>
                    Guardando en MySQL...
                </span>
            `;

            break;


        case 'saved':

            badge.innerHTML = `
                <i
                    class="fas fa-circle-check text-success me-1"
                ></i>

                <span>
                    ${escapeHTML(
                        mensaje ||
                        'Guardado'
                    )}
                </span>
            `;

            break;


        case 'error':

            badge.innerHTML = `
                <i
                    class="fas fa-circle-exclamation text-danger me-1"
                ></i>

                <span class="text-danger">
                    ${escapeHTML(
                        mensaje ||
                        'Error'
                    )}
                </span>
            `;

            break;


        default:

            badge.innerHTML = `
                <i
                    class="fas fa-database text-primary me-1"
                ></i>

                <span>
                    Las marcas de asistencia se sincronizan con MySQL.
                </span>
            `;

            break;
    }
}


/* ============================================================
   13. MARCAR TODOS COMO ASISTIÓ
   ============================================================ */

async function marcarTodosAsistio() {

    if (
        !sesionActiva ||
        alumnosSesion.length === 0 ||
        guardandoEnServidor
    ) {

        return;
    }


    guardandoEnServidor = true;


    const btnMarcar =
        document.getElementById(
            'btnMarcarTodosAsistio'
        );


    if (btnMarcar) {

        btnMarcar.disabled = true;
    }


    alumnosSesion.forEach(
        a => {

            a.estado =
                'ASISTIO';

            actualizarFilaEstudiante(
                a
            );
        }
    );


    actualizarBarraResumen();


    actualizarIndicadorGuardado(
        'saving',
        `Guardando todos (0/${alumnosSesion.length})...`
    );


    const token =
        window.csrfToken ||
        document
            .querySelector(
                'meta[name="csrf-token"]'
            )
            ?.getAttribute(
                'content'
            ) ||
        '';


    let guardadosConExito = 0;


    const promesas =
        alumnosSesion.map(
            async alumno => {

                const payload = {

                    codHorario:
                        sesionActiva.codHorario ||
                        null,

                    codDocente:
                        String(
                            docenteActual ||
                            sesionActiva.codDocente ||
                            'DOCENTE'
                        ),

                    codAlumno:
                        String(
                            alumno.codAlumno
                        ),

                    fecha:
                        sesionActiva.fecha,

                    codCurso:
                        sesionActiva.codCurso,

                    secCurso:
                        sesionActiva.secCurso,

                    semestre:
                        sesionActiva.semestre ||
                        semestreActual,

                    estado:
                        'ASISTIO',

                    observacion:
                        alumno.observacion ||
                        ''
                };


                try {

                    const res =
                        await fetch(
                            API_URLS.asistenciaAlumno,
                            {
                                method:
                                    'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        token
                                },

                                credentials:
                                    'same-origin',

                                body:
                                    JSON.stringify(
                                        payload
                                    )
                            }
                        );


                    if (res.ok) {

                        guardadosConExito++;


                        actualizarIndicadorGuardado(
                            'saving',
                            `Guardando (${guardadosConExito}/${alumnosSesion.length})...`
                        );
                    }

                } catch (e) {

                    console.error(
                        'Error al guardar asistencia en lote:',
                        e
                    );
                }
            }
        );


    await Promise.all(
        promesas
    );


    const hora =
        new Date()
            .toLocaleTimeString(
                [],
                {
                    hour:
                        '2-digit',

                    minute:
                        '2-digit'
                }
            );


    actualizarIndicadorGuardado(
        'saved',
        `Todos los alumnos registrados como ASISTIÓ en MySQL (${hora})`
    );


    guardandoEnServidor =
        false;


    if (btnMarcar) {

        btnMarcar.disabled =
            false;
    }
}


/* ============================================================
   14. FILTRADO DE ALUMNOS
   ============================================================ */

function filtrarAlumnosModal(
    termino
) {

    const q =
        String(termino || '')
            .trim()
            .toLowerCase();


    const filas =
        document.querySelectorAll(
            '#tbodyAlumnosModal tr'
        );


    filas.forEach(
        fila => {

            const codAlumno =
                fila.id.replace(
                    'fila-alumno-',
                    ''
                );


            const alumno =
                alumnosSesion.find(
                    a =>
                        a.codAlumno ===
                        codAlumno
                );


            if (!alumno) return;


            if (!q) {

                fila.style.display =
                    '';

                return;
            }


            const match =
                alumno.codAlumno
                    .toLowerCase()
                    .includes(q)

                ||

                alumno.nombreCompleto
                    .toLowerCase()
                    .includes(q)

                ||

                (
                    alumno.email &&
                    alumno.email
                        .toLowerCase()
                        .includes(q)
                );


            fila.style.display =
                match
                    ? ''
                    : 'none';
        }
    );
}


/* ============================================================
   15. CONSTRUIR PARÁMETROS DE EXPORTACIÓN
   ============================================================ */

function construirParametrosExportacion() {

    if (!sesionActiva) {

        return null;
    }


    const params =
        new URLSearchParams();


    if (sesionActiva.codHorario) {

        params.set(
            'codHorario',
            sesionActiva.codHorario
        );
    }


    if (docenteActual) {

        params.set(
            'codDocente',
            docenteActual
        );

    } else if (
        sesionActiva.codDocente
    ) {

        params.set(
            'codDocente',
            sesionActiva.codDocente
        );
    }


    if (sesionActiva.codCurso) {

        params.set(
            'codCurso',
            sesionActiva.codCurso
        );
    }


    if (sesionActiva.secCurso) {

        params.set(
            'secCurso',
            sesionActiva.secCurso
        );
    }


    if (sesionActiva.semestre) {

        params.set(
            'semestre',
            sesionActiva.semestre
        );

    } else {

        params.set(
            'semestre',
            semestreActual
        );
    }


    if (sesionActiva.fecha) {

        params.set(
            'fecha',
            sesionActiva.fecha
        );
    }


    return params;
}


/* ============================================================
   16. EXPORTAR PDF
   ============================================================ */

function exportarPdfAsistencia() {

    /* --------------------------------------------------------
       VALIDACIÓN
       -------------------------------------------------------- */

    if (!sesionActiva) {

        mostrarAlertaAsistencia(
            'Primero selecciona una clase del calendario para exportar su asistencia.',
            'Selecciona una clase'
        );

        return;
    }


    /* --------------------------------------------------------
       CONSTRUIR PARÁMETROS
       -------------------------------------------------------- */

    const params =
        construirParametrosExportacion();


    if (!params) {

        mostrarAlertaAsistencia(
            'No se pudo obtener la información de la clase seleccionada.',
            'Datos incompletos'
        );

        return;
    }


    /* --------------------------------------------------------
       EXPORTACIÓN REAL
       -------------------------------------------------------- */

    window.location.href =
        `${EXPORT_URLS.pdf}?${params.toString()}`;
}


/* ============================================================
   17. EXPORTAR EXCEL REAL .XLSX
   ============================================================ */

function exportarExcelAsistencia() {

    /* --------------------------------------------------------
       VALIDACIÓN
       -------------------------------------------------------- */

    if (!sesionActiva) {

        mostrarAlertaAsistencia(
            'Primero selecciona una clase del calendario para exportar su asistencia.',
            'Selecciona una clase'
        );

        return;
    }


    /* --------------------------------------------------------
       CONSTRUIR PARÁMETROS
       -------------------------------------------------------- */

    const params =
        construirParametrosExportacion();


    if (!params) {

        mostrarAlertaAsistencia(
            'No se pudo obtener la información de la clase seleccionada.',
            'Datos incompletos'
        );

        return;
    }


    /* --------------------------------------------------------
       EXPORTACIÓN REAL XLSX
       -------------------------------------------------------- */

    window.location.href =
        `${EXPORT_URLS.excel}?${params.toString()}`;
}


/* ============================================================
   18. ALERTAS PERSONALIZADAS
   ============================================================ */

function mostrarAlertaAsistencia(
    mensaje =
        'Primero selecciona una clase del calendario para continuar.',
    titulo =
        'Selecciona una clase'
) {

    const alerta =
        document.getElementById(
            'alertaAsistencia'
        );


    const tituloElemento =
        document.getElementById(
            'alertaAsistenciaTitulo'
        );


    const mensajeElemento =
        document.getElementById(
            'alertaAsistenciaMensaje'
        );


    /* --------------------------------------------------------
       Si el HTML de la alerta no existe
       -------------------------------------------------------- */

    if (!alerta) {

        console.warn(
            'No existe #alertaAsistencia en el HTML.'
        );

        return;
    }


    /* --------------------------------------------------------
       Colocar título
       -------------------------------------------------------- */

    if (tituloElemento) {

        tituloElemento.textContent =
            titulo;
    }


    /* --------------------------------------------------------
       Colocar mensaje
       -------------------------------------------------------- */

    if (mensajeElemento) {

        mensajeElemento.textContent =
            mensaje;
    }


    /* --------------------------------------------------------
       Mostrar alerta
       -------------------------------------------------------- */

    alerta.classList.add(
        'mostrar'
    );


    /* --------------------------------------------------------
       Evitar scroll del fondo
       -------------------------------------------------------- */

    document.body.style.overflow =
        'hidden';


    /* --------------------------------------------------------
       Enfocar botón Entendido
       -------------------------------------------------------- */

    const btnAceptar =
        document.getElementById(
            'btnAceptarAlertaAsistencia'
        );


    if (btnAceptar) {

        setTimeout(
            () => {
                btnAceptar.focus();
            },
            100
        );
    }
}


function cerrarAlertaAsistencia() {

    const alerta =
        document.getElementById(
            'alertaAsistencia'
        );


    if (!alerta) {
        return;
    }


    alerta.classList.remove(
        'mostrar'
    );


    /* --------------------------------------------------------
       Devolver scroll al documento
       -------------------------------------------------------- */

    document.body.style.overflow =
        '';
}


/* ============================================================
   19. UTILITARIOS DE TIEMPO Y FORMATOS
   ============================================================ */

function obtenerHorasRango(
    rango
) {

    if (!rango) return null;


    const partes =
        String(rango)
            .trim()
            .split(
                /\s*(?:-|–|—|a|A|hasta)\s*/
            );


    if (
        partes.length < 2
    ) {

        return null;
    }


    const inicio =
        convertirHoraDecimal(
            partes[0]
        );


    const fin =
        convertirHoraDecimal(
            partes[1]
        );


    if (
        inicio === null ||
        fin === null
    ) {

        return null;
    }


    return {
        inicio,
        fin
    };
}


function convertirHoraDecimal(
    valor
) {

    if (
        valor === null ||
        valor === undefined
    ) {

        return null;
    }


    const texto =
        String(valor)
            .trim()
            .toUpperCase();


    const match =
        texto.match(
            /^(\d{1,2})(?::(\d{1,2}))?\s*(AM|PM)?$/
        );


    if (!match) {
        return null;
    }


    let horas =
        Number(match[1]);


    const minutos =
        Number(
            match[2] || 0
        );


    const periodo =
        match[3];


    if (
        periodo === 'PM' &&
        horas < 12
    ) {

        horas += 12;
    }


    if (
        periodo === 'AM' &&
        horas === 12
    ) {

        horas = 0;
    }


    return horas +
        (minutos / 60);
}


function convertirHoraAMinutos(
    valor
) {

    const decimal =
        convertirHoraDecimal(
            valor
        );


    if (decimal === null) {
        return null;
    }


    return Math.round(
        decimal * 60
    );
}


function mostrarCargaCalendario() {

    const tbody =
        document.getElementById(
            'tbodyAsistencia'
        );


    if (!tbody) return;


    tbody.innerHTML = `
        <tr>

            <td
                colspan="8"
                class="text-center py-5"
            >

                <div
                    class="spinner-border text-primary"
                    role="status"
                >

                    <span class="visually-hidden">
                        Cargando...
                    </span>

                </div>

                <div class="mt-2 text-muted fw-semibold">

                    Cargando programación y asistencias docentes...

                </div>

            </td>

        </tr>
    `;
}