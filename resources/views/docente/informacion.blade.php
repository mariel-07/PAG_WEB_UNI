<x-app-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">
        <div class="docente-page-header">
            <div>
                <span class="docente-page-header__eyebrow">
                    GESTIÓN ACADÉMICA
                </span>

                <h2 class="docente-page-header__title">
                    Información Docente
                </h2>

                <p class="docente-page-header__subtitle">
                    Registro y administración de información del personal docente
                </p>
            </div>

            <div class="docente-header-line"></div>
        </div>
    </x-slot>


    {{-- =========================================================
         CONTENEDOR PRINCIPAL
    ========================================================== --}}

    <div class="docente-container">


        {{-- =====================================================
             CABECERA DEL PERFIL
        ====================================================== --}}

        <section class="docente-profile">

            <div class="docente-profile__info">

                <div class="docente-profile__icon">
                    <i class="fas fa-user-tie"></i>
                </div>

                <div>
                    <span class="docente-profile__label">
                        REGISTRO DEL PERSONAL
                    </span>

                    <h1>
                        Información del Docente
                    </h1>

                    <p>
                        Complete y mantenga actualizada la información registrada.
                    </p>
                </div>

            </div>


            <div class="docente-profile__actions">

                <button
                    type="button"
                    class="btn-docente btn-docente--outline"
                    id="btnGenerarPDF">

                    <i class="fas fa-file-pdf"></i>

                    Generar PDF

                </button>

            </div>

        </section>


        {{-- =====================================================
             NAVEGACIÓN DE SECCIONES
        ====================================================== --}}

        <section class="docente-progress">

            <div id="navegacionSecciones">


                <button
                    type="button"
                    class="seccion-nav activo"
                    data-seccion="1">

                    <span class="seccion-nav__number">
                        01
                    </span>

                    <span class="seccion-nav__content">

                        <strong>
                            Datos personales
                        </strong>

                        <small>
                            Identificación
                        </small>

                    </span>

                </button>


                <div class="seccion-nav-line"></div>


                <button
                    type="button"
                    class="seccion-nav"
                    data-seccion="2">

                    <span class="seccion-nav__number">
                        02
                    </span>

                    <span class="seccion-nav__content">

                        <strong>
                            Domicilio
                        </strong>

                        <small>
                            Ubicación
                        </small>

                    </span>

                </button>


                <div class="seccion-nav-line"></div>


                <button
                    type="button"
                    class="seccion-nav"
                    data-seccion="3">

                    <span class="seccion-nav__number">
                        03
                    </span>

                    <span class="seccion-nav__content">

                        <strong>
                            Formación profesional
                        </strong>

                        <small>
                            Estudios académicos
                        </small>

                    </span>

                </button>


                <div class="seccion-nav-line"></div>


                <button
                    type="button"
                    class="seccion-nav"
                    data-seccion="4">

                    <span class="seccion-nav__number">
                        04
                    </span>

                    <span class="seccion-nav__content">

                        <strong>
                            Adicionales
                        </strong>

                        <small>
                            Carga familiar
                        </small>

                    </span>

                </button>

            </div>

        </section>



        {{-- =====================================================
             CONTENIDO DE LAS 4 SECCIONES
        ====================================================== --}}

        <main id="contenidoReporte">


            {{-- =================================================
                 SECCIÓN 01 - DATOS PERSONALES
            ================================================== --}}

            <section
                id="seccion-1"
                class="seccion-docente seccion-activa">

                <div class="docente-section-card">


                    <div class="docente-section-header">

                        <div class="docente-section-title">

                            <div class="docente-section-icon">
                                <i class="fas fa-id-card"></i>
                            </div>

                            <div>

                                <span>
                                    SECCIÓN 01
                                </span>

                                <h3>
                                    Datos personales
                                </h3>

                                <p>
                                    Información de identificación y datos generales del docente.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="docente-section-body">

                        <form id="formDatosPersonales">

                            <div class="docente-form-grid">


                                {{-- SEXO --}}

                                <div class="docente-field">

                                    <label for="select-sexo">
                                        Sexo
                                    </label>

                                    <select
                                        id="select-sexo"
                                        class="form-select persistir-input">

                                        <option value="Masculino">
                                            Masculino
                                        </option>

                                        <option value="Femenino">
                                            Femenino
                                        </option>

                                    </select>

                                </div>


                                {{-- FECHA NACIMIENTO --}}

                                <div class="docente-field">

                                    <label for="input-fec-nac">
                                        Fecha de nacimiento
                                    </label>

                                    <input
                                        type="date"
                                        id="input-fec-nac"
                                        class="form-control persistir-input">

                                </div>


                                {{-- DNI --}}

                                <div class="docente-field">

                                    <label for="input-dni">

                                        DNI

                                        <i class="fas fa-check-circle field-valid"></i>

                                    </label>

                                    <input
                                        type="text"
                                        id="input-dni"
                                        class="form-control"
                                        readonly>

                                </div>


                                {{-- TELEFONO --}}

                                <div class="docente-field">

                                    <label for="input-tel-fijo">
                                        Teléfono fijo
                                    </label>

                                    <input
                                        type="text"
                                        id="input-tel-fijo"
                                        class="form-control persistir-input"
                                        placeholder="Teléfono fijo">

                                </div>


                                {{-- CELULAR --}}

                                <div class="docente-field">

                                    <label for="input-celular">
                                        Celular
                                    </label>

                                    <input
                                        type="text"
                                        id="input-celular"
                                        class="form-control persistir-input"
                                        placeholder="Celular">

                                </div>


                                {{-- RUC --}}

                                <div class="docente-field">

                                    <label for="input-ruc">
                                        RUC
                                    </label>

                                    <input
                                        type="text"
                                        id="input-ruc"
                                        class="form-control persistir-input">

                                </div>


                                {{-- LIBRETA --}}

                                <div class="docente-field">

                                    <label for="input-lib-militar">
                                        Libreta militar
                                    </label>

                                    <input
                                        type="text"
                                        id="input-lib-militar"
                                        class="form-control persistir-input">

                                </div>


                                {{-- BREVETE --}}

                                <div class="docente-field">

                                    <label for="input-brevete">
                                        Brevete y categoría
                                    </label>

                                    <input
                                        type="text"
                                        id="input-brevete"
                                        class="form-control persistir-input">

                                </div>


                                {{-- VEHICULO --}}

                                <div class="docente-field docente-field--wide">

                                    <label for="input-vehiculo">
                                        Vehículo
                                    </label>

                                    <input
                                        type="text"
                                        id="input-vehiculo"
                                        class="form-control persistir-input"
                                        placeholder="Modelo, placa, año y color">

                                </div>


                                {{-- ESTADO CIVIL --}}

                                <div class="docente-field">

                                    <label for="select-estado-civil">
                                        Estado civil
                                    </label>

                                    <select
                                        id="select-estado-civil"
                                        class="form-select persistir-input">

                                        <option value="Soltero">
                                            Soltero(a)
                                        </option>

                                        <option value="Casado">
                                            Casado(a)
                                        </option>

                                        <option value="Divorciado">
                                            Divorciado(a)
                                        </option>

                                    </select>

                                </div>


                                {{-- MATRIMONIO --}}

                                <div
                                    class="docente-field"
                                    id="contenedor-fecha-matrimonio">

                                    <label for="input-fec-matrimonio">
                                        Fecha de matrimonio
                                    </label>

                                    <input
                                        type="date"
                                        id="input-fec-matrimonio"
                                        class="form-control persistir-input">

                                </div>


                                {{-- SANGRE --}}

                                <div class="docente-field">

                                    <label for="input-grupo-sanguineo">
                                        Grupo sanguíneo
                                    </label>

                                    <input
                                        type="text"
                                        id="input-grupo-sanguineo"
                                        class="form-control persistir-input"
                                        placeholder="Ej. O+">

                                </div>

                            </div>


                            {{-- =================================================
                                 LUGAR DE NACIMIENTO
                            ================================================== --}}

                            <div class="docente-subsection">

                                <div class="docente-subsection__header">

                                    <div class="docente-subsection__icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>

                                    <div>

                                        <h4>
                                            Lugar de nacimiento
                                        </h4>

                                        <p>
                                            Ubicación geográfica registrada.
                                        </p>

                                    </div>

                                </div>


                                <div class="docente-form-grid">


                                    <div class="docente-field">

                                        <label for="select-pais">
                                            País
                                        </label>

                                        <select
                                            id="select-pais"
                                            class="form-select persistir-input">

                                            <option value="">
                                                -- Seleccionar --
                                            </option>

                                            <option value="PE">
                                                Perú
                                            </option>

                                            <option value="OTRO">
                                                Otro país
                                            </option>

                                        </select>

                                    </div>


                                    <div
                                        id="campo-depa"
                                        class="docente-field d-none">

                                        <label for="select-depa">
                                            Departamento
                                        </label>

                                        <select
                                            id="select-depa"
                                            class="form-select persistir-input">

                                            <option value="">
                                                -- Seleccionar --
                                            </option>

                                        </select>

                                    </div>


                                    <div
                                        id="campo-prov"
                                        class="docente-field d-none">

                                        <label for="select-prov">
                                            Provincia
                                        </label>

                                        <select
                                            id="select-prov"
                                            class="form-select persistir-input"
                                            disabled>

                                            <option value="">
                                                -- Seleccionar --
                                            </option>

                                        </select>

                                    </div>


                                    <div
                                        id="campo-dist"
                                        class="docente-field d-none">

                                        <label for="select-dist">
                                            Distrito
                                        </label>

                                        <select
                                            id="select-dist"
                                            class="form-select persistir-input"
                                            disabled>

                                            <option value="">
                                                -- Seleccionar --
                                            </option>

                                        </select>

                                    </div>

                                </div>

                            </div>


                            <div class="docente-form-footer">

                                <button
                                    type="button"
                                    class="btn-docente btn-docente--primary"
                                    id="btnGuardarDatosPersonales">

                                    <i class="fas fa-save"></i>

                                    Guardar información

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </section>



            {{-- =================================================
                 SECCIÓN 02 - DOMICILIO
            ================================================== --}}

            <section
                id="seccion-2"
                class="seccion-docente">

                <div class="docente-section-card">

                    <div class="docente-section-header">

                        <div class="docente-section-title">

                            <div class="docente-section-icon">
                                <i class="fas fa-home"></i>
                            </div>

                            <div>

                                <span>
                                    SECCIÓN 02
                                </span>

                                <h3>
                                    Registro de domicilio
                                </h3>

                                <p>
                                    Direcciones y datos de ubicación del docente.
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="btn-docente btn-docente--light"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDomicilio">

                            <i class="fas fa-plus"></i>

                            Añadir domicilio

                        </button>

                    </div>


                    <div class="docente-table-wrapper">

                        <table class="table docente-table">

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>País</th>
                                    <th>Dirección</th>
                                    <th>Departamento</th>
                                    <th>Provincia</th>
                                    <th>Distrito</th>
                                    <th>Tipo vía</th>
                                    <th>Nombre vía</th>
                                    <th>Tipo inmueble</th>
                                    <th>N.º inmueble</th>
                                    <th>Acción</th>
                                </tr>

                            </thead>

                            <tbody id="tablaDomicilio">
                            </tbody>

                        </table>

                    </div>

                </div>

            </section>



            {{-- =================================================
                 SECCIÓN 03 - FORMACIÓN
            ================================================== --}}

            <section
                id="seccion-3"
                class="seccion-docente">

                <div class="docente-section-card">

                    <div class="docente-section-header">

                        <div class="docente-section-title">

                            <div class="docente-section-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>

                            <div>

                                <span>
                                    SECCIÓN 03
                                </span>

                                <h3>
                                    Formación profesional
                                </h3>

                                <p>
                                    Estudios, grados académicos y especialidades.
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="btn-docente btn-docente--light"
                            data-bs-toggle="modal"
                            data-bs-target="#modalFormacion">

                            <i class="fas fa-plus"></i>

                            Añadir formación

                        </button>

                    </div>


                    <div class="docente-table-wrapper">

                        <table class="table docente-table">

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>Formación</th>
                                    <th>País</th>
                                    <th>Universidad</th>
                                    <th>Especialidad</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Grado</th>
                                    <th>Doc.</th>
                                    <th>Obs.</th>
                                    <th>Acción</th>
                                </tr>

                            </thead>

                            <tbody id="tablaFormacion">
                            </tbody>

                        </table>

                    </div>

                </div>

            </section>



            {{-- =================================================
                 SECCIÓN 04 - ADICIONALES
            ================================================== --}}

            <section
                id="seccion-4"
                class="seccion-docente">

                <div class="docente-section-card">

                    <div class="docente-section-header">

                        <div class="docente-section-title">

                            <div class="docente-section-icon">
                                <i class="fas fa-users"></i>
                            </div>

                            <div>

                                <span>
                                    SECCIÓN 04
                                </span>

                                <h3>
                                    Información adicional
                                </h3>

                                <p>
                                    Registro de carga familiar y otros datos complementarios.
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="btn-docente btn-docente--light"
                            data-bs-toggle="modal"
                            data-bs-target="#modalFamiliar">

                            <i class="fas fa-plus"></i>

                            Añadir familiar

                        </button>

                    </div>


                    <div class="docente-table-wrapper">

                        <table class="table docente-table">

                            <thead>

                                <tr>
                                    <th>Apellidos y nombres</th>
                                    <th>Parentesco</th>
                                    <th>Fecha nac.</th>
                                    <th>Estado civil</th>
                                    <th>Estudios</th>
                                    <th>Ocupación</th>
                                    <th>Celular</th>
                                    <th>Depende</th>
                                    <th>Acción</th>
                                </tr>

                            </thead>

                            <tbody id="tablaFamiliar">
                            </tbody>

                        </table>

                    </div>

                </div>

            </section>

        </main>



        {{-- =====================================================
             NAVEGACIÓN INFERIOR
        ====================================================== --}}

        <div class="docente-navigation">

            <button
                type="button"
                class="btn-docente btn-docente--secondary"
                id="btnAnterior"
                disabled>

                <i class="fas fa-arrow-left"></i>

                Anterior

            </button>


            <div class="docente-navigation__info">

                <span>
                    SECCIÓN
                </span>

                <strong id="seccionActual">
                    01
                </strong>

                <span>
                    / 04
                </span>

            </div>


            <button
                type="button"
                class="btn-docente btn-docente--primary"
                id="btnSiguiente">

                Siguiente

                <i class="fas fa-arrow-right"></i>

            </button>

        </div>

    </div>



    {{-- =========================================================
         MODAL DOMICILIO
    ========================================================== --}}

    <div
        class="modal fade docente-modal"
        id="modalDomicilio"
        tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div class="docente-modal-title">

                        <div class="docente-modal-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>

                        <div>

                            <span>
                                REGISTRO
                            </span>

                            <h5>
                                Agregar domicilio
                            </h5>

                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <form id="formDomicilio">

                    <div class="modal-body">

                        <div class="docente-form-grid">

                            <div class="docente-field">

                                <label>
                                    País
                                </label>

                                <select
                                    class="form-select"
                                    id="selectPaisDom">

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                    <option value="PE">
                                        Perú
                                    </option>

                                    <option value="EXT">
                                        Extranjero
                                    </option>

                                </select>

                            </div>


                            <div class="docente-field">

                                <label>
                                    Departamento
                                </label>

                                <select
                                    class="form-select"
                                    id="selectDepartamentoDom"
                                    disabled>

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                </select>

                            </div>


                            <div class="docente-field">

                                <label>
                                    Provincia
                                </label>

                                <select
                                    class="form-select"
                                    id="selectProvinciaDom"
                                    disabled>

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                </select>

                            </div>


                            <div class="docente-field">

                                <label>
                                    Distrito
                                </label>

                                <select
                                    class="form-select"
                                    id="selectDistritoDom"
                                    disabled>

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                </select>

                            </div>


                            <div class="docente-field">

                                <label>
                                    Tipo de vía
                                </label>

                                <select
                                    class="form-select"
                                    id="selectTipoVia">

                                    <option value="-">
                                        -- Seleccionar --
                                    </option>

                                    <option value="AV">
                                        Avenida
                                    </option>

                                    <option value="JR">
                                        Jirón
                                    </option>

                                    <option value="CALLE">
                                        Calle
                                    </option>

                                </select>

                            </div>


                            <div class="docente-field">

                                <label>
                                    Nombre de la vía
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nombreVia">

                            </div>


                            <div class="docente-field">

                                <label>
                                    Tipo de inmueble
                                </label>

                                <select
                                    class="form-select"
                                    id="selectTipoInmueble">

                                    <option value="-">
                                        -- Seleccionar --
                                    </option>

                                    <option value="CASA">
                                        Casa
                                    </option>

                                    <option value="DEP">
                                        Departamento
                                    </option>

                                </select>

                            </div>


                            <div class="docente-field">

                                <label>
                                    Número de inmueble
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="numeroInmueble">

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn-docente btn-docente--secondary"
                            data-bs-dismiss="modal">

                            Cancelar

                        </button>

                        <button
                            type="button"
                            class="btn-docente btn-docente--primary"
                            id="btnGuardarDomicilio">

                            <i class="fas fa-save"></i>

                            Guardar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
         MODAL FORMACIÓN
    ========================================================== --}}

    <div
        class="modal fade docente-modal"
        id="modalFormacion"
        tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div class="docente-modal-title">

                        <div class="docente-modal-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div>

                            <span>
                                REGISTRO
                            </span>

                            <h5>
                                Formación profesional
                            </h5>

                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <form id="formFormacion">

                    <div class="modal-body">

                        <div class="docente-form-grid">

                            <div class="docente-field">

                                <label>
                                    Nivel de formación
                                </label>

                                <select
                                    class="form-select"
                                    id="formacionNivel">

                                    <option value="-">
                                        Seleccionar
                                    </option>

                                    <option value="Licenciatura">
                                        Licenciatura
                                    </option>

                                    <option value="Maestría">
                                        Maestría
                                    </option>

                                    <option value="Doctorado">
                                        Doctorado
                                    </option>

                                </select>

                            </div>


                            <div class="docente-field">

                                <label>
                                    Universidad
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="formacionUniversidad">

                            </div>


                            <div class="docente-field">

                                <label>
                                    Especialidad
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="formacionEspecialidad">

                            </div>


                            <div class="docente-field">

                                <label>
                                    Grado obtenido
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="formacionGrado">

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn-docente btn-docente--secondary"
                            data-bs-dismiss="modal">

                            Cancelar

                        </button>

                        <button
                            type="button"
                            class="btn-docente btn-docente--primary"
                            id="btnGuardarFormacion">

                            <i class="fas fa-save"></i>

                            Guardar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
         MODAL FAMILIAR
    ========================================================== --}}

    <div
        class="modal fade docente-modal"
        id="modalFamiliar"
        tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div class="docente-modal-title">

                        <div class="docente-modal-icon">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>

                            <span>
                                REGISTRO
                            </span>

                            <h5>
                                Carga familiar
                            </h5>

                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <form id="formFamiliar">

                    <div class="modal-body">

                        <div class="docente-form-grid">

                            <div class="docente-field">

                                <label>
                                    Nombres y apellidos
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="familiarNombre">

                            </div>


                            <div class="docente-field">

                                <label>
                                    Parentesco
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="familiarParentesco">

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn-docente btn-docente--secondary"
                            data-bs-dismiss="modal">

                            Cancelar

                        </button>

                        <button
                            type="button"
                            class="btn-docente btn-docente--primary"
                            id="btnGuardarFamiliar">

                            <i class="fas fa-save"></i>

                            Guardar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
         CSS
    ========================================================== --}}

    @vite(['public/css/informacion.css'])


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    <script src="{{ asset('js/informacion.js') }}"></script>


</x-app-layout>