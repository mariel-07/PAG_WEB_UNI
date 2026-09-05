

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
    LUNES: 1,
    MARTES: 2,
    MIÉRCOLES: 3,
    JUEVES: 4,
    VIERNES: 5,
    SÁBADO: 6,
    DOMINGO: 0
};


/* =========================================================
   VARIABLES
========================================================= */

let asistenciaData = [];
let horarioData = [];
let alumnosData = [];

let semanaActual = obtenerInicioSemana(new Date());

let docenteActual = 87546374;
let semestreActual = '20262';

let registroSeleccionado = null;


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

        if (window.codDocente) {
            docenteActual =
                Number(window.codDocente);
        }


        /* -----------------------------------------------
           SEMESTRE
        ----------------------------------------------- */

        const selectSemestre =
            document.getElementById(
                'selectSemestre'
            );

        if (selectSemestre) {

            semestreActual =
                selectSemestre.value || '20262';

            selectSemestre.addEventListener(
                'change',
                function () {

                    semestreActual =
                        this.value;

                    semanaActual =
                        obtenerInicioSemana(
                            new Date()
                        );

                    renderizarTodo();
                }
            );
        }


        /* -----------------------------------------------
           SEMANA ANTERIOR
        ----------------------------------------------- */

        const btnAnterior =
            document.getElementById(
                'btnSemanaAnterior'
            );

        if (btnAnterior) {

            btnAnterior.addEventListener(
                'click',
                function () {

                    semanaActual =
                        sumarDias(
                            semanaActual,
                            -7
                        );

                    renderizarTodo();
                }
            );
        }


        /* -----------------------------------------------
           SEMANA SIGUIENTE
        ----------------------------------------------- */

        const btnSiguiente =
            document.getElementById(
                'btnSemanaSiguiente'
            );

        if (btnSiguiente) {

            btnSiguiente.addEventListener(
                'click',
                function () {

                    semanaActual =
                        sumarDias(
                            semanaActual,
                            7
                        );

                    renderizarTodo();
                }
            );
        }


        /* -----------------------------------------------
           SEMANA ACTUAL
        ----------------------------------------------- */

        const btnActual =
            document.getElementById(
                'btnSemanaActual'
            );

        if (btnActual) {

            btnActual.addEventListener(
                'click',
                function () {

                    semanaActual =
                        obtenerInicioSemana(
                            new Date()
                        );

                    renderizarTodo();
                }
            );
        }


        /* -----------------------------------------------
           BOTONES DE ASISTENCIA
        ----------------------------------------------- */

        document.addEventListener(
            'click',
            manejarClickAsistencia
        );


        document.addEventListener(
            'change',
            manejarCambioObservacion
        );


        /* -----------------------------------------------
           CARGAR DATOS
        ----------------------------------------------- */

        cargarDatos();
    }
);


/* =========================================================
   CARGAR DATOS
   ---------------------------------------------------------
   FASE 4

   ASISTENCIA:
   Laravel API + MySQL

   HORARIOS:
   JSON

   ALUMNOS:
   JSON
========================================================= */

async function cargarDatos() {

    try {

        mostrarCargaCalendario();


        const urlAsistencia =
            window.urlAsistenciaApi ||
            '/api/asistencias';


        const urlHorario =
            window.urlHorarioJson;


        const urlAlumnos =
            window.urlAlumnosJson;


        /* -----------------------------------------------
           CARGAR DATOS
        ----------------------------------------------- */

        const [
            respuestaAsistencia,
            respuestaHorario,
            respuestaAlumnos
        ] = await Promise.all([

            fetch(
                urlAsistencia,
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    cache: 'no-store'
                }
            ),

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


        /* -----------------------------------------------
           VALIDAR ASISTENCIA
        ----------------------------------------------- */

        if (!respuestaAsistencia.ok) {

            throw new Error(
                'No se pudo cargar la asistencia desde Laravel.'
            );
        }


        /* -----------------------------------------------
           VALIDAR HORARIOS
        ----------------------------------------------- */

        if (!respuestaHorario.ok) {

            throw new Error(
                'No se pudo cargar horario.json'
            );
        }


        /* -----------------------------------------------
           VALIDAR ALUMNOS
        ----------------------------------------------- */

        if (!respuestaAlumnos.ok) {

            throw new Error(
                'No se pudo cargar alumnos.json'
            );
        }


        /* =================================================
           ASISTENCIA DESDE LARAVEL
        ================================================= */

        const resultadoAsistencia =
            await respuestaAsistencia.json();


        /*
         * Nuestro AsistenciaController devuelve:
         *
         * {
         *     success: true,
         *     data: [...]
         * }
         */

        if (
            resultadoAsistencia &&
            Array.isArray(
                resultadoAsistencia.data
            )
        ) {

            asistenciaData =
                resultadoAsistencia.data;

        } else if (
            Array.isArray(
                resultadoAsistencia
            )
        ) {

            /*
             * Compatibilidad por si Laravel
             * devuelve directamente el array.
             */

            asistenciaData =
                resultadoAsistencia;

        } else {

            asistenciaData = [];
        }


        /* =================================================
           HORARIOS DESDE JSON
        ================================================= */

        horarioData =
            await respuestaHorario.json();


        /* =================================================
           ALUMNOS DESDE JSON
        ================================================= */

        alumnosData =
            await respuestaAlumnos.json();


        /* -----------------------------------------------
           NORMALIZAR HORARIOS
        ----------------------------------------------- */

        if (!Array.isArray(horarioData)) {

            horarioData =
                Object.values(
                    horarioData || {}
                );
        }


        /* -----------------------------------------------
           NORMALIZAR ALUMNOS
        ----------------------------------------------- */

        if (!Array.isArray(alumnosData)) {

            alumnosData =
                Object.values(
                    alumnosData || {}
                );
        }


        /* -----------------------------------------------
           NORMALIZAR ASISTENCIA
        ----------------------------------------------- */

        if (!Array.isArray(asistenciaData)) {

            asistenciaData =
                Object.values(
                    asistenciaData || {}
                );
        }


        /* =================================================
           DEBUG
        ================================================= */

        console.log(
            '========================================'
        );

        console.log(
            'ASISTENCIA DESDE LARAVEL + MYSQL:',
            asistenciaData
        );

        console.log(
            'HORARIOS DESDE JSON:',
            horarioData
        );

        console.log(
            'ALUMNOS DESDE JSON:',
            alumnosData
        );

        console.log(
            'DOCENTE ACTUAL:',
            docenteActual
        );

        console.log(
            'SEMESTRE ACTUAL:',
            semestreActual
        );

        console.log(
            '========================================'
        );


        /* -----------------------------------------------
           RENDERIZAR
        ----------------------------------------------- */

        renderizarTodo();


    } catch (error) {

        console.error(
            'Error cargando datos:',
            error
        );

        mostrarError(
            'No se pudieron cargar los datos.'
        );
    }
}


/* =========================================================
   RENDERIZAR TODO
========================================================= */

function renderizarTodo() {

    const registros =
        obtenerRegistrosDocente();


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

            return (

                Number(
                    registro.codDocente
                )
                ===
                Number(
                    docenteActual
                )

                &&

                String(
                    registro.semestre
                )
                ===
                String(
                    semestreActual
                )
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
            window.nombreDocente ||
            'Docente';
    }


    const horarios =
        new Set();


    registros.forEach(
        function (registro) {

            horarios.add(
                String(
                    registro.codHorario || ''
                )
                +
                '|'
                +
                String(
                    registro.hora || ''
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


        /* -----------------------------------------------
           HORA
        ----------------------------------------------- */

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


        /* -----------------------------------------------
           DÍAS
        ----------------------------------------------- */

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


                const registro =
                    buscarRegistroInicio(
                        registros,
                        dia,
                        hora
                    );


                if (registro) {

                    const rango =
                        obtenerHorasRango(
                            registro.hora
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


                        if (filas > 1) {

                            td.rowSpan =
                                filas;
                        }


                        td.appendChild(
                            crearBloqueAsistencia(
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
   BUSCAR SOLO EL INICIO DEL REGISTRO
========================================================= */

function buscarRegistroInicio(
    registros,
    dia,
    hora
) {

    const fecha =
        obtenerFechaParaDia(
            semanaActual,
            dia
        );


    return registros.find(
        function (registro) {

            if (!registro.fecha) {
                return false;
            }


            if (
                normalizarFecha(
                    registro.fecha
                )
                !==
                normalizarFecha(
                    fecha
                )
            ) {

                return false;
            }


            const rango =
                obtenerHorasRango(
                    registro.hora
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
   BLOQUE DEL CALENDARIO
========================================================= */

function crearBloqueAsistencia(
    registro
) {

    const bloque =
        document.createElement(
            'div'
        );


    const estado =
        obtenerEstadoAsistencia(
            registro
        );


    const claseEstado =
        obtenerClaseEstado(
            estado
        );


    bloque.className =
        `bloque-asistencia ${claseEstado}`;


    /* -----------------------------------------------
       CABECERA
    ----------------------------------------------- */

    const cabecera =
        document.createElement(
            'div'
        );


    cabecera.className =
        'bloque-cabecera';


    /* -----------------------------------------------
       CURSO
    ----------------------------------------------- */

    const curso =
        document.createElement(
            'div'
        );


    curso.className =
        'bloque-curso';


    curso.textContent =
        registro.codCurso ||
        'SIN CURSO';


    cabecera.appendChild(
        curso
    );


    /* -----------------------------------------------
       SECCIÓN
    ----------------------------------------------- */

    if (registro.secCurso) {

        const seccion =
            document.createElement(
                'div'
            );


        seccion.className =
            'bloque-seccion';


        seccion.textContent =
            `Sección ${registro.secCurso}`;


        cabecera.appendChild(
            seccion
        );
    }


    bloque.appendChild(
        cabecera
    );


    /* -----------------------------------------------
       INFORMACIÓN
    ----------------------------------------------- */

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
        `<i class="fas fa-clock"></i> ${escapeHTML(
            registro.hora || '--'
        )}`;


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


    /* -----------------------------------------------
       BOTÓN OJO
    ----------------------------------------------- */

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
        '<i class="fas fa-eye"></i>';


    boton.addEventListener(
        'click',
        function (evento) {

            evento.preventDefault();

            evento.stopPropagation();


            abrirDetalleAsistencia(
                Number(
                    registro.id
                )
            );
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
        estado.includes('NO APLICA')
        ||
        estado === 'N/A'
    ) {

        return 'NO APLICA';
    }


    if (
        estado.includes('PROCESO')
        ||
        estado.includes('CAMBIO')
    ) {

        return 'EN PROCESO DE CAMBIO';
    }


    if (
        estado.includes('INASIST')
        ||
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
        estado.includes('PUNT')
        ||
        estado.includes('ASIST')
        ||
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
   ABRIR MODAL
========================================================= */

function abrirDetalleAsistencia(
    id
) {

    const registro =
        asistenciaData.find(
            function (item) {

                return (
                    Number(item.id)
                    ===
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


    /* -----------------------------------------------
       HORARIO
    ----------------------------------------------- */

    const horario =
        horarioData.find(
            function (item) {

                return (
                    String(
                        item.codHorario
                        ||
                        item.idHorario
                        ||
                        ''
                    )
                    ===
                    String(
                        registro.codHorario
                        ||
                        ''
                    )
                );
            }
        );


    /* -----------------------------------------------
       ALUMNOS
    ----------------------------------------------- */

    const alumnos =
        alumnosData.filter(
            function (alumno) {

                return (
                    String(
                        alumno.idHorario
                        ||
                        ''
                    )
                    ===
                    String(
                        registro.codHorario
                        ||
                        ''
                    )
                );
            }
        );


    alumnos.forEach(
        function (alumno) {

            aplicarAsistenciaGuardada(
                alumno,
                registro
            );
        }
    );


    /* -----------------------------------------------
       DATOS DEL MODAL
    ----------------------------------------------- */

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
        registro.hora || '--'
    );


    ponerTexto(
        'modalEstado',
        obtenerEstadoAsistencia(
            registro
        )
    );


    renderizarAlumnos(
        alumnos
    );


    /* -----------------------------------------------
       MODAL BOOTSTRAP
    ----------------------------------------------- */

    const modalElemento =
        document.getElementById(
            'modalAsistencia'
        );


    if (!modalElemento) {

        return;
    }


    if (
        typeof bootstrap !== 'undefined'
        &&
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
   ALUMNOS
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
        !alumnos
        ||
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
                    para este horario.
                </p>
            </div>
        `;

        return;
    }


    /* -----------------------------------------------
       RESUMEN
    ----------------------------------------------- */

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


    /* -----------------------------------------------
       AVISO
    ----------------------------------------------- */

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


    /* -----------------------------------------------
       TABLA
    ----------------------------------------------- */

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


    /* -----------------------------------------------
       FILAS
    ----------------------------------------------- */

    alumnos.forEach(
        function (
            alumno,
            index
        ) {

            const estado =
                normalizarAsistencia(
                    alumno.asistencia
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
                        ${escapeHTML(
                            alumno.codigoEstudiante
                            || '--'
                        )}
                    </span>

                </td>

                <td>

                    <div class="student-name">
                        ${escapeHTML(
                            alumno.nombres
                            || 'Sin nombre'
                        )}
                    </div>

                    <div class="student-correo">
                        ${escapeHTML(
                            alumno.correo
                            || ''
                        )}
                    </div>

                </td>

                <td class="columna-asistencia">

                    <div class="estado-actual-alumno">

                        <span
                            class="badge ${obtenerClaseAsistenciaAlumno(estado)}"
                        >
                            ${obtenerTextoAsistencia(
                                estado
                            )}
                        </span>

                    </div>


                    <div class="marcar-asistencia">

                        <span class="marcar-titulo">
                            MARCAR ASISTENCIA
                        </span>


                        <div class="asistencia-opciones">

                            <button
                                type="button"
                                class="btn-marca btn-marca-asistio ${estado === 'ASISTIO' ? 'active' : ''}"
                                data-accion-asistencia="ASISTIO"
                                data-alumno-id="${escapeHTML(String(alumno.id))}"
                            >
                                <i class="fas fa-check"></i>
                                ASISTIÓ
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-falto ${estado === 'FALTO' ? 'active' : ''}"
                                data-accion-asistencia="FALTO"
                                data-alumno-id="${escapeHTML(String(alumno.id))}"
                            >
                                <i class="fas fa-xmark"></i>
                                FALTÓ
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-tardanza ${estado === 'TARDANZA' ? 'active' : ''}"
                                data-accion-asistencia="TARDANZA"
                                data-alumno-id="${escapeHTML(String(alumno.id))}"
                            >
                                <i class="fas fa-clock"></i>
                                TARDANZA
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-justificado ${estado === 'JUSTIFICADO' ? 'active' : ''}"
                                data-accion-asistencia="JUSTIFICADO"
                                data-alumno-id="${escapeHTML(String(alumno.id))}"
                            >
                                <i class="fas fa-file-circle-check"></i>
                                JUSTIFICADO
                            </button>


                            <button
                                type="button"
                                class="btn-marca btn-marca-limpiar"
                                data-accion-asistencia=""
                                data-alumno-id="${escapeHTML(String(alumno.id))}"
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
                        data-observacion-alumno="${escapeHTML(String(alumno.id))}"
                        value="${escapeAttribute(alumno.observacion || '')}"
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

        total: alumnos.length,

        asistio: 0,

        falto: 0,

        tardanza: 0,

        justificado: 0,

        sinMarcar: 0
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
   MARCAR ALUMNO
   ---------------------------------------------------------
   NOTA:
   Por ahora permanece en localStorage.

   La tabla "asistencias" actual corresponde
   a la asistencia del DOCENTE y no contiene
   idAlumno.

   En el siguiente paso crearemos la tabla
   asistencia_alumnos para llevar esta parte
   también a MySQL.
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

                    String(item.id)
                    ===
                    String(idAlumno)

                    &&

                    String(
                        item.idHorario || ''
                    )
                    ===
                    String(
                        registroSeleccionado.codHorario
                        ||
                        ''
                    )
                );
            }
        );


    if (!alumno) {

        return;
    }


    estado =
        normalizarAsistencia(
            estado
        );


    alumno.asistencia =
        estado;


    guardarAsistenciaLocal(
        alumno,
        registroSeleccionado
    );


    const alumnos =
        alumnosData.filter(
            function (item) {

                return (

                    String(
                        item.idHorario || ''
                    )
                    ===
                    String(
                        registroSeleccionado.codHorario
                        ||
                        ''
                    )
                );
            }
        );


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

                    String(item.id)
                    ===
                    String(idAlumno)

                    &&

                    String(
                        item.idHorario || ''
                    )
                    ===
                    String(
                        registroSeleccionado.codHorario
                        ||
                        ''
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
        valor === null
        ||
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
        estado === 'PRESENTE'
        ||
        estado === 'ASISTENCIA'
        ||
        estado === 'ASISTIÓ'
        ||
        estado === 'ASISTIO'
    ) {

        return 'ASISTIO';
    }


    if (
        estado === 'FALTA'
        ||
        estado === 'AUSENTE'
        ||
        estado === 'FALTO'
    ) {

        return 'FALTO';
    }


    if (
        estado === 'TARDE'
        ||
        estado === 'TARDIO'
        ||
        estado === 'TARDÍO'
        ||
        estado === 'TARDANZA'
    ) {

        return 'TARDANZA';
    }


    if (
        estado === 'JUSTIFICADA'
        ||
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


        return JSON.parse(
            datos
        ) || {};


    } catch (error) {

        console.error(error);

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
        !alumno
        ||
        !registro
    ) {

        return null;
    }


    return [

        semestreActual,

        normalizarFecha(
            registro.fecha
        ),

        String(
            registro.codHorario
            ||
            alumno.idHorario
            ||
            ''
        ),

        String(
            alumno.id
            ||
            alumno.codigoEstudiante
            ||
            ''
        )

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
            alumno.observacion || ''
        ).trim();


    if (
        !asistencia
        &&
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


        console.log(
            'Guardado local:',
            asistenciasGuardadas[clave]
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

        return;
    }


    alumno.asistencia =
        normalizarAsistencia(
            guardado.asistencia
        );


    alumno.observacion =
        guardado.observacion || '';
}


/* =========================================================
   SEMANA
========================================================= */

function obtenerInicioSemana(
    fecha
) {

    const resultado =
        new Date(fecha);


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
        resultado.getDate()
        +
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
        new Date(fecha);


    resultado.setDate(
        resultado.getDate()
        +
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
        new Date(semana);


    const numero =
        MAPA_DIAS[dia];


    const diferencia =
        numero === 0
            ? 6
            : numero - 1;


    fecha.setDate(
        fecha.getDate()
        +
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


    if (fecha instanceof Date) {

        return [

            fecha.getFullYear(),

            String(
                fecha.getMonth() + 1
            ).padStart(2, '0'),

            String(
                fecha.getDate()
            ).padStart(2, '0')

        ].join('-');
    }


    const texto =
        String(fecha).trim();


    const iso =
        texto.match(
            /^(\d{4})-(\d{1,2})-(\d{1,2})/
        );


    if (iso) {

        return [

            iso[1],

            String(
                iso[2]
            ).padStart(2, '0'),

            String(
                iso[3]
            ).padStart(2, '0')

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
            ).padStart(2, '0'),

            String(
                latino[1]
            ).padStart(2, '0')

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
            Number(partes[0]),
            Number(partes[1]) - 1,
            Number(partes[2])
        );


    return date.toLocaleDateString(
        'es-PE',
        {

            weekday: 'long',

            day: '2-digit',

            month: 'long',

            year: 'numeric'
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
            : new Date(fecha);


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

            day: '2-digit',

            month: '2-digit'
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
        inicio === null
        ||
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
        valor === null
        ||
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
        periodo === 'PM'
        &&
        horas < 12
    ) {

        horas += 12;
    }


    if (
        periodo === 'AM'
        &&
        horas === 12
    ) {

        horas = 0;
    }


    return horas +
        (minutos / 60);
}


/* =========================================================
   HORA A MINUTOS
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
        horas.fin - horas.inicio
    );
}


/* =========================================================
   ESCAPAR HTML
========================================================= */

function escapeHTML(
    valor
) {

    if (
        valor === null
        ||
        valor === undefined
    ) {

        return '';
    }


    return String(valor)

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
   ESCAPAR ATRIBUTO
========================================================= */

function escapeAttribute(
    valor
) {

    return escapeHTML(
        valor
    );
}


/* =========================================================
   TEXTO
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

    obtenerDatos: function () {

        return {

            asistenciaData,

            horarioData,

            alumnosData,

            docenteActual,

            semestreActual,

            semanaActual,

            registroSeleccionado,

            asistenciasGuardadas
        };
    },


    limpiarAsistenciasLocales: function () {

        localStorage.removeItem(
            STORAGE_KEY
        );


        asistenciasGuardadas = {};


        console.log(
            'Asistencias locales eliminadas.'
        );
    },


    recargar: function () {

        cargarDatos();
    }
};

