/* ============================================================
   HORARIO DOCENTE
   ============================================================

   FUENTES JSON:

   horario.json
        |
        | codHorario
        v
   alumnos.json
        |
        | idHorario
        v
   LISTA DE ALUMNOS

   ============================================================ */


/* ============================================================
   CONFIGURACIÓN GENERAL
============================================================ */

const dias = [
    'LUNES',
    'MARTES',
    'MIÉRCOLES',
    'JUEVES',
    'VIERNES',
    'SÁBADO',
    'DOMINGO'
];


/* ============================================================
   MAPEO DE DÍAS
============================================================ */

const diasMap = {

    'LU': 'LUNES',
    'L': 'LUNES',

    'MA': 'MARTES',
    'MAR': 'MARTES',
    'M': 'MARTES',

    'MI': 'MIÉRCOLES',
    'MIE': 'MIÉRCOLES',
    'X': 'MIÉRCOLES',

    'JU': 'JUEVES',
    'J': 'JUEVES',

    'VI': 'VIERNES',
    'V': 'VIERNES',

    'SA': 'SÁBADO',
    'S': 'SÁBADO',

    'DO': 'DOMINGO',
    'D': 'DOMINGO'

};


/* ============================================================
   VARIABLES GLOBALES
============================================================ */

let horariosData = [];

let alumnosDataCache = [];

let cargandoDatos = false;

let semanaActual =
    obtenerInicioSemana(new Date());


window.currentAlumnosModal = [];

window.currentClaseModal = null;


/* ============================================================
   NORMALIZAR DÍA
============================================================ */

function normalizarDia(dia) {

    if (!dia) {
        return '';
    }

    const valor =
        String(dia)
            .trim()
            .toUpperCase();

    return diasMap[valor] || valor;

}


/* ============================================================
   CONVERTIR HORA A MINUTOS
============================================================ */

function horaAMinutos(hora) {

    if (!hora) {
        return null;
    }

    const texto =
        String(hora)
            .trim();

    const partes =
        texto.split(':');

    const horas =
        parseInt(
            partes[0],
            10
        );

    const minutos =
        partes.length > 1
            ? parseInt(partes[1], 10)
            : 0;

    if (
        isNaN(horas) ||
        isNaN(minutos)
    ) {

        return null;

    }

    return (
        horas * 60 +
        minutos
    );

}


/* ============================================================
   OBTENER RANGO DEL HORARIO
============================================================ */

function obtenerRangoHorario(horario) {

    if (!horario) {
        return null;
    }

    const partes =
        String(horario)
            .split('-')
            .map(
                parte =>
                    parte.trim()
            );

    if (
        partes.length < 2
    ) {

        return null;

    }

    const inicio =
        horaAMinutos(
            partes[0]
        );

    const fin =
        horaAMinutos(
            partes[1]
        );

    if (
        inicio === null ||
        fin === null ||
        fin <= inicio
    ) {

        return null;

    }

    return {

        inicio: inicio,

        fin: fin

    };

}


/* ============================================================
   FORMATEAR HORA
============================================================ */

function formatearHora(minutos) {

    const horas =
        Math.floor(
            minutos / 60
        );

    const mins =
        minutos % 60;

    return (
        String(horas)
            .padStart(2, '0')
        +
        ':'
        +
        String(mins)
            .padStart(2, '0')
    );

}


/* ============================================================
   INICIO DE SEMANA
============================================================ */

function obtenerInicioSemana(fecha) {

    const nuevaFecha =
        new Date(fecha);

    nuevaFecha.setHours(
        0,
        0,
        0,
        0
    );

    const dia =
        nuevaFecha.getDay();

    const diferencia =
        dia === 0
            ? -6
            : 1 - dia;

    nuevaFecha.setDate(
        nuevaFecha.getDate() +
        diferencia
    );

    return nuevaFecha;

}


/* ============================================================
   SUMAR DÍAS
============================================================ */

function sumarDias(
    fecha,
    cantidad
) {

    const nuevaFecha =
        new Date(fecha);

    nuevaFecha.setDate(
        nuevaFecha.getDate() +
        cantidad
    );

    return nuevaFecha;

}


/* ============================================================
   FECHA ISO
============================================================ */

function formatearFechaISO(fecha) {

    const año =
        fecha.getFullYear();

    const mes =
        String(
            fecha.getMonth() + 1
        )
        .padStart(2, '0');

    const dia =
        String(
            fecha.getDate()
        )
        .padStart(2, '0');

    return (
        `${año}-${mes}-${dia}`
    );

}


/* ============================================================
   EVENTO INICIAL
============================================================ */

document.addEventListener(
    'DOMContentLoaded',
    () => {

        cargarDatosCompletos();


        /* ====================================================
           CAMBIO DE SEMESTRE
        ==================================================== */

        const selectSemestre =
            document.getElementById(
                'selectSemestre'
            );


        if (selectSemestre) {

            selectSemestre.addEventListener(
                'change',
                () => {

                    if (
                        !cargandoDatos
                    ) {

                        cargarDatosCompletos();

                    }

                }
            );

        }


        /* ====================================================
           SEMANA ANTERIOR
        ==================================================== */

        const btnAnterior =
            document.getElementById(
                'btnSemanaAnterior'
            );


        if (btnAnterior) {

            btnAnterior.addEventListener(
                'click',
                () => {

                    semanaActual =
                        sumarDias(
                            semanaActual,
                            -7
                        );

                    renderizarTablaHorario();

                }
            );

        }


        /* ====================================================
           SEMANA SIGUIENTE
        ==================================================== */

        const btnSiguiente =
            document.getElementById(
                'btnSemanaSiguiente'
            );


        if (btnSiguiente) {

            btnSiguiente.addEventListener(
                'click',
                () => {

                    semanaActual =
                        sumarDias(
                            semanaActual,
                            7
                        );

                    renderizarTablaHorario();

                }
            );

        }


        /* ====================================================
           SEMANA ACTUAL
        ==================================================== */

        const btnActual =
            document.getElementById(
                'btnSemanaActual'
            );


        if (btnActual) {

            btnActual.addEventListener(
                'click',
                () => {

                    semanaActual =
                        obtenerInicioSemana(
                            new Date()
                        );

                    renderizarTablaHorario();

                }
            );

        }

    }
);


/* ============================================================
   CARGAR JSON
============================================================ */

async function cargarDatosCompletos() {

    if (cargandoDatos) {
        return;
    }

    cargandoDatos = true;


    const selectSemestre =
        document.getElementById(
            'selectSemestre'
        );


    const valorRaw =
        selectSemestre
            ? selectSemestre.value
            : '20261';


    const semestreSeleccionado =
        String(valorRaw)
            .replace('-', '')
            .replace('I', '1')
            .replace('II', '2');


    try {

        /* ====================================================
           URL HORARIO
        ==================================================== */

        const urlHorario =
            window.urlHorarioJson ||
            '/json/horario.json';


        /* ====================================================
           URL ALUMNOS
        ==================================================== */

        const urlAlumnos =
            window.urlAlumnosJson ||
            '/json/alumnos.json';


        /* ====================================================
           CARGAR AMBOS JSON
        ==================================================== */

        const [
            resHorarios,
            resAlumnos
        ] = await Promise.all([

            fetch(
                urlHorario,
                {
                    cache: 'no-store'
                }
            ),

            fetch(
                urlAlumnos,
                {
                    cache: 'no-store'
                }
            )

        ]);


        /* ====================================================
           VALIDAR HORARIO
        ==================================================== */

        if (!resHorarios.ok) {

            throw new Error(
                'No se pudo cargar horario.json'
            );

        }


        /* ====================================================
           VALIDAR ALUMNOS
        ==================================================== */

        if (!resAlumnos.ok) {

            throw new Error(
                'No se pudo cargar alumnos.json'
            );

        }


        /* ====================================================
           CONVERTIR JSON
        ==================================================== */

        const dataHorarios =
            await resHorarios.json();


        alumnosDataCache =
            await resAlumnos.json();


        /* ====================================================
           VALIDAR ARRAYS
        ==================================================== */

        if (
            !Array.isArray(
                dataHorarios
            )
        ) {

            throw new Error(
                'horario.json debe contener un arreglo'
            );

        }


        if (
            !Array.isArray(
                alumnosDataCache
            )
        ) {

            throw new Error(
                'alumnos.json debe contener un arreglo'
            );

        }


        /* ====================================================
           FILTRAR SEMESTRE
        ==================================================== */

        horariosData =
            dataHorarios.filter(
                item =>
                    String(
                        item.semestre
                    ) ===
                    semestreSeleccionado
            );


        /* ====================================================
           CONSOLA
        ==================================================== */

        console.log(
            '================================'
        );

        console.log(
            'HORARIO DOCENTE'
        );

        console.log(
            'Semestre:',
            semestreSeleccionado
        );

        console.log(
            'Horarios:',
            horariosData
        );

        console.log(
            'Alumnos:',
            alumnosDataCache
        );

        console.log(
            '================================'
        );


        /* ====================================================
           RENDERIZAR
        ==================================================== */

        renderizarTablaHorario();


    } catch (error) {

        console.error(
            'Error al cargar los datos:',
            error
        );


        mostrarErrorHorario(
            error.message
        );


    } finally {

        cargandoDatos = false;

    }

}


/* ============================================================
   CREAR MAPA DE HORARIOS
============================================================ */

function construirMapaHorario() {

    const mapa = {};


    dias.forEach(
        dia => {

            mapa[dia] = [];

        }
    );


    horariosData.forEach(
        clase => {

            const dia =
                normalizarDia(
                    clase.dia
                );


            const rango =
                obtenerRangoHorario(
                    clase.hora
                );


            if (
                !dia ||
                !rango
            ) {

                return;

            }


            if (
                !mapa[dia]
            ) {

                return;

            }


            mapa[dia].push({

                ...clase,

                _inicio:
                    rango.inicio,

                _fin:
                    rango.fin

            });

        }
    );


    dias.forEach(
        dia => {

            mapa[dia].sort(
                (
                    a,
                    b
                ) =>
                    a._inicio -
                    b._inicio
            );

        }
    );


    return mapa;

}


/* ============================================================
   GENERAR FILAS HORARIAS
============================================================ */

function generarFilasHorario() {

    const filas = [];


    /*
     * HORARIO:
     *
     * 07:00
     * hasta
     * 23:00
     *
     * Cada fila = 1 hora
     */


    for (
        let minutos = 7 * 60;
        minutos < 23 * 60;
        minutos += 60
    ) {

        filas.push({

            inicio:
                minutos,

            fin:
                minutos + 60

        });

    }


    return filas;

}


/* ============================================================
   RENDERIZAR CALENDARIO
============================================================ */

function renderizarTablaHorario() {

    const tbody =
        document.getElementById(
            'tbodyHorario'
        );


    if (!tbody) {

        console.error(
            'No existe #tbodyHorario'
        );

        return;

    }


    tbody.innerHTML = '';


    /* ========================================================
       TÍTULO DE SEMANA
    ======================================================== */

    actualizarTituloSemana();


    /* ========================================================
       MAPA
    ======================================================== */

    const mapa =
        construirMapaHorario();


    /* ========================================================
       FILAS
    ======================================================== */

    const filas =
        generarFilasHorario();


    let totalHoras = 0;


    /* ========================================================
       CONTROL DE CELDAS OCUPADAS
    ======================================================== */

    const ocupadas = {};


    dias.forEach(
        dia => {

            ocupadas[dia] =
                new Set();

        }
    );


    /* ========================================================
       RECORRER HORAS
    ======================================================== */

    filas.forEach(
        (
            fila,
            indiceFila
        ) => {

            const tr =
                document.createElement(
                    'tr'
                );


            /* =================================================
               COLUMNA HORA
            ================================================= */

            const tdHora =
                document.createElement(
                    'td'
                );


            tdHora.className =
                'celda-hora';


            tdHora.innerHTML = `

                <div class="hora-inicio">
                    ${formatearHora(
                        fila.inicio
                    )}
                </div>

                <div class="hora-separador">
                    a
                </div>

                <div class="hora-fin">
                    ${formatearHora(
                        fila.fin
                    )}
                </div>

            `;


            tr.appendChild(
                tdHora
            );


            /* =================================================
               RECORRER DÍAS
            ================================================= */

            dias.forEach(
                dia => {

                    /*
                     * Si la posición ya está
                     * ocupada por un rowspan,
                     * no crear otra celda.
                     */

                    if (
                        ocupadas[dia]
                            .has(
                                indiceFila
                            )
                    ) {

                        return;

                    }


                    const td =
                        document.createElement(
                            'td'
                        );


                    td.className =
                        'celda-dia';


                    /* =========================================
                       FIN DE SEMANA
                    ========================================= */

                    if (
                        dia === 'SÁBADO' ||
                        dia === 'DOMINGO'
                    ) {

                        td.classList.add(
                            'celda-fin-semana'
                        );

                    }


                    /* =========================================
                       BUSCAR CLASE
                    ========================================= */

                    const clase =
                        (
                            mapa[dia] ||
                            []
                        ).find(
                            item => {

                                return (
                                    item._inicio ===
                                    fila.inicio
                                );

                            }
                        );


                    /* =========================================
                       EXISTE CLASE
                    ========================================= */

                    if (clase) {

                        const duracionMinutos =
                            clase._fin -
                            clase._inicio;


                        const duracionFilas =
                            Math.ceil(
                                duracionMinutos /
                                60
                            );


                        /* =====================================
                           ROWSPAN
                        ===================================== */

                        td.rowSpan =
                            duracionFilas;


                        /* =====================================
                           MARCAR CELDAS
                        ===================================== */

                        for (
                            let i = 0;
                            i < duracionFilas;
                            i++
                        ) {

                            ocupadas[dia]
                                .add(
                                    indiceFila + i
                                );

                        }


                        /* =====================================
                           CARGA HORARIA
                        ===================================== */

                        totalHoras +=
                            duracionMinutos /
                            60;


                        /* =====================================
                           CANTIDAD DE ALUMNOS
                        ===================================== */

                        const idHorarioBuscado =
                            Number(
                                clase.codHorario
                            );


                        const cantidadAlumnos =
                            alumnosDataCache.filter(
                                alumno =>
                                    Number(
                                        alumno.idHorario
                                    ) ===
                                    idHorarioBuscado
                            ).length;


                        /* =====================================
                           CLASE F / D
                        ===================================== */

                        const esEstiloClaro =
                            String(
                                clase.secCurso ||
                                ''
                            )
                            .toUpperCase() === 'F' ||
                            String(
                                clase.secCurso ||
                                ''
                            )
                            .toUpperCase() === 'D';


                        /* =====================================
                           BLOQUE
                        ===================================== */

                        const bloque =
                            document.createElement(
                                'div'
                            );


                        bloque.className =
                            'bloque-clase';


                        if (
                            esEstiloClaro
                        ) {

                            bloque.classList.add(
                                'bloque-clase-claro'
                            );

                        }


                        bloque.title =
                            'Consultar alumnos matriculados';


                        bloque.innerHTML = `

                            <div class="bloque-cabecera">

                                <div class="bloque-curso">

                                    <i class="fas fa-book-open"></i>

                                    <span>
                                        ${escapeHTML(
                                            clase.codCurso ||
                                            'CURSO'
                                        )}
                                    </span>

                                </div>

                            </div>


                            <div class="bloque-seccion">

                                Sección
                                <strong>
                                    ${escapeHTML(
                                        clase.secCurso ||
                                        '-'
                                    )}
                                </strong>

                            </div>


                            <div class="bloque-aula">

                                <i class="fas fa-location-dot"></i>

                                <span>
                                    ${escapeHTML(
                                        clase.codAula ||
                                        'AULA'
                                    )}
                                </span>

                            </div>


                            <div class="bloque-tipo">

                                <i class="fas fa-chalkboard"></i>

                                <span>
                                    ${escapeHTML(
                                        clase.teopra ||
                                        'CLASE'
                                    )}
                                </span>

                            </div>


                            <div class="bloque-alumnos">

                                <i class="fas fa-users"></i>

                                <span>
                                    ${cantidadAlumnos}
                                    alumnos
                                </span>

                            </div>


                            <div class="bloque-hora">

                                <i class="far fa-clock"></i>

                                ${escapeHTML(
                                    clase.hora ||
                                    ''
                                )}

                            </div>


                            <div class="bloque-consultar">

                                <i class="fas fa-eye"></i>

                                Ver alumnos

                            </div>

                        `;


                        /* =====================================
                           CLICK
                        ===================================== */

                        bloque.addEventListener(
                            'click',
                            () => {

                                cargarYMostrarAlumnos(
                                    clase
                                );

                            }
                        );


                        td.appendChild(
                            bloque
                        );

                    }


                    /* =========================================
                       AGREGAR CELDA
                    ========================================= */

                    tr.appendChild(
                        td
                    );

                }
            );


            tbody.appendChild(
                tr
            );

        }
    );


    /* ========================================================
       ACTUALIZAR CARGA HORARIA
    ======================================================== */

    const lblCarga =
        document.getElementById(
            'lblCargaHoraria'
        );


    if (lblCarga) {

        const horasRedondeadas =
            Number.isInteger(
                totalHoras
            )
                ? totalHoras
                : totalHoras.toFixed(1);


        lblCarga.textContent =
            `${horasRedondeadas} HRS`;

    }

}


/* ============================================================
   TÍTULO DE SEMANA
============================================================ */

function actualizarTituloSemana() {

    const inicio =
        semanaActual;


    const fin =
        sumarDias(
            inicio,
            6
        );


    const textoInicio =
        inicio.toLocaleDateString(
            'es-PE',
            {
                day: '2-digit',
                month: 'short'
            }
        );


    const textoFin =
        fin.toLocaleDateString(
            'es-PE',
            {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }
        );


    const lblSemana =
        document.getElementById(
            'lblSemana'
        );


    if (lblSemana) {

        lblSemana.textContent =
            `${textoInicio} - ${textoFin}`;

    }

}


/* ============================================================
   MODAL DE ALUMNOS
============================================================ */

async function cargarYMostrarAlumnos(
    clase
) {

    window.currentClaseModal =
        clase;


    /* ========================================================
       TÍTULO
    ======================================================== */

    const tituloModal =
        document.getElementById(
            'modalCursoTitulo'
        );


    if (tituloModal) {

        tituloModal.textContent =
            `${clase.codCurso || ''}-${clase.secCurso || ''} / ` +
            `${clase.codAula || ''} | ` +
            `Semestre: ${clase.semestre || ''}`;

    }


    /* ========================================================
       CONTENEDOR
    ======================================================== */

    const container =
        document.getElementById(
            'listaAlumnosContainer'
        );


    if (container) {

        container.innerHTML = `

            <div class="text-center py-5">

                <div
                    class="spinner-border"
                    style="color:#7A0019"
                ></div>

                <div class="mt-3 text-muted">

                    Cargando alumnos...

                </div>

            </div>

        `;

    }


    /* ========================================================
       MODAL
    ======================================================== */

    const modalElement =
        document.getElementById(
            'modalAlumnos'
        );


    if (
        modalElement &&
        typeof bootstrap !== 'undefined'
    ) {

        const myModal =
            bootstrap.Modal
                .getOrCreateInstance(
                    modalElement
                );


        myModal.show();

    }


    try {

        /* ====================================================
           RECUPERAR ALUMNOS
        ==================================================== */

        let todosLosAlumnos =
            alumnosDataCache;


        if (
            !todosLosAlumnos ||
            todosLosAlumnos.length === 0
        ) {

            const urlAlumnos =
                window.urlAlumnosJson ||
                '/json/alumnos.json';


            const resAlumnos =
                await fetch(
                    urlAlumnos,
                    {
                        cache: 'no-store'
                    }
                );


            if (!resAlumnos.ok) {

                throw new Error(
                    'No se pudo cargar alumnos.json'
                );

            }


            todosLosAlumnos =
                await resAlumnos.json();


            alumnosDataCache =
                todosLosAlumnos;

        }


        /* ====================================================
           BUSCAR POR HORARIO
        ==================================================== */

        const idHorarioBuscado =
            Number(
                clase.codHorario
            );


        window.currentAlumnosModal =
            todosLosAlumnos.filter(
                alumno =>
                    Number(
                        alumno.idHorario
                    ) ===
                    idHorarioBuscado
            );


        const total =
            window.currentAlumnosModal.length;


        /* ====================================================
           CANTIDAD
        ==================================================== */

        const lblCantidad =
            document.getElementById(
                'modalCantidadAlumnos'
            );


        if (lblCantidad) {

            lblCantidad.textContent =
                `Cantidad de alumnos: ${total}`;

        }


        if (!container) {
            return;
        }


        container.innerHTML = '';


        /* ====================================================
           SIN ALUMNOS
        ==================================================== */

        if (
            total === 0
        ) {

            container.innerHTML = `

                <div class="text-center py-5">

                    <i
                        class="fas fa-user-slash fa-2x mb-3"
                        style="color:#7A0019"
                    ></i>

                    <p class="fw-semibold mb-1">

                        No hay alumnos matriculados

                    </p>

                    <small class="text-muted">

                        No se encontraron alumnos
                        asociados a este horario.

                    </small>

                </div>

            `;

            return;

        }


        /* ====================================================
           ESTADÍSTICAS
        ==================================================== */

        const presentes =
            window.currentAlumnosModal.filter(
                alumno =>
                    String(
                        alumno.asistencia || ''
                    )
                    .toUpperCase() ===
                    'PRESENTE'
            ).length;


        const faltas =
            window.currentAlumnosModal.filter(
                alumno =>
                    String(
                        alumno.asistencia || ''
                    )
                    .toUpperCase() ===
                    'FALTO'
            ).length;


        const tardanzas =
            window.currentAlumnosModal.filter(
                alumno =>
                    String(
                        alumno.asistencia || ''
                    )
                    .toUpperCase() ===
                    'TARDANZA'
            ).length;


        /* ====================================================
           RESUMEN
        ==================================================== */

        const resumen =
            document.createElement(
                'div'
            );


        resumen.className =
            'row g-3 mb-4';


        resumen.innerHTML = `

            <div class="col-md-3">

                <div class="resumen-alumno">

                    <div class="resumen-icono">

                        <i class="fas fa-users"></i>

                    </div>

                    <div>

                        <small>
                            TOTAL
                        </small>

                        <strong>
                            ${total}
                        </strong>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="resumen-alumno">

                    <div class="resumen-icono">

                        <i class="fas fa-check-circle"></i>

                    </div>

                    <div>

                        <small>
                            PRESENTES
                        </small>

                        <strong>
                            ${presentes}
                        </strong>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="resumen-alumno">

                    <div class="resumen-icono">

                        <i class="fas fa-times-circle"></i>

                    </div>

                    <div>

                        <small>
                            FALTAS
                        </small>

                        <strong>
                            ${faltas}
                        </strong>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="resumen-alumno">

                    <div class="resumen-icono">

                        <i class="fas fa-clock"></i>

                    </div>

                    <div>

                        <small>
                            TARDANZAS
                        </small>

                        <strong>
                            ${tardanzas}
                        </strong>

                    </div>

                </div>

            </div>

        `;


        container.appendChild(
            resumen
        );


        /* ====================================================
           LISTA
        ==================================================== */

        const lista =
            document.createElement(
                'div'
            );


        lista.className =
            'lista-alumnos';


        window.currentAlumnosModal
            .forEach(
                (
                    al,
                    index
                ) => {

                    const item =
                        document.createElement(
                            'div'
                        );


                    item.className =
                        'alumno-item';


                    const asistencia =
                        String(
                            al.asistencia ||
                            'SIN REGISTRO'
                        )
                        .toUpperCase();


                    const claseEstado =
                        obtenerClaseEstadoAlumno(
                            asistencia
                        );


                    item.innerHTML = `

                        <div class="alumno-principal">

                            <div class="alumno-numero">

                                ${index + 1}

                            </div>


                            <div class="alumno-datos">

                                <div class="alumno-nombre">

                                    <i class="far fa-user"></i>

                                    ${escapeHTML(
                                        al.nombres ||
                                        '--'
                                    )}

                                </div>


                                <div class="alumno-correo">

                                    <i class="fas fa-envelope"></i>

                                    ${escapeHTML(
                                        al.correo ||
                                        '--'
                                    )}

                                </div>

                            </div>

                        </div>


                        <div class="alumno-extra">

                            <div class="alumno-codigo">

                                Código:

                                <strong>

                                    ${escapeHTML(
                                        al.codigoEstudiante ||
                                        '--'
                                    )}

                                </strong>

                            </div>


                            <span
                                class="estado-alumno ${claseEstado}"
                            >

                                ${escapeHTML(
                                    asistencia
                                )}

                            </span>


                            ${
                                al.observacion
                                    ? `

                                        <span class="observacion-alumno">

                                            ${escapeHTML(
                                                al.observacion
                                            )}

                                        </span>

                                    `
                                    : ''
                            }

                        </div>

                    `;


                    lista.appendChild(
                        item
                    );

                }
            );


        container.appendChild(
            lista
        );


    } catch (err) {

        console.error(
            'Error al procesar alumnos:',
            err
        );


        if (container) {

            container.innerHTML = `

                <div class="text-center py-5">

                    <i
                        class="fas fa-circle-exclamation fa-2x text-danger mb-3"
                    ></i>

                    <p class="text-danger fw-semibold">

                        Error al cargar la lista de alumnos.

                    </p>

                    <small class="text-muted">

                        ${escapeHTML(
                            err.message
                        )}

                    </small>

                </div>

            `;

        }

    }

}


/* ============================================================
   ESTADO DE ALUMNO
============================================================ */

function obtenerClaseEstadoAlumno(
    asistencia
) {

    switch (
        String(
            asistencia
        )
        .toUpperCase()
    ) {

        case 'PRESENTE':

            return 'estado-presente';


        case 'FALTO':

            return 'estado-falto';


        case 'TARDANZA':

            return 'estado-tardanza';


        case 'JUSTIFICADO':

            return 'estado-justificado';


        default:

            return 'estado-sin-registro';

    }

}


/* ============================================================
   EXPORTAR EXCEL / CSV
============================================================ */

function exportarExcelAlumnos() {

    if (
        !window.currentAlumnosModal ||
        window.currentAlumnosModal.length === 0
    ) {

        alert(
            'No hay alumnos para exportar en esta sección.'
        );

        return;

    }


    let csvContent =
        'data:text/csv;charset=utf-8,\uFEFF';


    csvContent +=
        'N°,Codigo,Nombres,Correo,Asistencia,Observacion\r\n';


    window.currentAlumnosModal.forEach(
        (
            al,
            index
        ) => {

            const fila = [

                index + 1,

                `"${limpiarCSV(
                    al.codigoEstudiante
                )}"`,

                `"${limpiarCSV(
                    al.nombres
                )}"`,

                `"${limpiarCSV(
                    al.correo
                )}"`,

                `"${limpiarCSV(
                    al.asistencia
                )}"`,

                `"${limpiarCSV(
                    al.observacion
                )}"`

            ].join(',');


            csvContent +=
                fila +
                '\r\n';

        }
    );


    const encodedUri =
        encodeURI(
            csvContent
        );


    const link =
        document.createElement(
            'a'
        );


    link.setAttribute(
        'href',
        encodedUri
    );


    link.setAttribute(
        'download',
        `lista_alumnos_${

            window.currentClaseModal?.codCurso ||
            'curso'

        }_Seccion_${

            window.currentClaseModal?.secCurso ||
            ''

        }.csv`
    );


    document.body.appendChild(
        link
    );


    link.click();


    document.body.removeChild(
        link
    );

}


/* ============================================================
   LIMPIAR CSV
============================================================ */

function limpiarCSV(valor) {

    return String(
        valor ?? ''
    )
    .replaceAll(
        '"',
        '""'
    );

}


/* ============================================================
   EXPORTAR PDF
============================================================ */

function exportarPDFAlumnos() {

    if (
        !window.currentAlumnosModal ||
        window.currentAlumnosModal.length === 0
    ) {

        alert(
            'No hay alumnos para exportar en esta sección.'
        );

        return;

    }


    const clase =
        window.currentClaseModal ||
        {};


    const ventanaImpresion =
        window.open(
            '',
            '_blank'
        );


    if (!ventanaImpresion) {

        alert(
            'El navegador bloqueó la ventana de impresión.'
        );

        return;

    }


    let html = `

        <!DOCTYPE html>

        <html lang="es">

        <head>

            <meta charset="UTF-8">

            <title>

                Lista de Alumnos -
                ${escapeHTML(
                    clase.codCurso ||
                    ''
                )}

            </title>


            <style>

                * {
                    box-sizing: border-box;
                }


                body {

                    font-family:
                        Arial,
                        Helvetica,
                        sans-serif;

                    margin: 35px;

                    color: #222;

                    background: #fff;

                }


                .header {

                    border-bottom:
                        4px solid #C9A227;

                    padding-bottom:
                        15px;

                    margin-bottom:
                        20px;

                }


                .institution {

                    color:
                        #7A0019;

                    font-size:
                        12px;

                    font-weight:
                        bold;

                    letter-spacing:
                        .6px;

                }


                h1 {

                    color:
                        #560012;

                    margin:
                        6px 0;

                    font-size:
                        22px;

                }


                .subtitulo {

                    color:
                        #666;

                    font-size:
                        12px;

                }


                .info {

                    display:
                        grid;

                    grid-template-columns:
                        repeat(
                            4,
                            1fr
                        );

                    gap:
                        10px;

                    margin-bottom:
                        20px;

                }


                .info-box {

                    border:
                        1px solid #ddd;

                    padding:
                        10px;

                    border-radius:
                        6px;

                }


                .info-label {

                    display:
                        block;

                    font-size:
                        9px;

                    color:
                        #777;

                    font-weight:
                        bold;

                    margin-bottom:
                        3px;

                }


                .info-value {

                    color:
                        #560012;

                    font-size:
                        12px;

                    font-weight:
                        bold;

                }


                table {

                    width:
                        100%;

                    border-collapse:
                        collapse;

                    margin-top:
                        10px;

                }


                th,
                td {

                    border:
                        1px solid #d7d1ca;

                    padding:
                        8px;

                    font-size:
                        10px;

                    text-align:
                        left;

                }


                th {

                    background:
                        #7A0019;

                    color:
                        white;

                    font-weight:
                        bold;

                }


                tr:nth-child(even) {

                    background:
                        #faf8f5;

                }


                .footer {

                    margin-top:
                        30px;

                    padding-top:
                        10px;

                    border-top:
                        1px solid #ddd;

                    font-size:
                        9px;

                    color:
                        #777;

                }


                @media print {

                    body {

                        margin:
                            20px;

                    }

                }

            </style>

        </head>


        <body>


            <div class="header">

                <div class="institution">

                    UNIVERSIDAD NACIONAL DE INGENIERÍA

                </div>


                <h1>

                    Lista de alumnos

                </h1>


                <div class="subtitulo">

                    Control académico de sección

                </div>

            </div>


            <div class="info">

                <div class="info-box">

                    <span class="info-label">
                        CURSO
                    </span>

                    <span class="info-value">

                        ${escapeHTML(
                            clase.codCurso ||
                            '--'
                        )}

                    </span>

                </div>


                <div class="info-box">

                    <span class="info-label">
                        SECCIÓN
                    </span>

                    <span class="info-value">

                        ${escapeHTML(
                            clase.secCurso ||
                            '--'
                        )}

                    </span>

                </div>


                <div class="info-box">

                    <span class="info-label">
                        AULA
                    </span>

                    <span class="info-value">

                        ${escapeHTML(
                            clase.codAula ||
                            '--'
                        )}

                    </span>

                </div>


                <div class="info-box">

                    <span class="info-label">
                        TOTAL ALUMNOS
                    </span>

                    <span class="info-value">

                        ${window.currentAlumnosModal.length}

                    </span>

                </div>

            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            N°
                        </th>

                        <th>
                            Código
                        </th>

                        <th>
                            Nombres y Apellidos
                        </th>

                        <th>
                            Correo
                        </th>

                        <th>
                            Asistencia
                        </th>

                        <th>
                            Observación
                        </th>

                    </tr>

                </thead>


                <tbody>

    `;


    window.currentAlumnosModal.forEach(
        (
            al,
            index
        ) => {

            html += `

                <tr>

                    <td>
                        ${index + 1}
                    </td>

                    <td>
                        ${escapeHTML(
                            al.codigoEstudiante ||
                            ''
                        )}
                    </td>

                    <td>
                        ${escapeHTML(
                            al.nombres ||
                            ''
                        )}
                    </td>

                    <td>
                        ${escapeHTML(
                            al.correo ||
                            ''
                        )}
                    </td>

                    <td>
                        ${escapeHTML(
                            al.asistencia ||
                            ''
                        )}
                    </td>

                    <td>
                        ${escapeHTML(
                            al.observacion ||
                            ''
                        )}
                    </td>

                </tr>

            `;

        }
    );


    html += `

                </tbody>

            </table>


            <div class="footer">

                Documento generado desde
                el sistema de gestión académica.

                <br>

                Fecha de generación:
                ${new Date().toLocaleDateString(
                    'es-PE'
                )}

            </div>


            <script>

                window.onload =
                    function() {

                        window.print();

                    };

            <\/script>


        </body>

        </html>

    `;


    ventanaImpresion.document.write(
        html
    );


    ventanaImpresion.document.close();

}


/* ============================================================
   MOSTRAR ERROR
============================================================ */

function mostrarErrorHorario(
    mensaje
) {

    const tbody =
        document.getElementById(
            'tbodyHorario'
        );


    if (!tbody) {
        return;
    }


    tbody.innerHTML = `

        <tr>

            <td
                colspan="8"
                class="text-center py-5"
            >

                <i
                    class="fas fa-circle-exclamation fa-2x text-danger mb-3"
                ></i>


                <div
                    class="fw-bold text-danger"
                >

                    No se pudo cargar
                    el horario.

                </div>


                <small class="text-muted">

                    ${escapeHTML(
                        mensaje
                    )}

                </small>

            </td>

        </tr>

    `;

}


/* ============================================================
   ESCAPAR HTML
============================================================ */

function escapeHTML(valor) {

    return String(
        valor ?? ''
    )
    .replaceAll(
        '&',
        '&amp;'
    )
    .replaceAll(
        '<',
        '&lt;'
    )
    .replaceAll(
        '>',
        '&gt;'
    )
    .replaceAll(
        '"',
        '&quot;'
    )
    .replaceAll(
        "'",
        '&#039;'
    );

}


/* ============================================================
   DEBUG
============================================================ */

window.horarioDocenteDebug =
    function () {

        console.log(
            '================================='
        );

        console.log(
            'DEBUG HORARIO DOCENTE'
        );

        console.log(
            '================================='
        );


        console.log(
            'Horarios:',
            horariosData
        );


        console.log(
            'Alumnos:',
            alumnosDataCache
        );


        console.log(
            'Semana:',
            semanaActual
        );


        console.log(
            'Clase seleccionada:',
            window.currentClaseModal
        );


        console.log(
            'Alumnos modal:',
            window.currentAlumnosModal
        );

    };


/* ============================================================
   FIN
============================================================ */

console.log(
    'horario-docente.js cargado correctamente'
);