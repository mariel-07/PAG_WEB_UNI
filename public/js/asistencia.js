
/* =========================================================
   ASISTENCIA DOCENTE
   =========================================================

   RELACIÓN DE LOS JSON:

   asistencia-docente.json
          codHorario
              ↓
   horario.json
          codHorario
              ↓
   alumnos.json
          idHorario

========================================================= */


/* =========================================================
   CONFIGURACIÓN
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

    'LU': 'LUNES',
    'L': 'LUNES',

    'MA': 'MARTES',
    'M': 'MARTES',

    'MI': 'MIÉRCOLES',
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


/* =========================================================
   VARIABLES GLOBALES
========================================================= */

let asistenciaData = [];

let horarioData = [];

let alumnosData = [];

let semanaActual =
    obtenerInicioSemana(new Date());

let docenteActual = 87546374;

let semestreActual = '20262';

let registroSeleccionado = null;


/* =========================================================
   INICIALIZACIÓN
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        console.log(
            '================================='
        );

        console.log(
            'ASISTENCIA DOCENTE'
        );

        console.log(
            'Inicializando módulo...'
        );

        console.log(
            '================================='
        );


        /* -----------------------------------------
           DOCENTE
        ----------------------------------------- */

        if (
            window.codDocente
        ) {

            docenteActual =
                Number(
                    window.codDocente
                );

        }


        /* -----------------------------------------
           SEMESTRE
        ----------------------------------------- */

        const selectSemestre =
            document.getElementById(
                'selectSemestre'
            );


        if (selectSemestre) {

            semestreActual =
                String(
                    selectSemestre.value
                );

        }


        /* -----------------------------------------
           CARGAR LOS 3 JSON
        ----------------------------------------- */

        cargarDatos();


        /* -----------------------------------------
           CAMBIO DE SEMESTRE
        ----------------------------------------- */

        if (selectSemestre) {

            selectSemestre.addEventListener(
                'change',
                function () {

                    semestreActual =
                        String(
                            this.value
                        );


                    semanaActual =
                        obtenerInicioSemana(
                            new Date()
                        );


                    cargarDatos();

                }
            );

        }


        /* -----------------------------------------
           SEMANA ANTERIOR
        ----------------------------------------- */

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


        /* -----------------------------------------
           SEMANA SIGUIENTE
        ----------------------------------------- */

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


        /* -----------------------------------------
           SEMANA ACTUAL
        ----------------------------------------- */

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

    }
);


/* =========================================================
   1. CARGAR LOS 3 JSON
========================================================= */

async function cargarDatos() {

    try {

        console.log(
            'Cargando JSON...'
        );


        /* -----------------------------------------
           URLs
        ----------------------------------------- */

        const urlAsistencia =
            window.urlAsistenciaJson ||
            '/json/asistencia-docente.json';


        const urlHorario =
            window.urlHorarioJson ||
            '/json/horario.json';


        const urlAlumnos =
            window.urlAlumnosJson ||
            '/json/alumnos.json';


        /* -----------------------------------------
           PETICIONES SIMULTÁNEAS
        ----------------------------------------- */

        const [
            responseAsistencia,
            responseHorario,
            responseAlumnos
        ] = await Promise.all([

            fetch(
                urlAsistencia,
                {
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


        /* -----------------------------------------
           VALIDAR RESPUESTAS
        ----------------------------------------- */

        if (
            !responseAsistencia.ok
        ) {

            throw new Error(
                'No se pudo cargar asistencia-docente.json'
            );

        }


        if (
            !responseHorario.ok
        ) {

            throw new Error(
                'No se pudo cargar horario.json'
            );

        }


        if (
            !responseAlumnos.ok
        ) {

            throw new Error(
                'No se pudo cargar alumnos.json'
            );

        }


        /* -----------------------------------------
           CONVERTIR JSON
        ----------------------------------------- */

        asistenciaData =
            await responseAsistencia.json();


        horarioData =
            await responseHorario.json();


        alumnosData =
            await responseAlumnos.json();


        /* -----------------------------------------
           VALIDAR ARRAYS
        ----------------------------------------- */

        if (
            !Array.isArray(
                asistenciaData
            )
        ) {

            throw new Error(
                'asistencia-docente.json debe ser un arreglo'
            );

        }


        if (
            !Array.isArray(
                horarioData
            )
        ) {

            throw new Error(
                'horario.json debe ser un arreglo'
            );

        }


        if (
            !Array.isArray(
                alumnosData
            )
        ) {

            throw new Error(
                'alumnos.json debe ser un arreglo'
            );

        }


        /* -----------------------------------------
           CONSOLA
        ----------------------------------------- */

        console.log(
            'Asistencias:',
            asistenciaData
        );


        console.log(
            'Horarios:',
            horarioData
        );


        console.log(
            'Alumnos:',
            alumnosData
        );


        console.log(
            'Total alumnos:',
            alumnosData.length
        );


        /* -----------------------------------------
           RENDERIZAR
        ----------------------------------------- */

        renderizarTodo();


    } catch (error) {

        console.error(
            'Error cargando JSON:',
            error
        );


        mostrarError(
            error.message
        );

    }

}


/* =========================================================
   2. RENDERIZACIÓN GENERAL
========================================================= */

function renderizarTodo() {

    /* -----------------------------------------
       FILTRAR ASISTENCIA POR DOCENTE
    ----------------------------------------- */

    let registrosDocente =
        asistenciaData.filter(
            registro => {

                return Number(
                    registro.codDocente
                ) === Number(
                    docenteActual
                );

            }
        );


    /* -----------------------------------------
       FILTRAR POR SEMESTRE
    ----------------------------------------- */

    registrosDocente =
        registrosDocente.filter(
            registro => {

                return String(
                    registro.semestre
                ) === String(
                    semestreActual
                );

            }
        );


    console.log(
        'Registros del docente:',
        registrosDocente
    );


    /* -----------------------------------------
       INFORMACIÓN
    ----------------------------------------- */

    actualizarInformacion(
        registrosDocente
    );


    /* -----------------------------------------
       ESTADÍSTICAS
    ----------------------------------------- */

    actualizarEstadisticas(
        registrosDocente
    );


    /* -----------------------------------------
       CALENDARIO
    ----------------------------------------- */

    renderizarCalendario(
        registrosDocente
    );

}


/* =========================================================
   3. INFORMACIÓN SUPERIOR
========================================================= */

function actualizarInformacion(
    registros
) {

    const clasesUnicas = [];


    registros.forEach(
        registro => {

            const clave =
                `${registro.codHorario}|${registro.hora}`;


            if (
                !clasesUnicas.some(
                    item =>
                        item.clave === clave
                )
            ) {

                clasesUnicas.push({

                    clave: clave,

                    hora: registro.hora

                });

            }

        }
    );


    let totalHoras = 0;


    clasesUnicas.forEach(
        clase => {

            const rango =
                convertirRangoHora(
                    clase.hora
                );


            totalHoras +=
                rango.fin -
                rango.inicio;

        }
    );


    ponerTexto(
        'lblCargaHoraria',
        `${totalHoras} HRS`
    );


    const nombre =
        window.nombreDocente ||
        'Docente';


    ponerTexto(
        'lblDocente',
        nombre
    );

}


/* =========================================================
   4. ESTADÍSTICAS
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
        registro => {

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
   5. CALENDARIO
========================================================= */

function renderizarCalendario(
    registros
) {

    const tbody =
        document.getElementById(
            'tbodyAsistencia'
        );


    if (!tbody) {

        console.error(
            'No existe #tbodyAsistencia'
        );

        return;

    }


    tbody.innerHTML = '';


    actualizarTituloSemana();


    /* -----------------------------------------
       HORAS 07:00 - 23:00
    ----------------------------------------- */

    for (
        let hora = 7;
        hora < 23;
        hora++
    ) {

        const tr =
            document.createElement(
                'tr'
            );


        /* -----------------------------------------
           HORA
        ----------------------------------------- */

        const tdHora =
            document.createElement(
                'td'
            );


        tdHora.className =
            'hora-cell';


        tdHora.innerHTML = `

            <div class="hora-inicio">
                ${formatearHora(hora)}
            </div>

            <div class="hora-separador">
                a
            </div>

            <div class="hora-fin">
                ${formatearHora(hora + 1)}
            </div>

        `;


        tr.appendChild(
            tdHora
        );


        /* -----------------------------------------
           DÍAS
        ----------------------------------------- */

        DIAS.forEach(
            (
                dia,
                indiceDia
            ) => {

                const td =
                    document.createElement(
                        'td'
                    );


                const fecha =
                    sumarDias(
                        semanaActual,
                        indiceDia
                    );


                const fechaISO =
                    formatearFechaISO(
                        fecha
                    );


                /* Domingo */

                if (
                    dia === 'DOMINGO'
                ) {

                    td.classList.add(
                        'domingo-cell'
                    );

                }


                /* Feriado */

                if (
                    esFeriado(
                        fechaISO
                    )
                ) {

                    td.classList.add(
                        'feriado-cell'
                    );

                }


                /* -----------------------------------------
                   BUSCAR ASISTENCIA
                ----------------------------------------- */

                const registro =
                    buscarRegistroPorFechaHora(
                        registros,
                        fechaISO,
                        hora
                    );


                if (registro) {

                    const rango =
                        convertirRangoHora(
                            registro.hora
                        );


                    /* -----------------------------------------
                       SOLO PRIMERA FILA DEL BLOQUE
                    ----------------------------------------- */

                    if (
                        hora ===
                        rango.inicio
                    ) {

                        const duracion =
                            rango.fin -
                            rango.inicio;


                        td.rowSpan =
                            duracion;


                        td.innerHTML =
                            crearBloqueAsistencia(
                                registro,
                                fechaISO,
                                duracion
                            );


                    } else {

                        return;

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
   6. BUSCAR ASISTENCIA POR FECHA Y HORA
========================================================= */

function buscarRegistroPorFechaHora(
    registros,
    fecha,
    hora
) {

    return registros.find(
        registro => {

            if (
                String(
                    registro.fecha
                ) !== String(
                    fecha
                )
            ) {

                return false;

            }


            const rango =
                convertirRangoHora(
                    registro.hora
                );


            return (
                hora >= rango.inicio &&
                hora < rango.fin
            );

        }
    );

}


/* =========================================================
   7. CREAR BLOQUE
========================================================= */

function crearBloqueAsistencia(
    registro,
    fecha,
    duracion
) {

    const estado =
        obtenerEstadoAsistencia(
            registro
        );


    const claseEstado =
        obtenerClaseEstado(
            estado
        );


    const altura =
        Math.max(
            53,
            (duracion * 54) - 4
        );


    return `

        <div
            class="bloque-asistencia ${claseEstado}"
            style="min-height:${altura}px"
        >

            <div class="bloque-curso">

                <i class="fas fa-book-open me-1"></i>

                ${escapeHTML(
                    registro.codCurso ||
                    'CURSO'
                )}

            </div>


            <div class="bloque-info">

                ${escapeHTML(
                    registro.hora ||
                    '--'
                )}

                &nbsp; | &nbsp;

                Sección
                ${escapeHTML(
                    registro.secCurso ||
                    '-'
                )}

            </div>


            <div class="bloque-info">

                <i class="fas fa-chalkboard me-1"></i>

                ${escapeHTML(
                    registro.teopra ||
                    '-'
                )}

            </div>


            <div class="bloque-estado">

                ${escapeHTML(
                    estado
                )}

            </div>


            <button
                type="button"
                class="bloque-ojo"
                title="Ver alumnos y asistencia"
                onclick="abrirDetalleAsistencia(${Number(registro.id)})"
            >

                <i class="fas fa-eye"></i>

            </button>

        </div>

    `;

}


/* =========================================================
   8. ESTADO DOCENTE
========================================================= */

function obtenerEstadoAsistencia(
    registro
) {

    if (!registro) {

        return 'PROGRAMADA';

    }


    if (
        Number(
            registro.estado
        ) !== 1
    ) {

        return 'NO APLICA';

    }


    if (
        !registro.horEntrada &&
        !registro.horSalida
    ) {

        return 'INASISTENCIA';

    }


    if (
        registro.horEntrada
    ) {

        const horaProgramada =
            obtenerHoraInicio(
                registro.hora
            );


        const entrada =
            convertirHoraMinutos(
                registro.horEntrada
            );


        const programada =
            convertirHoraMinutos(
                horaProgramada
            );


        const diferencia =
            entrada -
            programada;


        if (
            diferencia <= 10
        ) {

            return 'PUNTUAL';

        }


        return 'TARDANZA';

    }


    if (
        registro.horSalida
    ) {

        return 'PUNTUAL';

    }


    return 'PROGRAMADA';

}


/* =========================================================
   9. CLASE CSS ESTADO
========================================================= */

function obtenerClaseEstado(
    estado
) {

    const valor =
        String(
            estado || ''
        )
        .trim()
        .toUpperCase();


    switch (valor) {

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
   10. ABRIR DETALLE
========================================================= */

function abrirDetalleAsistencia(
    id
) {

    /* -----------------------------------------
       BUSCAR ASISTENCIA
    ----------------------------------------- */

    const registro =
        asistenciaData.find(
            item =>
                Number(
                    item.id
                ) === Number(
                    id
                )
        );


    if (!registro) {

        console.error(
            'No se encontró asistencia:',
            id
        );

        return;

    }


    registroSeleccionado =
        registro;


    console.log(
        'Registro seleccionado:',
        registro
    );


    /* -----------------------------------------
       BUSCAR HORARIO
    ----------------------------------------- */

    const horario =
        horarioData.find(
            item =>
                Number(
                    item.codHorario
                ) === Number(
                    registro.codHorario
                )
        );


    console.log(
        'Horario relacionado:',
        horario
    );


    /* -----------------------------------------
       BUSCAR ALUMNOS
       
       ESTA ES LA RELACIÓN IMPORTANTE:

       alumnos.idHorario
              ===
       asistencia.codHorario
    ----------------------------------------- */

    const alumnos =
        alumnosData.filter(
            alumno =>
                Number(
                    alumno.idHorario
                ) === Number(
                    registro.codHorario
                )
        );


    console.log(
        'Alumnos encontrados:',
        alumnos
    );


    /* -----------------------------------------
       INFORMACIÓN GENERAL
    ----------------------------------------- */

    const estado =
        obtenerEstadoAsistencia(
            registro
        );


    ponerTexto(
        'modalTitulo',
        'Control de asistencia'
    );


    ponerTexto(
        'modalSubtitulo',
        `${registro.codCurso || ''} | Sección ${registro.secCurso || ''}`
    );


    ponerTexto(
        'modalCurso',
        `${registro.codCurso || '--'} - Sección ${registro.secCurso || '--'}`
    );


    ponerTexto(
        'modalHorario',
        `${registro.fecha || '--'} | ${registro.hora || '--'}`
    );


    ponerTexto(
        'modalEstado',
        estado
    );


    /* -----------------------------------------
       INFORMACIÓN DEL HORARIO
    ----------------------------------------- */

    const modalAula =
        document.getElementById(
            'modalAula'
        );


    if (modalAula) {

        modalAula.textContent =
            horario?.codAula ||
            '--';

    }


    const modalTipo =
        document.getElementById(
            'modalTipo'
        );


    if (modalTipo) {

        modalTipo.textContent =
            horario?.teopra ||
            registro.teopra ||
            '--';

    }


    const modalTope =
        document.getElementById(
            'modalTope'
        );


    if (modalTope) {

        modalTope.textContent =
            horario?.tope ??
            '--';

    }


    /* -----------------------------------------
       RENDERIZAR ALUMNOS
    ----------------------------------------- */

    renderizarAlumnos(
        alumnos
    );


    /* -----------------------------------------
       ABRIR MODAL
    ----------------------------------------- */

    const modalElement =
        document.getElementById(
            'modalAsistencia'
        );


    if (
        modalElement &&
        typeof bootstrap !== 'undefined'
    ) {

        const modal =
            bootstrap.Modal
                .getOrCreateInstance(
                    modalElement
                );


        modal.show();

    } else {

        console.error(
            'No se encontró modalAsistencia o Bootstrap no está cargado'
        );

    }

}


/* =========================================================
   11. RENDERIZAR ALUMNOS
========================================================= */

function renderizarAlumnos(
    alumnos
) {

    const lista =
        document.getElementById(
            'listaAlumnos'
        );


    if (!lista) {

        console.error(
            'No existe #listaAlumnos'
        );

        return;

    }


    /* -----------------------------------------
       SIN ALUMNOS
    ----------------------------------------- */

    if (
        alumnos.length === 0
    ) {

        lista.innerHTML = `

            <div class="text-center py-5">

                <i
                    class="fas fa-user-slash fa-2x mb-3"
                ></i>

                <h6>
                    No hay alumnos registrados
                </h6>

                <small class="text-muted">

                    No se encontraron alumnos
                    asociados al horario
                    ${registroSeleccionado?.codHorario || '--'}.

                </small>

            </div>

        `;

        return;

    }


    /* -----------------------------------------
       ESTADÍSTICAS
    ----------------------------------------- */

    const total =
        alumnos.length;


    const presentes =
        alumnos.filter(
            alumno =>
                String(
                    alumno.asistencia
                ).toUpperCase() ===
                'PRESENTE'
        ).length;


    const faltas =
        alumnos.filter(
            alumno =>
                String(
                    alumno.asistencia
                ).toUpperCase() ===
                'FALTO'
        ).length;


    const tardanzas =
        alumnos.filter(
            alumno =>
                String(
                    alumno.asistencia
                ).toUpperCase() ===
                'TARDANZA'
        ).length;


    /* -----------------------------------------
       TABLA
    ----------------------------------------- */

    let html = `

        <div class="row g-3 mb-3">

            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small class="text-muted">
                        TOTAL
                    </small>

                    <div class="fs-4 fw-bold">
                        ${total}
                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small class="text-muted">
                        PRESENTES
                    </small>

                    <div class="fs-4 fw-bold">
                        ${presentes}
                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small class="text-muted">
                        FALTAS
                    </small>

                    <div class="fs-4 fw-bold">
                        ${faltas}
                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small class="text-muted">
                        TARDANZAS
                    </small>

                    <div class="fs-4 fw-bold">
                        ${tardanzas}
                    </div>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Código
                        </th>

                        <th>
                            Alumno
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


    /* -----------------------------------------
       GENERAR FILAS
    ----------------------------------------- */

    alumnos.forEach(
        (
            alumno,
            index
        ) => {

            const asistencia =
                String(
                    alumno.asistencia ||
                    'SIN REGISTRO'
                )
                .toUpperCase();


            const clase =
                obtenerClaseAsistenciaAlumno(
                    asistencia
                );


            html += `

                <tr>

                    <td>
                        ${index + 1}
                    </td>


                    <td>

                        <strong>
                            ${escapeHTML(
                                alumno.codigoEstudiante ||
                                '--'
                            )}
                        </strong>

                    </td>


                    <td>

                        ${escapeHTML(
                            alumno.nombres ||
                            '--'
                        )}

                    </td>


                    <td>

                        <small>
                            ${escapeHTML(
                                alumno.correo ||
                                '--'
                            )}
                        </small>

                    </td>


                    <td>

                        <span
                            class="badge ${clase}"
                        >

                            ${escapeHTML(
                                asistencia
                            )}

                        </span>

                    </td>


                    <td>

                        ${escapeHTML(
                            alumno.observacion ||
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

        </div>

    `;


    lista.innerHTML =
        html;

}


/* =========================================================
   12. CLASE ASISTENCIA ALUMNO
========================================================= */

function obtenerClaseAsistenciaAlumno(
    asistencia
) {

    switch (
        String(
            asistencia
        )
        .toUpperCase()
    ) {

        case 'PRESENTE':

            return 'bg-success';


        case 'FALTO':

            return 'bg-danger';


        case 'TARDANZA':

            return 'bg-warning text-dark';


        case 'JUSTIFICADO':

            return 'bg-info';


        default:

            return 'bg-secondary';

    }

}


/* =========================================================
   13. CONVERTIR RANGO
========================================================= */

function convertirRangoHora(
    rango
) {

    if (!rango) {

        return {

            inicio: 0,

            fin: 0

        };

    }


    const partes =
        String(
            rango
        )
        .trim()
        .split('-');


    if (
        partes.length < 2
    ) {

        return {

            inicio: 0,

            fin: 0

        };

    }


    const inicio =
        parseInt(
            partes[0],
            10
        );


    const fin =
        parseInt(
            partes[1],
            10
        );


    return {

        inicio:
            isNaN(inicio)
                ? 0
                : inicio,

        fin:
            isNaN(fin)
                ? 0
                : fin

    };

}


/* =========================================================
   14. HORA INICIAL
========================================================= */

function obtenerHoraInicio(
    rango
) {

    const partes =
        String(
            rango || ''
        )
        .split('-');


    if (
        partes.length === 0
    ) {

        return '00:00:00';

    }


    return (
        partes[0]
            .trim()
            .padStart(
                2,
                '0'
            )
        + ':00:00'
    );

}


/* =========================================================
   15. CONVERTIR HORA A MINUTOS
========================================================= */

function convertirHoraMinutos(
    hora
) {

    if (!hora) {

        return 0;

    }


    const partes =
        String(
            hora
        )
        .split(':');


    const horas =
        parseInt(
            partes[0] || 0,
            10
        );


    const minutos =
        parseInt(
            partes[1] || 0,
            10
        );


    return (
        horas * 60 +
        minutos
    );

}


/* =========================================================
   16. FORMATEAR HORA
========================================================= */

function formatearHora(
    hora
) {

    return String(
        hora
    )
    .padStart(
        2,
        '0'
    ) + ':00';

}


/* =========================================================
   17. INICIO DE SEMANA
========================================================= */

function obtenerInicioSemana(
    fecha
) {

    const nuevaFecha =
        new Date(
            fecha
        );


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


/* =========================================================
   18. SUMAR DÍAS
========================================================= */

function sumarDias(
    fecha,
    cantidad
) {

    const nuevaFecha =
        new Date(
            fecha
        );


    nuevaFecha.setDate(
        nuevaFecha.getDate() +
        cantidad
    );


    return nuevaFecha;

}


/* =========================================================
   19. FECHA ISO
========================================================= */

function formatearFechaISO(
    fecha
) {

    const año =
        fecha.getFullYear();


    const mes =
        String(
            fecha.getMonth() + 1
        )
        .padStart(
            2,
            '0'
        );


    const dia =
        String(
            fecha.getDate()
        )
        .padStart(
            2,
            '0'
        );


    return `${año}-${mes}-${dia}`;

}


/* =========================================================
   20. TÍTULO SEMANA
========================================================= */

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


    ponerTexto(
        'lblSemana',
        `${textoInicio} - ${textoFin}`
    );

}


/* =========================================================
   21. FERIADOS
========================================================= */

function esFeriado(
    fecha
) {

    return false;

}


/* =========================================================
   22. MOSTRAR ERROR
========================================================= */

function mostrarError(
    mensaje
) {

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
                class="text-center py-5"
            >

                <i
                    class="fas fa-circle-exclamation fa-2x text-danger mb-2"
                ></i>


                <div class="fw-bold text-danger">

                    No se pudo cargar
                    la información.

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


/* =========================================================
   23. PONER TEXTO
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
            texto;

    }

}


/* =========================================================
   24. ESCAPAR HTML
========================================================= */

function escapeHTML(
    valor
) {

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


/* =========================================================
   25. DEBUG
========================================================= */

window.asistenciaDocenteDebug =
    function () {

        console.log(
            '========== DEBUG =========='
        );


        console.log(
            'Asistencia:',
            asistenciaData
        );


        console.log(
            'Horarios:',
            horarioData
        );


        console.log(
            'Alumnos:',
            alumnosData
        );


        console.log(
            'Docente:',
            docenteActual
        );


        console.log(
            'Semestre:',
            semestreActual
        );


        console.log(
            'Semana:',
            semanaActual
        );


        if (
            registroSeleccionado
        ) {

            console.log(
                'Registro seleccionado:',
                registroSeleccionado
            );


            const alumnos =
                alumnosData.filter(
                    alumno =>
                        Number(
                            alumno.idHorario
                        ) === Number(
                            registroSeleccionado.codHorario
                        )
                );


            console.log(
                'Alumnos del horario:',
                alumnos
            );

        }

    };


/* =========================================================
   FIN
========================================================= */

console.log(
    'asistencia.js cargado correctamente'
);

