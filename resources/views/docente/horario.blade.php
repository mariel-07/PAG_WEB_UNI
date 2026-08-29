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
    onchange="cargarHorariosDesdeJSON()"> <!-- AQUÍ ESTABA EL ERROR -->
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

<script src="{{ asset('js/horario.js') }}"></script>

   
</x-app-layout>