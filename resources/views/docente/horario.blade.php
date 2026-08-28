<x-app-layout>

    <!-- =========================================================
         HEADER
         ========================================================= -->
    <x-slot name="header">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

            <div class="d-flex align-items-center">

                <div class="uni-header-icon me-3">
                    <i class="fas fa-calendar-days"></i>
                </div>

                <div>
                    <h2 class="h5 fw-bold mb-1 uni-title">
                        {{ __('Horario Académico del Docente') }}
                    </h2>

                    <span class="uni-subtitle">
                        Gestión de programación académica
                    </span>
                </div>

            </div>


            <div class="uni-institution">

                <i class="fas fa-university me-2"></i>

                UNIVERSIDAD NACIONAL DE INGENIERÍA

                <span class="uni-divider">|</span>

                FIM

            </div>

        </div>

    </x-slot>


    <!-- =========================================================
         LIBRERÍAS
         ========================================================= -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet"
          href="{{ asset('css/horario.css') }}">


    <!-- =========================================================
         CONTENIDO
         ========================================================= -->

    <div class="uni-page">

        <div class="container-fluid px-4 py-4">


            <!-- =====================================================
                 ENCABEZADO DEL MÓDULO
                 ===================================================== -->

          <div class="uni-page-heading">

    <div>

        <span class="uni-section-label">
            GESTIÓN ACADÉMICA
        </span>

        <h1>
            Horario de clases
        </h1>

        <p>
            Consulte la distribución semanal de sus cursos, aulas y horarios.
        </p>

    </div>

    <button
        type="button"
        class="uni-btn-pdf"
        onclick="window.print()">

        <i class="fas fa-file-pdf"></i>

        Exportar PDF

    </button>

</div>

            </div>



            <!-- =========================================================
     INFORMACIÓN ACADÉMICA
     ========================================================= -->

<div class="uni-info-wrapper">

    <!-- SEMESTRE -->
    <div class="uni-info-card">

        <div class="uni-info-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>

        <div class="uni-info-content">

            <span>SEMESTRE ACADÉMICO</span>

            <select
                id="selectSemestre"
                class="uni-select"
                onchange="cargarHorariosDesdeBD()">

                <option value="20251" selected>2025-I</option>
                <option value="20242">2024-II</option>
                <option value="20241">2024-I</option>

            </select>

            <div class="uni-status">
                <i class="fas fa-check-circle"></i>
                DEFINITIVO
            </div>

        </div>

    </div>


    <!-- DOCENTE -->
    <div class="uni-info-card">

        <div class="uni-info-icon">
            <i class="fas fa-user"></i>
        </div>

        <div class="uni-info-content">

            <span>DOCENTE</span>

            <strong>dulce</strong>

            <small>Personal docente</small>

        </div>

    </div>


    <!-- CARGA HORARIA -->
    <div class="uni-info-card">

        <div class="uni-info-icon">
            <i class="fas fa-clock"></i>
        </div>

        <div class="uni-info-content uni-hours">

            <span>CARGA HORARIA SEMANAL</span>

            <strong id="lblCargaHoraria">
                2 HRS
            </strong>

        </div>

    </div>

</div>



            <!-- =====================================================
                 HORARIO
                 ===================================================== -->

            <div class="uni-schedule-card">


                <!-- CABECERA -->

                <div class="uni-schedule-header">

                    <div>

                        <div class="uni-schedule-title">

                            <div class="uni-calendar-icon">
                                <i class="fas fa-calendar-week"></i>
                            </div>

                            <div>

                                <h3>
                                    Programación semanal
                                </h3>

                                <p>
                                    Distribución de clases por día y horario
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="uni-schedule-info">

                        <i class="fas fa-circle-info"></i>

                        Horario institucional

                    </div>

                </div>



                <!-- =================================================
                     TABLA
                     ================================================= -->

                <div class="uni-table-container">

                    <table
                        class="table tbl-horario mb-0"
                        id="tablaHorario">
<thead>
    <tr>

        <th class="hora-column">
            HORA
        </th>

        <th>LUNES</th>

        <th>MARTES</th>

        <th>MIÉRCOLES</th>

        <th>JUEVES</th>

        <th>VIERNES</th>

        <th>SÁBADO</th>

        <th>DOMINGO</th>

    </tr>
</thead>


                        <!--
                        =================================================
                        IMPORTANTE

                        NO SE MODIFICA ESTE ID.

                        Tu JavaScript seguirá generando aquí
                        las filas del horario.
                        =================================================
                        -->

                        <tbody id="tbodyHorario">

                        </tbody>

                    </table>

                </div>



                <!-- =================================================
                     LEYENDA
                     ================================================= -->

                <div class="uni-legend">

                    <span class="uni-legend-title">
                        Referencia:
                    </span>


                    <span class="uni-legend-item">

                        <span class="uni-legend-class"></span>

                        Clase programada

                    </span>


                    <span class="uni-legend-item">

                        <span class="uni-legend-empty"></span>

                        Horario disponible

                    </span>


                    <span class="uni-legend-note">

                        <i class="fas fa-info-circle"></i>

                        Seleccione una clase para consultar información adicional.

                    </span>

                </div>

            </div>



            <!-- =====================================================
                 INFORMACIÓN INFERIOR
                 ===================================================== -->

            <div class="row g-3 mt-1">


                <div class="col-lg-8">

                    <div class="uni-help-card">

                        <div class="uni-help-icon">

                            <i class="fas fa-circle-info"></i>

                        </div>

                        <div>

                            <strong>
                                Información del horario
                            </strong>

                            <p>
                                El horario mostrado corresponde al semestre académico
                                seleccionado. Los bloques representan las clases
                                programadas para cada día y rango horario.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="uni-update-card">

                        <div class="uni-update-icon">

                            <i class="fas fa-database"></i>

                        </div>

                        <div>

                            <span>
                                ESTADO DEL SISTEMA
                            </span>

                            <strong>

                                <i class="fas fa-circle"></i>

                                Información disponible

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>



    <!-- =========================================================
         MODAL DE ALUMNOS
         ========================================================= -->

    <div
        class="modal fade"
        id="modalAlumnos"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-md modal-dialog-centered">

            <div class="modal-content uni-modal">


                <!-- HEADER -->

                <div class="uni-modal-header">

                    <div class="d-flex align-items-center">

                        <div class="uni-modal-icon">

                            <i class="fas fa-users"></i>

                        </div>

                        <div>

                            <h5
                                class="modal-title"
                                id="modalCursoTitulo">

                                ---

                            </h5>

                            <span
                                id="modalCantidadAlumnos">

                                Cantidad de alumnos: 0

                            </span>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                    </button>

                </div>



                <!-- BODY -->

                <div class="modal-body p-4">


                    <!-- BOTONES DE EXPORTACIÓN -->

                    <div class="uni-export-bar">

                        <span>
                            <i class="fas fa-file-export me-1"></i>
                            Exportar listado
                        </span>


                        <div class="d-flex gap-2">

                            <button
                                type="button"
                                class="uni-export-pdf">

                                <i class="fas fa-file-pdf"></i>

                                PDF

                            </button>


                            <button
                                type="button"
                                class="uni-export-excel">

                                <i class="fas fa-file-excel"></i>

                                Excel

                            </button>

                        </div>

                    </div>



                    <!-- LISTA DE ALUMNOS -->

                    <div
                        id="listaAlumnosContainer"
                        class="uni-student-list">

                    </div>


                </div>



                <!-- FOOTER -->

                <div class="uni-modal-footer">

                    <button
                        type="button"
                        class="uni-btn-close"
                        data-bs-dismiss="modal">

                        CERRAR

                    </button>

                </div>

            </div>

        </div>

    </div>



   <script>

    

        
    // 1. Días en el formato que guarda la base de datos
    const dias = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];
    let horariosData = [];

    document.addEventListener('DOMContentLoaded', function () {
        cargarHorariosDesdeBD();
    });

    async function cargarHorariosDesdeBD() {
        const selectSemestre = document.getElementById('selectSemestre');
        const semestre = selectSemestre ? selectSemestre.value : '20251';

        try {
            const response = await fetch(`/api/horarios?semestre=${semestre}`);
            horariosData = await response.json();
            renderizarTablaHorario();
        } catch (error) {
            console.error("Error al cargar horarios desde la base de datos:", error);
        }
    }

   // 2. Función para renderizar la tabla del horario
function renderizarTablaHorario() {

    const tbody = document.getElementById('tbodyHorario');

    if (!tbody) return;

    tbody.innerHTML = '';

    let totalHoras = 0;

    for (let h = 7; h < 23; h++) {

        const horaInicio = h < 10 ? `0${h}` : `${h}`;
        const horaFin = (h + 1) < 10 ? `0${h + 1}` : `${h + 1}`;

        const tr = document.createElement('tr');


        /* =====================================================
           COLUMNA DE HORA
           ===================================================== */

        const tdHora = document.createElement('td');

        tdHora.className = 'celda-hora';

        tdHora.innerHTML = `
            <div class="hora-inicio">
                ${horaInicio}:00
            </div>

            <div class="hora-separador">
                a
            </div>

            <div class="hora-fin">
                ${horaFin}:00
            </div>
        `;

        tr.appendChild(tdHora);


        /* =====================================================
           DÍAS DE LA SEMANA
           ===================================================== */

        dias.forEach(dia => {

            const td = document.createElement('td');

            const clase = horariosData.find(c => {

                if (c.dia !== dia) return false;

                const [inicio, fin] =
                    c.hora.split('-').map(Number);

                return h >= inicio && h < fin;

            });


            /* =================================================
               SI EXISTE UNA CLASE
               ================================================= */

            if (clase) {

                const [inicio, fin] =
                    clase.hora.split('-').map(Number);


                /* ---------------------------------------------
                   SOLO CREAR EL BLOQUE EN LA PRIMERA HORA
                   --------------------------------------------- */

                if (h === inicio) {

                    const duracion = fin - inicio;

                    td.rowSpan = duracion;

                    totalHoras += duracion;


                    /* -----------------------------------------
                       ESTILO DEL BLOQUE
                       ----------------------------------------- */

                    const esEstiloClaro =
                        clase.secCurso === 'F' ||
                        clase.secCurso === 'D';


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

                    return;

                }

            }

            tr.appendChild(td);

        });


        tbody.appendChild(tr);

    }


    /* =========================================================
       ACTUALIZAR CARGA HORARIA
       ========================================================= */

    const lblCarga =
        document.getElementById('lblCargaHoraria');

    if (lblCarga) {

        lblCarga.textContent =
            `${totalHoras} HRS`;

    }

}

    // 3. Función para cargar modal de alumnos
    async function cargarYMostrarAlumnos(clase) {
        document.getElementById('modalCursoTitulo').textContent = `${clase.codCurso}-${clase.secCurso} / ${clase.codAula} | ${clase.semestre}`;
        const container = document.getElementById('listaAlumnosContainer');
        container.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-info"></div></div>';

        const myModal = new bootstrap.Modal(document.getElementById('modalAlumnos'));
        myModal.show();

        try {
            const res = await fetch(`/docente/api/alumnos?codCurso=${clase.codCurso}&secCurso=${clase.secCurso}&semestre=${clase.semestre}`);
            const responseData = await res.json();

            const lista = responseData.alumnos || [];
            const total = responseData.total ?? lista.length;

            document.getElementById('modalCantidadAlumnos').textContent = `Cantidad de alumnos: ${total}`;
            container.innerHTML = '';

            if (lista.length === 0) {
                container.innerHTML = '<p class="text-center text-muted py-3">No hay alumnos matriculados en esta sección.</p>';
                return;
            }

            lista.forEach(al => {
                const item = document.createElement('div');
                item.className = 'p-2 border-bottom d-flex justify-content-between align-items-center';
                item.innerHTML = `
                    <div>
                        <div class="fw-bold text-danger small mb-1">
                            <i class="far fa-user me-1"></i> ${al.nombre || (al.apePaterno + ' ' + al.apeMaterno + ', ' + al.nombres)}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-mobile-alt text-primary"></i>
                            <span class="bg-light px-2 py-1 rounded border text-muted small">${al.celular || 'S/N'}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">especialidad: <strong>${al.esp || al.especialidad || 'M3'}</strong></div>
                        <div class="small text-muted fw-bold">${al.codigo || al.codAlumno}</div>
                        <a href="mailto:${al.email}" class="text-decoration-none small text-dark"><i class="fas fa-envelope me-1"></i>Enviar correo</a>
                    </div>
                `;
                container.appendChild(item);
            });
        } catch (err) {
            console.error(err);
            container.innerHTML = '<p class="text-center text-danger py-3">Error al cargar la lista de alumnos.</p>';
        }
    }
    document.getElementById('selectSemestre')?.addEventListener('change', () => {
    cargarHorariosDesdeBD();
});
</script>
</x-app-layout>