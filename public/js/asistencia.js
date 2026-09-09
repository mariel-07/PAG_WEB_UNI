/* =========================================================
   ASISTENCIA DOCENTE
   DATOS DESDE LARAVEL / MYSQL
========================================================= */


/* =========================================================
   DÍAS
========================================================= */

const DIAS = [
    'LUNES',
    'MARTES',
    'MIÉRCOLES',
    'JUEVES',
    'VIERNES',
    'SÁBADO',
    'DOMINGO'
];

const MAPA_DIAS = {
    LU: 'LUNES',
    MA: 'MARTES',
    MI: 'MIÉRCOLES',
    JU: 'JUEVES',
    VI: 'VIERNES',
    SA: 'SÁBADO',
    DO: 'DOMINGO',

    LUNES: 'LUNES',
    MARTES: 'MARTES',
    MIERCOLES: 'MIÉRCOLES',
    MIÉRCOLES: 'MIÉRCOLES',
    JUEVES: 'JUEVES',
    VIERNES: 'VIERNES',
    SABADO: 'SÁBADO',
    SÁBADO: 'SÁBADO',
    DOMINGO: 'DOMINGO'
};

const NUMERO_DIA = {
    LUNES: 1,
    MARTES: 2,
    'MIÉRCOLES': 3,
    JUEVES: 4,
    VIERNES: 5,
    'SÁBADO': 6,
    DOMINGO: 0
};


/* =========================================================
   URL DE LAS APIS
========================================================= */

const urlAsistencia =
    window.asistenciaApi?.asistencias ||
    '/api/asistencias';

const urlHorario =
    window.asistenciaApi?.horarios ||
    '/api/horarios';


/*
 * IMPORTANTE:
 *
 * Ya NO usamos:
 *
 * /api/alumnos?codDocente=...
 *
 * Los alumnos serán obtenidos mediante:
 *
 * /api/asistencias
 * ?semestre=20262
 * &codCurso=BIC01
 * &secCurso=F
 *
 * porque AsistenciaController ya hace:
 *
 * matricula -> alumnos
 */


/* =========================================================
   VARIABLES
========================================================= */

let asistenciaData = [];
let horarioData = [];
let alumnosData = [];

let semanaActual =
    obtenerInicioSemana(new Date());

let docenteActual = null;

let nombreDocenteActual =
    'Docente';

let semestreActual =
    '20262';

let registroSeleccionado =
    null;


/* =========================================================
   STORAGE
========================================================= */

const STORAGE_KEY =
    'asistencia_alumnos_docente_v1';

let asistenciasGuardadas =
    cargarAsistenciasLocales();


/* =========================================================
   INICIO
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        console.log('=================================');
        console.log('INICIANDO ASISTENCIA DOCENTE');
        console.log('=================================');

        console.log(
            'API asistencias:',
            urlAsistencia
        );

        console.log(
            'API horarios:',
            urlHorario
        );


        /* =================================================
           SEMESTRE
        ================================================= */

        const selectSemestre =
            document.getElementById(
                'selectSemestre'
            );


        if (selectSemestre) {

            const opcion20262 =
                Array.from(
                    selectSemestre.options
                ).find(
                    option =>
                        option.value === '20262'
                );


            if (
                opcion20262 &&
                !selectSemestre.value
            ) {

                selectSemestre.value =
                    '20262';
            }


            semestreActual =
                selectSemestre.value ||
                '20262';


            selectSemestre.addEventListener(
                'change',
                function () {

                    semestreActual =
                        this.value ||
                        '20262';


                    /*
                     * Cuando cambia el ciclo,
                     * la semana debe ir al inicio
                     * del periodo académico.
                     */

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


        /*
         * Si estamos fuera del periodo actual,
         * colocamos la semana dentro del ciclo.
         */

        semanaActual =
            ajustarSemanaAlPeriodo(
                semanaActual,
                semestreActual
            );


        /* =================================================
           SEMANA ANTERIOR
        ================================================= */

        const btnAnterior =
            document.getElementById(
                'btnSemanaAnterior'
            );


        if (btnAnterior) {

            btnAnterior.addEventListener(
                'click',
                function () {

                    const nuevaSemana =
                        sumarDias(
                            semanaActual,
                            -7
                        );


                    if (
                        semanaDentroDelPeriodo(
                            nuevaSemana,
                            semestreActual
                        )
                    ) {

                        semanaActual =
                            nuevaSemana;

                        renderizarTodo();
                    }
                }
            );
        }


        /* =================================================
           SEMANA SIGUIENTE
        ================================================= */

        const btnSiguiente =
            document.getElementById(
                'btnSemanaSiguiente'
            );


        if (btnSiguiente) {

            btnSiguiente.addEventListener(
                'click',
                function () {

                    const nuevaSemana =
                        sumarDias(
                            semanaActual,
                            7
                        );


                    if (
                        semanaDentroDelPeriodo(
                            nuevaSemana,
                            semestreActual
                        )
                    ) {

                        semanaActual =
                            nuevaSemana;

                        renderizarTodo();
                    }
                }
            );
        }


        /* =================================================
           SEMANA ACTUAL
        ================================================= */

        const btnActual =
            document.getElementById(
                'btnSemanaActual'
            );


        if (btnActual) {

            btnActual.addEventListener(
                'click',
                function () {

                    const hoy =
                        new Date();


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

                        /*
                         * Si hoy está fuera del ciclo,
                         * vamos al inicio del ciclo.
                         */

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


        /* =================================================
           BOTONES ASISTENCIA
        ================================================= */

        document.addEventListener(
            'click',
            manejarClickAsistencia
        );


        document.addEventListener(
            'change',
            manejarCambioObservacion
        );


        /* =================================================
           CARGAR
        ================================================= */

        cargarDatos();
    }
);


/* =========================================================
   PERIODOS ACADÉMICOS
========================================================= */

/*
 * 20261 = FEBRERO → JULIO
 * 20262 = AGOSTO → DICIEMBRE
 */

function obtenerPeriodoAcademico(
    semestre
) {

    const codigo =
        String(
            semestre || ''
        ).trim();


    if (codigo === '20261') {

        return {
            inicio:
                new Date(
                    2026,
                    1,
                    1
                ),

            fin:
                new Date(
                    2026,
                    6,
                    31
                )
        };
    }


    if (codigo === '20262') {

        return {
            inicio:
                new Date(
                    2026,
                    7,
                    1
                ),

            fin:
                new Date(
                    2026,
                    11,
                    31
                )
        };
    }


    /*
     * Fallback para otros ciclos.
     *
     * Ejemplo:
     * 20271 = febrero-julio 2027
     * 20272 = agosto-diciembre 2027
     */

    const anio =
        Number(
            codigo.substring(0, 4)
        );

    const periodo =
        codigo.substring(4);


    if (
        !isNaN(anio)
    ) {

        if (periodo === '1') {

            return {
                inicio:
                    new Date(
                        anio,
                        1,
                        1
                    ),

                fin:
                    new Date(
                        anio,
                        6,
                        31
                    )
            };
        }


        if (periodo === '2') {

            return {
                inicio:
                    new Date(
                        anio,
                        7,
                        1
                    ),

                fin:
                    new Date(
                        anio,
                        11,
                        31
                    )
            };
        }
    }


    return {
        inicio:
            new Date(
                2026,
                7,
                1
            ),

        fin:
            new Date(
                2026,
                11,
                31
            )
    };
}


/* =========================================================
   INICIO DEL PERIODO
========================================================= */

function obtenerInicioPeriodo(
    semestre
) {

    return new Date(
        obtenerPeriodoAcademico(
            semestre
        ).inicio
    );
}


/* =========================================================
   FIN DEL PERIODO
========================================================= */

function obtenerFinPeriodo(
    semestre
) {

    return new Date(
        obtenerPeriodoAcademico(
            semestre
        ).fin
    );
}


/* =========================================================
   FECHA DENTRO DEL PERIODO
========================================================= */

function fechaDentroDelPeriodo(
    fecha,
    semestre
) {

    const periodo =
        obtenerPeriodoAcademico(
            semestre
        );


    const valor =
        new Date(fecha);


    valor.setHours(
        0,
        0,
        0,
        0
    );


    return (
        valor >= periodo.inicio &&
        valor <= periodo.fin
    );
}


/* =========================================================
   SEMANA DENTRO DEL PERIODO
========================================================= */

function semanaDentroDelPeriodo(
    semana,
    semestre
) {

    const inicioSemana =
        obtenerInicioSemana(
            semana
        );


    const finSemana =
        sumarDias(
            inicioSemana,
            6
        );


    const periodo =
        obtenerPeriodoAcademico(
            semestre
        );


    return (
        finSemana >= periodo.inicio &&
        inicioSemana <= periodo.fin
    );
}


/* =========================================================
   AJUSTAR SEMANA AL PERIODO
========================================================= */

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


/* =========================================================
   CARGAR DATOS DESDE MYSQL
========================================================= */

async function cargarDatos() {

    mostrarCargaCalendario();


    try {

        const semestre =
            semestreActual ||
            '20262';


        console.log('=================================');
        console.log('CONSULTANDO MYSQL');
        console.log('Semestre:', semestre);
        console.log('=================================');


        /* =================================================
           1. ASISTENCIAS
        ================================================= */

        const urlAsistenciaCompleta =
            `${urlAsistencia}?semestre=${encodeURIComponent(semestre)}`;


        console.log(
            'GET asistencias:',
            urlAsistenciaCompleta
        );


        const respuestaAsistencia =
            await fetch(
                urlAsistenciaCompleta,
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


        if (!respuestaAsistencia.ok) {

            throw new Error(
                `Error HTTP asistencias: ${respuestaAsistencia.status}`
            );
        }


        const jsonAsistencia =
            await respuestaAsistencia.json();


        console.log(
            'JSON asistencias:',
            jsonAsistencia
        );


        if (
            !jsonAsistencia.success &&
            !Array.isArray(jsonAsistencia)
        ) {

            throw new Error(
                jsonAsistencia.message ||
                'No se pudieron obtener las asistencias.'
            );
        }


        /* =================================================
           2. DOCENTE
        ================================================= */

        if (
            jsonAsistencia.codDocente !==
                null &&
            jsonAsistencia.codDocente !==
                undefined &&
            jsonAsistencia.codDocente !== ''
        ) {

            docenteActual =
                jsonAsistencia.codDocente;
        }


        /* =================================================
           3. ASISTENCIAS
        ================================================= */

        asistenciaData =
            Array.isArray(jsonAsistencia)
                ? jsonAsistencia
                : Array.isArray(
                    jsonAsistencia.data
                )
                    ? jsonAsistencia.data
                    : [];


        /*
         * Si no llegó codDocente,
         * lo sacamos del primer registro.
         */

        if (
            (
                docenteActual === null ||
                docenteActual === undefined ||
                docenteActual === ''
            ) &&
            asistenciaData.length > 0
        ) {

            docenteActual =
                asistenciaData[0].codDocente;
        }


        if (
            docenteActual === null ||
            docenteActual === undefined ||
            docenteActual === ''
        ) {

            throw new Error(
                'No se pudo obtener el código del docente.'
            );
        }


        /* =================================================
           4. HORARIOS
        ================================================= */

        const urlHorarioCompleta =
            `${urlHorario}?codDocente=${encodeURIComponent(docenteActual)}&semestre=${encodeURIComponent(semestre)}`;


        console.log(
            'GET horarios:',
            urlHorarioCompleta
        );


        const respuestaHorario =
            await fetch(
                urlHorarioCompleta,
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


        if (!respuestaHorario.ok) {

            throw new Error(
                `Error HTTP horarios: ${respuestaHorario.status}`
            );
        }


        const jsonHorario =
            await respuestaHorario.json();


        console.log(
            'JSON horarios:',
            jsonHorario
        );


        horarioData =
            Array.isArray(jsonHorario)
                ? jsonHorario
                : Array.isArray(
                    jsonHorario.data
                )
                    ? jsonHorario.data
                    : [];


        /*
         * Los alumnos NO se cargan aquí.
         *
         * Se cargan cuando se abre
         * una clase concreta.
         */

        alumnosData = [];


        /* =================================================
           5. DOCENTE
        ================================================= */

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


        /* =================================================
           6. AJUSTAR SEMANA
        ================================================= */

        semanaActual =
            ajustarSemanaAlPeriodo(
                semanaActual,
                semestreActual
            );


        /* =================================================
           7. DEBUG
        ================================================= */

        console.log('=================================');
        console.log(
            'DATOS CARGADOS DESDE MYSQL'
        );
        console.log('=================================');

        console.log(
            'Docente:',
            docenteActual
        );

        console.log(
            'Semestre:',
            semestreActual
        );

        console.log(
            'Asistencias:',
            asistenciaData.length
        );

        console.log(
            'Horarios:',
            horarioData.length
        );

        console.log(
            'Alumnos:',
            alumnosData.length
        );


        console.table(
            asistenciaData
        );

        console.table(
            horarioData
        );


        /* =================================================
           8. RENDERIZAR
        ================================================= */

        renderizarTodo();


    } catch (error) {

        console.error(
            '================================='
        );

        console.error(
            'ERROR CARGANDO DATOS'
        );

        console.error(
            error
        );

        console.error(
            '================================='
        );


        mostrarError(
            error.message ||
            'No se pudieron cargar los datos desde MySQL.'
        );
    }
}


/* =========================================================
   RENDERIZAR TODO
========================================================= */

function renderizarTodo() {

    const registros =
        obtenerRegistrosDocente();


    console.log(
        'Registros del docente:',
        registros
    );


    actualizarInformacion(
        registros
    );


    actualizarEstadisticas(
        registros
    );


    renderizarCalendario(
        registros
    );
}


/* =========================================================
   REGISTROS DEL DOCENTE
========================================================= */

function obtenerRegistrosDocente() {

    return asistenciaData.filter(
        function (registro) {

            const mismoDocente =
                String(
                    registro.codDocente ?? ''
                ).trim() ===
                String(
                    docenteActual ?? ''
                ).trim();


            const mismoSemestre =
                String(
                    registro.semestre ?? ''
                ).trim() ===
                String(
                    semestreActual ?? ''
                ).trim();


            return (
                mismoDocente &&
                mismoSemestre
            );
        }
    );
}


/* =========================================================
   INFORMACIÓN SUPERIOR
========================================================= */

function actualizarInformacion(
    registros
) {

    const lblDocente =
        document.getElementById(
            'lblDocente'
        );


    const lblCargaHoraria =
        document.getElementById(
            'lblCargaHoraria'
        );


    if (lblDocente) {

        lblDocente.textContent =
            nombreDocenteActual ||
            'Docente';
    }


    /*
     * La carga horaria se obtiene
     * de los HORARIOS, no solamente
     * de las asistencias.
     */

    const horarios =
        new Set();


    horarioData.forEach(
        function (horario) {

            horarios.add(
                String(
                    horario.codHorario ||
                    ''
                ) +
                '|' +
                String(
                    horario.hora ||
                    ''
                )
            );
        }
    );


    let horas = 0;


    horarios.forEach(
        function (clave) {

            const partes =
                clave.split('|');


            horas +=
                calcularHorasRango(
                    partes[1]
                );
        }
    );


    if (lblCargaHoraria) {

        lblCargaHoraria.textContent =
            `${horas} HRS`;
    }
}


/* =========================================================
   ESTADÍSTICAS
========================================================= */

function actualizarEstadisticas(
    registros
) {

    let programadas = 0;
    let puntuales = 0;
    let tardanzas = 0;
    let inasistencias = 0;
    let noAplica = 0;
    let proceso = 0;


    registros.forEach(
        function (registro) {

            const estado =
                obtenerEstadoAsistencia(
                    registro
                );


            switch (estado) {

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
        }
    );


    ponerTexto(
        'statProgramadas',
        programadas
    );


    ponerTexto(
        'statPuntuales',
        puntuales
    );


    ponerTexto(
        'statTardanzas',
        tardanzas
    );


    ponerTexto(
        'statInasistencias',
        inasistencias
    );


    ponerTexto(
        'statNoAplica',
        noAplica
    );


    ponerTexto(
        'statProceso',
        proceso
    );
}


/* =========================================================
   CALENDARIO
========================================================= */

function renderizarCalendario(
    registros
) {

    const tbody =
        document.getElementById(
            'tbodyAsistencia'
        );


    if (!tbody) {
        return;
    }


    tbody.innerHTML = '';


    actualizarEtiquetaSemana();


    const ocupadas = {};


    DIAS.forEach(
        function (dia) {

            ocupadas[dia] = {};
        }
    );


    const HORA_INICIO = 7;
    const HORA_FIN = 23;


    for (
        let hora = HORA_INICIO;
        hora < HORA_FIN;
        hora++
    ) {

        const tr =
            document.createElement(
                'tr'
            );


        /* HORA */

        const tdHora =
            document.createElement(
                'td'
            );


        tdHora.className =
            'hora-cell';


        tdHora.textContent =
            `${String(hora).padStart(2, '0')}:00`;


        tr.appendChild(
            tdHora
        );


        /* DÍAS */

        DIAS.forEach(
            function (dia) {

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
                    'calendario-cell';


                if (
                    dia === 'DOMINGO'
                ) {

                    td.classList.add(
                        'domingo'
                    );
                }


                /*
                 * IMPORTANTE:
                 *
                 * Primero buscamos el HORARIO
                 * correspondiente a este día/hora.
                 *
                 * Luego buscamos si existe
                 * asistencia para esa fecha.
                 */

                const horario =
                    buscarHorarioInicio(
                        dia,
                        hora
                    );


                if (horario) {

                    const rango =
                        obtenerHorasRango(
                            horario.hora
                        );


                    if (rango) {

                        const inicio =
                            Math.floor(
                                rango.inicio
                            );


                        const fin =
                            Math.ceil(
                                rango.fin
                            );


                        let filas =
                            fin - inicio;


                        if (filas < 1) {

                            filas = 1;
                        }


                        for (
                            let h = inicio;
                            h < fin;
                            h++
                        ) {

                            if (
                                h !== inicio
                            ) {

                                ocupadas[dia][h] =
                                    true;
                            }
                        }


                        if (
                            filas > 1
                        ) {

                            td.rowSpan =
                                filas;
                        }


                        /*
                         * Buscar asistencia
                         * para la fecha concreta.
                         */

                        const fecha =
                            obtenerFechaParaDia(
                                semanaActual,
                                dia
                            );


                        const registro =
                            buscarAsistenciaParaHorario(
                                registros,
                                horario,
                                fecha
                            );


                        td.appendChild(
                            crearBloqueHorario(
                                horario,
                                registro
                            )
                        );
                    }
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


/* =========================================================
   BUSCAR HORARIO
========================================================= */

function buscarHorarioInicio(
    dia,
    hora
) {

    return horarioData.find(
        function (horario) {

            const diaHorario =
                normalizarDiaHorario(
                    horario.dia
                );


            if (
                diaHorario !== dia
            ) {

                return false;
            }


            const rango =
                obtenerHorasRango(
                    horario.hora
                );


            if (!rango) {

                return false;
            }


            const inicio =
                Math.floor(
                    rango.inicio
                );


            return (
                inicio === hora
            );
        }
    );
}


/* =========================================================
   NORMALIZAR DÍA HORARIO
========================================================= */

function normalizarDiaHorario(
    dia
) {

    if (!dia) {
        return '';
    }


    const texto =
        String(dia)
            .trim()
            .toUpperCase();


    return (
        MAPA_DIAS[texto] ||
        texto
    );
}


/* =========================================================
   BUSCAR ASISTENCIA DEL HORARIO
========================================================= */

function buscarAsistenciaParaHorario(
    registros,
    horario,
    fecha
) {

    if (
        !horario ||
        !fecha
    ) {

        return null;
    }


    const fechaNormalizada =
        normalizarFecha(
            fecha
        );


    return registros.find(
        function (registro) {

            const mismaFecha =
                normalizarFecha(
                    registro.fecha
                ) ===
                fechaNormalizada;


            if (!mismaFecha) {

                return false;
            }


            /*
             * Primero intentamos
             * relacionar por codHorario.
             */

            if (
                registro.codHorario &&
                horario.codHorario
            ) {

                if (
                    String(
                        registro.codHorario
                    ) ===
                    String(
                        horario.codHorario
                    )
                ) {

                    return true;
                }
            }


            /*
             * Segundo método:
             * curso + sección.
             */

            return (
                normalizarTexto(
                    registro.codCurso
                ) ===
                normalizarTexto(
                    horario.codCurso
                ) &&

                normalizarTexto(
                    registro.secCurso
                ) ===
                normalizarTexto(
                    horario.secCurso
                )
            );
        }
    ) || null;
}


/* =========================================================
   CREAR BLOQUE HORARIO
========================================================= */

function crearBloqueHorario(
    horario,
    registro
) {

    const bloque =
        document.createElement(
            'div'
        );


    const estado =
        registro
            ? obtenerEstadoAsistencia(
                registro
            )
            : 'PROGRAMADA';


    const claseEstado =
        obtenerClaseEstado(
            estado
        );


    bloque.className =
        `bloque-asistencia ${claseEstado}`;


    const cabecera =
        document.createElement(
            'div'
        );


    cabecera.className =
        'bloque-cabecera';


    const curso =
        document.createElement(
            'div'
        );


    curso.className =
        'bloque-curso';


    curso.textContent =
        horario.codCurso ||
        'SIN CURSO';


    cabecera.appendChild(
        curso
    );


    if (
        horario.secCurso
    ) {

        const seccion =
            document.createElement(
                'div'
            );


        seccion.className =
            'bloque-seccion';


        seccion.textContent =
            `Sección ${horario.secCurso}`;


        cabecera.appendChild(
            seccion
        );
    }


    bloque.appendChild(
        cabecera
    );


    const informacion =
        document.createElement(
            'div'
        );


    informacion.className =
        'bloque-informacion';


    const hora =
        document.createElement(
            'div'
        );


    hora.innerHTML =
        `<i class="fas fa-clock"></i> ${
            escapeHTML(
                horario.hora || '--'
            )
        }`;


    informacion.appendChild(
        hora
    );


    const estadoElemento =
        document.createElement(
            'div'
        );


    estadoElemento.className =
        'bloque-estado';


    estadoElemento.textContent =
        estado;


    informacion.appendChild(
        estadoElemento
    );


    bloque.appendChild(
        informacion
    );


    const acciones =
        document.createElement(
            'div'
        );


    acciones.className =
        'bloque-acciones';


    const boton =
        document.createElement(
            'button'
        );


    boton.type =
        'button';


    boton.className =
        'bloque-ojo';


    boton.title =
        'Ver alumnos y marcar asistencia';


    boton.innerHTML =
        `<i class="fas fa-eye"></i>`;


    boton.addEventListener(
        'click',
        function (evento) {

            evento.preventDefault();

            evento.stopPropagation();


            /*
             * Si existe asistencia,
             * usamos su ID.
             *
             * Si todavía no existe,
             * abrimos el horario para
             * consultar alumnos.
             */

            if (registro) {

                abrirDetalleAsistencia(
                    Number(
                        registro.id
                    )
                );

            } else {

                abrirDetalleHorario(
                    horario
                );
            }
        }
    );


    acciones.appendChild(
        boton
    );


    bloque.appendChild(
        acciones
    );


    return bloque;
}


/* =========================================================
   ABRIR HORARIO SIN ASISTENCIA
========================================================= */

async function abrirDetalleHorario(
    horario
) {

    if (!horario) {
        return;
    }


    const fecha =
        obtenerFechaParaDia(
            semanaActual,
            normalizarDiaHorario(
                horario.dia
            )
        );


    /*
     * Creamos un registro temporal
     * para poder reutilizar el modal.
     */

    const registroTemporal = {

        id:
            null,

        codHorario:
            horario.codHorario,

        codDocente:
            docenteActual,

        fecha:
            normalizarFecha(
                fecha
            ),

        codCurso:
            horario.codCurso,

        secCurso:
            horario.secCurso,

        teopra:
            horario.teopra,

        hora:
            horario.hora,

        semestre:
            horario.semestre ||
            semestreActual,

        estado:
            'PROGRAMADA'
    };


    registroSeleccionado =
        registroTemporal;


    alumnosData =
        await obtenerAlumnosDesdeAsistencia(
            registroTemporal
        );


    alumnosData.forEach(
        function (alumno) {

            aplicarAsistenciaGuardada(
                alumno,
                registroTemporal
            );
        }
    );


    ponerTexto(
        'modalTitulo',
        'Asistencia de alumnos'
    );


    ponerTexto(
        'modalSubtitulo',
        formatearFechaCompleta(
            registroTemporal.fecha
        )
    );


    ponerTexto(
        'modalCurso',
        registroTemporal.codCurso
    );


    ponerTexto(
        'modalHorario',
        registroTemporal.hora ||
        '--'
    );


    ponerTexto(
        'modalEstado',
        'PROGRAMADA'
    );


    renderizarAlumnos(
        alumnosData
    );


    mostrarModal();
}


/* =========================================================
   ABRIR ASISTENCIA
========================================================= */

async function abrirDetalleAsistencia(
    id
) {

    const registro =
        asistenciaData.find(
            function (item) {

                return (
                    Number(item.id) ===
                    Number(id)
                );
            }
        );


    if (!registro) {

        mostrarError(
            'No se encontró la clase seleccionada.'
        );

        return;
    }


    registroSeleccionado =
        registro;


    const horario =
        horarioData.find(
            function (item) {

                return (
                    String(
                        item.codHorario ??
                        ''
                    ) ===
                    String(
                        registro.codHorario ??
                        ''
                    )
                );
            }
        );


    /*
     * OBTENER ALUMNOS DESDE
     * ASISTENCIACONTROLLER
     */

    alumnosData =
        await obtenerAlumnosDesdeAsistencia(
            registro
        );


    alumnosData.forEach(
        function (alumno) {

            aplicarAsistenciaGuardada(
                alumno,
                registro
            );
        }
    );


    ponerTexto(
        'modalTitulo',
        'Asistencia de alumnos'
    );


    ponerTexto(
        'modalSubtitulo',
        formatearFechaCompleta(
            registro.fecha
        )
    );


    ponerTexto(
        'modalCurso',
        obtenerNombreCurso(
            registro,
            horario
        )
    );


    ponerTexto(
        'modalHorario',
        registro.hora ||
        '--'
    );


    ponerTexto(
        'modalEstado',
        obtenerEstadoAsistencia(
            registro
        )
    );


    renderizarAlumnos(
        alumnosData
    );


    mostrarModal();
}


/* =========================================================
   OBTENER ALUMNOS DESDE ASISTENCIACONTROLLER
========================================================= */

async function obtenerAlumnosDesdeAsistencia(
    registro
) {

    if (!registro) {
        return [];
    }


    const curso =
        registro.codCurso ||
        '';


    const seccion =
        registro.secCurso ||
        '';


    const semestre =
        registro.semestre ||
        semestreActual;


    console.log(
        '================================='
    );


    console.log(
        'CONSULTANDO ALUMNOS'
    );


    console.log(
        'Curso:',
        curso
    );


    console.log(
        'Sección:',
        seccion
    );


    console.log(
        'Semestre:',
        semestre
    );


    /*
     * AQUÍ ESTÁ EL CAMBIO PRINCIPAL.
     *
     * NO:
     *
     * /api/alumnos?codDocente=...
     *
     * SÍ:
     *
     * /api/asistencias
     * ?semestre=...
     * &codCurso=...
     * &secCurso=...
     */

    const url =
        `${urlAsistencia}?semestre=${encodeURIComponent(semestre)}&codCurso=${encodeURIComponent(curso)}&secCurso=${encodeURIComponent(seccion)}`;


    console.log(
        'GET alumnos:',
        url
    );


    try {

        const respuesta =
            await fetch(
                url,
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


        if (!respuesta.ok) {

            throw new Error(
                `Error HTTP alumnos: ${respuesta.status}`
            );
        }


        const json =
            await respuesta.json();


        console.log(
            'RESPUESTA ALUMNOS:',
            json
        );


        if (
            !json.success
        ) {

            throw new Error(
                json.message ||
                'No se pudieron obtener los alumnos.'
            );
        }


        const alumnos =
            Array.isArray(
                json.alumnos
            )
                ? json.alumnos
                : [];


        console.log(
            'ALUMNOS ENCONTRADOS:',
            alumnos.length
        );


        console.table(
            alumnos
        );


        return alumnos;


    } catch (error) {

        console.error(
            'Error obteniendo alumnos:',
            error
        );


        mostrarError(
            error.message ||
            'No se pudieron cargar los alumnos.'
        );


        return [];
    }
}


/* =========================================================
   NOMBRE CURSO
========================================================= */

function obtenerNombreCurso(
    registro,
    horario
) {

    if (
        horario &&
        horario.nombreCurso
    ) {

        return horario.nombreCurso;
    }


    if (
        horario &&
        horario.curso
    ) {

        return horario.curso;
    }


    if (
        horario &&
        horario.nomCurso
    ) {

        return horario.nomCurso;
    }


    if (
        registro &&
        registro.nombreCurso
    ) {

        return registro.nombreCurso;
    }


    if (
        registro &&
        registro.codCurso
    ) {

        return registro.codCurso;
    }


    return '--';
}


/* =========================================================
   RENDERIZAR ALUMNOS
========================================================= */

function renderizarAlumnos(
    alumnos
) {

    const contenedor =
        document.getElementById(
            'listaAlumnos'
        );


    if (!contenedor) {
        return;
    }


    contenedor.innerHTML = '';


    if (
        !alumnos ||
        alumnos.length === 0
    ) {

        contenedor.innerHTML = `
            <div class="alumnos-vacio">
                <i class="fas fa-users-slash"></i>

                <h6>
                    No hay alumnos registrados
                </h6>

                <p>
                    No se encontraron alumnos
                    matriculados en este curso,
                    sección y semestre.
                </p>
            </div>
        `;

        return;
    }


    const resumen =
        obtenerResumenAlumnos(
            alumnos
        );


    const resumenElemento =
        document.createElement(
            'div'
        );


    resumenElemento.className =
        'alumnos-resumen';


    resumenElemento.innerHTML = `
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
        resumenElemento
    );


    const aviso =
        document.createElement(
            'div'
        );


    aviso.className =
        'attendance-save-status';


    aviso.innerHTML = `
        <i class="fas fa-circle-check"></i>

        <span>
            La asistencia se guarda automáticamente.
        </span>
    `;


    contenedor.appendChild(
        aviso
    );


    const wrapper =
        document.createElement(
            'div'
        );


    wrapper.className =
        'alumnos-tabla-wrapper';


    wrapper.innerHTML = `
        <div class="table-responsive">

            <table class="table alumnos-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>CÓDIGO</th>
                        <th>ALUMNO</th>
                        <th>ASISTENCIA</th>
                        <th>OBSERVACIÓN</th>
                    </tr>

                </thead>

                <tbody></tbody>

            </table>

        </div>
    `;


    contenedor.appendChild(
        wrapper
    );


    const tbody =
        wrapper.querySelector(
            'tbody'
        );


    alumnos.forEach(
        function (
            alumno,
            index
        ) {

            const estado =
                normalizarAsistencia(
                    alumno.asistencia
                );


            const codigo =
                alumno.codigo ||
                alumno.codAlumno ||
                alumno.codigoEstudiante ||
                alumno.id ||
                '--';


            const nombre =
                alumno.nombre ||
                [
                    alumno.apePaterno ||
                    alumno.apellidoPaterno,

                    alumno.apeMaterno ||
                    alumno.apellidoMaterno,

                    alumno.nombres
                ]
                    .filter(Boolean)
                    .join(' ') ||
                'Sin nombre';


            const correo =
                alumno.email ||
                alumno.correo ||
                '';


            const idAlumno =
                obtenerIdentificadorAlumno(
                    alumno
                );


            const fila =
                document.createElement(
                    'tr'
                );


            fila.innerHTML = `

                <td class="alumno-numero">
                    ${index + 1}
                </td>

                <td>
                    <span class="student-code">
                        ${escapeHTML(codigo)}
                    </span>
                </td>

                <td>

                    <div class="student-name">
                        ${escapeHTML(nombre)}
                    </div>

                    <div class="student-correo">
                        ${escapeHTML(correo)}
                    </div>

                </td>

                <td class="columna-asistencia">

                    <div class="estado-actual-alumno">

                        <span
                            class="badge ${obtenerClaseAsistenciaAlumno(estado)}"
                        >
                            ${obtenerTextoAsistencia(estado)}
                        </span>

                    </div>


                    <div class="marcar-asistencia">

                        <span class="marcar-titulo">
                            MARCAR ASISTENCIA
                        </span>


                        <div class="asistencia-opciones">

                            <button
                                type="button"
                                class="btn-marca btn-marca-asistio ${
                                    estado === 'ASISTIO'
                                        ? 'active'
                                        : ''
                                }"
                                data-accion-asistencia="ASISTIO"
                                data-alumno-id="${escapeAttribute(
                                    idAlumno
                                )}"
                            >
                                <i class="fas fa-check"></i>
                                ASISTIÓ
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-falto ${
                                    estado === 'FALTO'
                                        ? 'active'
                                        : ''
                                }"
                                data-accion-asistencia="FALTO"
                                data-alumno-id="${escapeAttribute(
                                    idAlumno
                                )}"
                            >
                                <i class="fas fa-xmark"></i>
                                FALTÓ
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-tardanza ${
                                    estado === 'TARDANZA'
                                        ? 'active'
                                        : ''
                                }"
                                data-accion-asistencia="TARDANZA"
                                data-alumno-id="${escapeAttribute(
                                    idAlumno
                                )}"
                            >
                                <i class="fas fa-clock"></i>
                                TARDANZA
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-justificado ${
                                    estado === 'JUSTIFICADO'
                                        ? 'active'
                                        : ''
                                }"
                                data-accion-asistencia="JUSTIFICADO"
                                data-alumno-id="${escapeAttribute(
                                    idAlumno
                                )}"
                            >
                                <i class="fas fa-file-circle-check"></i>
                                JUSTIFICADO
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-limpiar"
                                data-accion-asistencia=""
                                data-alumno-id="${escapeAttribute(
                                    idAlumno
                                )}"
                            >
                                <i class="fas fa-eraser"></i>
                                LIMPIAR
                            </button>

                        </div>

                    </div>

                </td>


                <td>

                    <input
                        type="text"
                        class="form-control form-control-sm alumno-observacion"
                        data-observacion-alumno="${escapeAttribute(
                            idAlumno
                        )}"
                        value="${escapeAttribute(
                            alumno.observacion || ''
                        )}"
                        placeholder="Agregar observación..."
                    >

                </td>

            `;


            tbody.appendChild(
                fila
            );
        }
    );
}


/* =========================================================
   RESUMEN ALUMNOS
========================================================= */

function obtenerResumenAlumnos(
    alumnos
) {

    const resumen = {

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
        function (alumno) {

            const estado =
                normalizarAsistencia(
                    alumno.asistencia
                );


            switch (estado) {

                case 'ASISTIO':
                    resumen.asistio++;
                    break;

                case 'FALTO':
                    resumen.falto++;
                    break;

                case 'TARDANZA':
                    resumen.tardanza++;
                    break;

                case 'JUSTIFICADO':
                    resumen.justificado++;
                    break;

                default:
                    resumen.sinMarcar++;
                    break;
            }
        }
    );


    return resumen;
}


/* =========================================================
   CLICK ASISTENCIA
========================================================= */

function manejarClickAsistencia(
    evento
) {

    const boton =
        evento.target.closest(
            '[data-accion-asistencia]'
        );


    if (!boton) {
        return;
    }


    const estado =
        boton.getAttribute(
            'data-accion-asistencia'
        );


    const idAlumno =
        boton.getAttribute(
            'data-alumno-id'
        );


    marcarAsistenciaAlumno(
        idAlumno,
        estado
    );
}


/* =========================================================
   IDENTIFICADOR ALUMNO
========================================================= */

function obtenerIdentificadorAlumno(
    alumno
) {

    if (!alumno) {
        return '';
    }


    return String(
        alumno.id ||
        alumno.codigo ||
        alumno.codAlumno ||
        alumno.codigoEstudiante ||
        ''
    );
}


/* =========================================================
   MARCAR ALUMNO
========================================================= */

function marcarAsistenciaAlumno(
    idAlumno,
    estado
) {

    if (!registroSeleccionado) {
        return;
    }


    const alumno =
        alumnosData.find(
            function (item) {

                return (
                    obtenerIdentificadorAlumno(
                        item
                    ) ===
                    String(
                        idAlumno
                    )
                );
            }
        );


    if (!alumno) {
        return;
    }


    alumno.asistencia =
        normalizarAsistencia(
            estado
        );


    guardarAsistenciaLocal(
        alumno,
        registroSeleccionado
    );


    const alumnos =
        alumnosData;


    alumnos.forEach(
        function (item) {

            aplicarAsistenciaGuardada(
                item,
                registroSeleccionado
            );
        }
    );


    renderizarAlumnos(
        alumnos
    );
}


/* =========================================================
   OBSERVACIÓN
========================================================= */

function manejarCambioObservacion(
    evento
) {

    const input =
        evento.target.closest(
            '[data-observacion-alumno]'
        );


    if (!input) {
        return;
    }


    if (!registroSeleccionado) {
        return;
    }


    const idAlumno =
        input.getAttribute(
            'data-observacion-alumno'
        );


    const alumno =
        alumnosData.find(
            function (item) {

                return (
                    obtenerIdentificadorAlumno(
                        item
                    ) ===
                    String(
                        idAlumno
                    )
                );
            }
        );


    if (!alumno) {
        return;
    }


    alumno.observacion =
        input.value.trim();


    guardarAsistenciaLocal(
        alumno,
        registroSeleccionado
    );
}


/* =========================================================
   NORMALIZAR ASISTENCIA
========================================================= */

function normalizarAsistencia(
    valor
) {

    if (
        valor === null ||
        valor === undefined
    ) {

        return '';
    }


    const estado =
        String(valor)
            .trim()
            .toUpperCase();


    if (!estado) {
        return '';
    }


    if (
        estado === 'PRESENTE' ||
        estado === 'ASISTENCIA' ||
        estado === 'ASISTIÓ' ||
        estado === 'ASISTIO'
    ) {

        return 'ASISTIO';
    }


    if (
        estado === 'FALTA' ||
        estado === 'AUSENTE' ||
        estado === 'FALTO'
    ) {

        return 'FALTO';
    }


    if (
        estado === 'TARDE' ||
        estado === 'TARDIO' ||
        estado === 'TARDÍO' ||
        estado === 'TARDANZA'
    ) {

        return 'TARDANZA';
    }


    if (
        estado === 'JUSTIFICADA' ||
        estado === 'JUSTIFICADO'
    ) {

        return 'JUSTIFICADO';
    }


    return estado;
}


/* =========================================================
   TEXTO ASISTENCIA
========================================================= */

function obtenerTextoAsistencia(
    estado
) {

    switch (
        normalizarAsistencia(
            estado
        )
    ) {

        case 'ASISTIO':
            return 'ASISTIÓ';

        case 'FALTO':
            return 'FALTÓ';

        case 'TARDANZA':
            return 'TARDANZA';

        case 'JUSTIFICADO':
            return 'JUSTIFICADO';

        default:
            return 'SIN MARCAR';
    }
}


/* =========================================================
   CLASE ASISTENCIA
========================================================= */

function obtenerClaseAsistenciaAlumno(
    estado
) {

    switch (
        normalizarAsistencia(
            estado
        )
    ) {

        case 'ASISTIO':
            return 'badge-presente';

        case 'FALTO':
            return 'badge-falta';

        case 'TARDANZA':
            return 'badge-tarde';

        case 'JUSTIFICADO':
            return 'badge-justificado';

        default:
            return 'badge-sin-registro';
    }
}


/* =========================================================
   ESTADO DOCENTE
========================================================= */

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


    const programada =
        rango.inicio * 60;


    if (
        entrada <=
        programada + 10
    ) {

        return 'PUNTUAL';
    }


    return 'TARDANZA';
}


/* =========================================================
   CLASE ESTADO
========================================================= */

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


/* =========================================================
   STORAGE
========================================================= */

function cargarAsistenciasLocales() {

    try {

        const datos =
            localStorage.getItem(
                STORAGE_KEY
            );


        if (!datos) {

            return {};
        }


        return (
            JSON.parse(
                datos
            ) || {}
        );

    } catch (error) {

        console.error(
            error
        );


        return {};
    }
}


/* =========================================================
   CLAVE STORAGE
========================================================= */

function crearClaveAsistenciaAlumno(
    alumno,
    registro
) {

    if (
        !alumno ||
        !registro
    ) {

        return null;
    }


    const idAlumno =
        obtenerIdentificadorAlumno(
            alumno
        );


    return [

        registro.semestre ||
        semestreActual,

        normalizarFecha(
            registro.fecha
        ),

        String(
            registro.codHorario ||
            registro.codCurso ||
            ''
        ),

        String(
            registro.secCurso ||
            ''
        ),

        idAlumno

    ].join('|');
}


/* =========================================================
   GUARDAR LOCAL
========================================================= */

function guardarAsistenciaLocal(
    alumno,
    registro
) {

    const clave =
        crearClaveAsistenciaAlumno(
            alumno,
            registro
        );


    if (!clave) {
        return;
    }


    const asistencia =
        normalizarAsistencia(
            alumno.asistencia
        );


    const observacion =
        String(
            alumno.observacion ||
            ''
        ).trim();


    if (
        !asistencia &&
        !observacion
    ) {

        delete asistenciasGuardadas[
            clave
        ];

    } else {

        asistenciasGuardadas[
            clave
        ] = {

            asistencia,

            observacion,

            actualizado:
                new Date().toISOString()
        };
    }


    try {

        localStorage.setItem(
            STORAGE_KEY,
            JSON.stringify(
                asistenciasGuardadas
            )
        );

    } catch (error) {

        console.error(
            'Error guardando asistencia:',
            error
        );
    }
}


/* =========================================================
   APLICAR STORAGE
========================================================= */

function aplicarAsistenciaGuardada(
    alumno,
    registro
) {

    const clave =
        crearClaveAsistenciaAlumno(
            alumno,
            registro
        );


    if (!clave) {
        return;
    }


    const guardado =
        asistenciasGuardadas[
            clave
        ];


    if (!guardado) {

        alumno.asistencia =
            '';

        alumno.observacion =
            '';

        return;
    }


    alumno.asistencia =
        normalizarAsistencia(
            guardado.asistencia
        );


    alumno.observacion =
        guardado.observacion ||
        '';
}


/* =========================================================
   SEMANA
========================================================= */

function obtenerInicioSemana(
    fecha
) {

    const resultado =
        new Date(
            fecha
        );


    resultado.setHours(
        0,
        0,
        0,
        0
    );


    const dia =
        resultado.getDay();


    const diferencia =
        dia === 0
            ? -6
            : 1 - dia;


    resultado.setDate(
        resultado.getDate() +
        diferencia
    );


    return resultado;
}


/* =========================================================
   SUMAR DÍAS
========================================================= */

function sumarDias(
    fecha,
    dias
) {

    const resultado =
        new Date(
            fecha
        );


    resultado.setDate(
        resultado.getDate() +
        dias
    );


    return resultado;
}


/* =========================================================
   FECHA POR DÍA
========================================================= */

function obtenerFechaParaDia(
    semana,
    dia
) {

    const fecha =
        new Date(
            semana
        );


    const diaNormalizado =
        normalizarDiaHorario(
            dia
        );


    const numero =
        NUMERO_DIA[
            diaNormalizado
        ];


    const diferencia =
        numero === 0
            ? 6
            : numero - 1;


    fecha.setDate(
        fecha.getDate() +
        diferencia
    );


    return fecha;
}


/* =========================================================
   NORMALIZAR FECHA
========================================================= */

function normalizarFecha(
    fecha
) {

    if (!fecha) {

        return '';
    }


    if (
        fecha instanceof Date
    ) {

        return [

            fecha.getFullYear(),

            String(
                fecha.getMonth() + 1
            ).padStart(
                2,
                '0'
            ),

            String(
                fecha.getDate()
            ).padStart(
                2,
                '0'
            )

        ].join('-');
    }


    const texto =
        String(
            fecha
        ).trim();


    const iso =
        texto.match(
            /^(\d{4})-(\d{1,2})-(\d{1,2})/
        );


    if (iso) {

        return [

            iso[1],

            String(
                iso[2]
            ).padStart(
                2,
                '0'
            ),

            String(
                iso[3]
            ).padStart(
                2,
                '0'
            )

        ].join('-');
    }


    const latino =
        texto.match(
            /^(\d{1,2})\/(\d{1,2})\/(\d{4})/
        );


    if (latino) {

        return [

            latino[3],

            String(
                latino[2]
            ).padStart(
                2,
                '0'
            ),

            String(
                latino[1]
            ).padStart(
                2,
                '0'
            )

        ].join('-');
    }


    return texto;
}


/* =========================================================
   FECHA COMPLETA
========================================================= */

function formatearFechaCompleta(
    fecha
) {

    const normalizada =
        normalizarFecha(
            fecha
        );


    if (!normalizada) {

        return '--';
    }


    const partes =
        normalizada.split('-');


    if (
        partes.length !== 3
    ) {

        return normalizada;
    }


    const date =
        new Date(

            Number(
                partes[0]
            ),

            Number(
                partes[1]
            ) - 1,

            Number(
                partes[2]
            )
        );


    return date.toLocaleDateString(
        'es-PE',
        {
            weekday:
                'long',

            day:
                '2-digit',

            month:
                'long',

            year:
                'numeric'
        }
    );
}


/* =========================================================
   FECHA CORTA
========================================================= */

function formatearFechaCorta(
    fecha
) {

    const date =
        fecha instanceof Date
            ? fecha
            : new Date(
                fecha
            );


    if (
        isNaN(
            date.getTime()
        )
    ) {

        return '--';
    }


    return date.toLocaleDateString(
        'es-PE',
        {
            day:
                '2-digit',

            month:
                '2-digit'
        }
    );
}


/* =========================================================
   ETIQUETA SEMANA
========================================================= */

function actualizarEtiquetaSemana() {

    const elemento =
        document.getElementById(
            'lblSemana'
        );


    if (!elemento) {

        return;
    }


    const inicio =
        semanaActual;


    const fin =
        sumarDias(
            inicio,
            6
        );


    elemento.textContent =
        `${formatearFechaCorta(inicio)} - ${formatearFechaCorta(fin)}`;
}


/* =========================================================
   HORAS
========================================================= */

function obtenerHorasRango(
    rango
) {

    if (!rango) {

        return null;
    }


    const partes =
        String(
            rango
        )
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


/* =========================================================
   HORA DECIMAL
========================================================= */

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
        String(
            valor
        )
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
        Number(
            match[1]
        );


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


    return (
        horas +
        (
            minutos / 60
        )
    );
}


/* =========================================================
   HORA MINUTOS
========================================================= */

function convertirHoraAMinutos(
    valor
) {

    const decimal =
        convertirHoraDecimal(
            valor
        );


    if (
        decimal === null
    ) {

        return null;
    }


    return Math.round(
        decimal * 60
    );
}


/* =========================================================
   CALCULAR HORAS
========================================================= */

function calcularHorasRango(
    rango
) {

    const horas =
        obtenerHorasRango(
            rango
        );


    if (!horas) {

        return 0;
    }


    return Math.max(
        0,
        horas.fin -
        horas.inicio
    );
}


/* =========================================================
   NORMALIZAR TEXTO
========================================================= */

function normalizarTexto(
    valor
) {

    return String(
        valor ?? ''
    )
        .trim()
        .toUpperCase();
}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHTML(
    valor
) {

    if (
        valor === null ||
        valor === undefined
    ) {

        return '';
    }


    return String(
        valor
    )

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


/* =========================================================
   ESCAPE ATRIBUTO
========================================================= */

function escapeAttribute(
    valor
) {

    return escapeHTML(
        valor
    );
}


/* =========================================================
   PONER TEXTO
========================================================= */

function ponerTexto(
    id,
    texto
) {

    const elemento =
        document.getElementById(
            id
        );


    if (elemento) {

        elemento.textContent =
            texto ?? '';
    }
}


/* =========================================================
   MODAL
========================================================= */

function mostrarModal() {

    const modalElemento =
        document.getElementById(
            'modalAsistencia'
        );


    if (!modalElemento) {

        return;
    }


    if (
        typeof bootstrap !==
            'undefined' &&
        bootstrap.Modal
    ) {

        const modal =
            bootstrap.Modal.getOrCreateInstance(
                modalElemento
            );


        modal.show();
    }
}


/* =========================================================
   CARGANDO
========================================================= */

function mostrarCargaCalendario() {

    const tbody =
        document.getElementById(
            'tbodyAsistencia'
        );


    if (!tbody) {

        return;
    }


    tbody.innerHTML = `

        <tr>

            <td
                colspan="8"
                class="calendar-loading"
            >

                <i class="fas fa-spinner fa-spin"></i>

                <span>
                    Cargando programación...
                </span>

            </td>

        </tr>

    `;
}


/* =========================================================
   ERROR
========================================================= */

function mostrarError(
    mensaje
) {

    console.error(
        mensaje
    );


    alert(
        mensaje
    );
}


/* =========================================================
   DEBUG
========================================================= */

window.asistenciaDocenteDebug = {

    obtenerDatos:
        function () {

            return {

                asistenciaData,

                horarioData,

                alumnosData,

                docenteActual,

                nombreDocenteActual,

                semestreActual,

                semanaActual,

                registroSeleccionado,

                asistenciasGuardadas
            };
        },


    limpiarAsistenciasLocales:
        function () {

            localStorage.removeItem(
                STORAGE_KEY
            );


            asistenciasGuardadas =
                {};


            console.log(
                'Asistencias locales eliminadas.'
            );
        },


    recargar:
        function () {

            cargarDatos();
        }
};