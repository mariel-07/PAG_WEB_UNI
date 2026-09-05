
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
             NAVEGACIÓN
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
             CONTENIDO
        ====================================================== --}}

        <main id="contenidoReporte">


            {{-- =================================================
                 SECCIÓN 01
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

    {{-- =====================================================
         APELLIDO PATERNO
    ====================================================== --}}

    <div class="docente-field">

        <label for="input-ape-paterno">
            Apellido paterno
        </label>

        <input
            type="text"
            id="input-ape-paterno"
            name="apePaterno"
            class="form-control persistir-input"
            maxlength="100"
            placeholder="Ingrese apellido paterno">

    </div>


    {{-- =====================================================
         APELLIDO MATERNO
    ====================================================== --}}

    <div class="docente-field">

        <label for="input-ape-materno">
            Apellido materno
        </label>

        <input
            type="text"
            id="input-ape-materno"
            name="apeMaterno"
            class="form-control persistir-input"
            maxlength="100"
            placeholder="Ingrese apellido materno">

    </div>


    {{-- =====================================================
         NOMBRES
    ====================================================== --}}

    <div class="docente-field docente-field--wide">

        <label for="input-nombres">
            Nombres
        </label>

        <input
            type="text"
            id="input-nombres"
            name="nombres"
            class="form-control persistir-input"
            maxlength="100"
            placeholder="Ingrese sus nombres">

    </div>


    {{-- =====================================================
         SEXO
    ====================================================== --}}

    <div class="docente-field">

        <label for="select-sexo">
            Sexo
        </label>

        <select
            id="select-sexo"
            class="form-select persistir-input">

            <option value="">
                -- Seleccionar --
            </option>

            <option value="Masculino">
                Masculino
            </option>

            <option value="Femenino">
                Femenino
            </option>

        </select>

    </div>


    {{-- =====================================================
         FECHA DE NACIMIENTO
    ====================================================== --}}

    <div class="docente-field">

        <label for="input-fec-nac">
            Fecha de nacimiento
        </label>

        <input
            type="date"
            id="input-fec-nac"
            class="form-control persistir-input">

    </div>


    {{-- =====================================================
         DNI
    ====================================================== --}}

    <div class="docente-field">

        <label for="input-dni">

            DNI

            <i class="fas fa-check-circle field-valid"></i>

        </label>

        <input
            type="text"
            id="input-dni"
            name="dni"
            class="form-control persistir-input"
            maxlength="15"
            inputmode="numeric"
            autocomplete="off"
            placeholder="Ingrese su DNI">

    </div>


    {{-- =====================================================
         TELÉFONO
    ====================================================== --}}

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


    {{-- =====================================================
         CELULAR
    ====================================================== --}}

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


    {{-- =====================================================
         RUC
    ====================================================== --}}

    <div class="docente-field">

        <label for="input-ruc">
            RUC
        </label>

        <input
            type="text"
            id="input-ruc"
            class="form-control persistir-input">

    </div>


    {{-- =====================================================
         LIBRETA MILITAR
    ====================================================== --}}

    <div class="docente-field">

        <label for="input-lib-militar">
            Libreta militar
        </label>

        <input
            type="text"
            id="input-lib-militar"
            class="form-control persistir-input">

    </div>


    {{-- =====================================================
         BREVETE
    ====================================================== --}}

    <div class="docente-field">

        <label for="input-brevete">
            Brevete y categoría
        </label>

        <input
            type="text"
            id="input-brevete"
            class="form-control persistir-input">

    </div>


    {{-- =====================================================
         VEHÍCULO
    ====================================================== --}}

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


    {{-- =====================================================
         ESTADO CIVIL
    ====================================================== --}}

    <div class="docente-field">

        <label for="select-estado-civil">
            Estado civil
        </label>

        <select
            id="select-estado-civil"
            class="form-select persistir-input">

            <option value="">
                -- Seleccionar --
            </option>

            <option value="Soltero">
                Soltero(a)
            </option>

            <option value="Casado">
                Casado(a)
            </option>

            <option value="Divorciado">
                Divorciado(a)
            </option>

            <option value="Viudo">
                Viudo(a)
            </option>

        </select>

    </div>


    {{-- =====================================================
         FECHA DE MATRIMONIO
    ====================================================== --}}

    <div
        class="docente-field"
        id="contenedor-fecha-matrimonio">

        <label for="input-fec-matrimonio">
            Fecha de matrimonio
        </label>

        <input
            type="date"
            id="input-fec-matrimonio"
            name="fecMatrimonio"
            class="form-control persistir-input">

    </div>


    {{-- =====================================================
         GRUPO SANGUÍNEO
    ====================================================== --}}

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




                            {{-- =============================================
                                 LUGAR DE NACIMIENTO
                            ============================================== --}}

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


                                    {{-- PAÍS --}}

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


                                    {{-- DEPARTAMENTO --}}

                                    <div
                                        id="campo-depa"
                                        class="docente-field d-none">

                                        <label for="select-depa">
                                            Departamento
                                        </label>

                                        <select
                                            id="select-depa"
                                            class="form-select persistir-input"
                                            disabled>

                                            <option value="">
                                                -- Seleccionar --
                                            </option>

                                        </select>

                                    </div>


                                    {{-- PROVINCIA --}}

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


                                    {{-- DISTRITO --}}

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


                            {{-- BOTÓN GUARDAR --}}

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
                 SECCIÓN 04 - FAMILIARES
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
        tabindex="-1"
        aria-hidden="true">

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


                            {{-- PAÍS --}}

                            <div class="docente-field">

                                <label for="selectPaisDom">
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


                            {{-- DEPARTAMENTO --}}

                            <div class="docente-field">

                                <label for="selectDepartamentoDom">
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


                            {{-- PROVINCIA --}}

                            <div class="docente-field">

                                <label for="selectProvinciaDom">
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


                            {{-- DISTRITO --}}

                            <div class="docente-field">

                                <label for="selectDistritoDom">
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


                            {{-- DIRECCIÓN EXTRANJERA --}}

                            <div
                                class="docente-field docente-field--wide">

                                <label for="inputDirExtDom">
                                    Dirección extranjera
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="inputDirExtDom"
                                    placeholder="Ingrese la dirección si reside en el extranjero">

                            </div>


                            {{-- TIPO VÍA --}}

                            <div class="docente-field">

                                <label for="selectTipoVia">
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

                                    <option value="PASAJE">
                                        Pasaje
                                    </option>

                                    <option value="CARRETERA">
                                        Carretera
                                    </option>

                                    <option value="OTRO">
                                        Otro
                                    </option>

                                </select>

                            </div>


                            {{-- NOMBRE VÍA --}}

                            <div class="docente-field">

                                <label for="nombreVia">
                                    Nombre de la vía
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nombreVia"
                                    placeholder="Ej. Av. Javier Prado">

                            </div>


                            {{-- TIPO INMUEBLE --}}

                            <div class="docente-field">

                                <label for="selectTipoInmueble">
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

                                    <option value="OFICINA">
                                        Oficina
                                    </option>

                                    <option value="OTRO">
                                        Otro
                                    </option>

                                </select>

                            </div>


                            {{-- NÚMERO --}}

                            <div class="docente-field">

                                <label for="numeroInmueble">
                                    Número de inmueble
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="numeroInmueble"
                                    placeholder="Ej. 123">

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

                            Guardar domicilio

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
        tabindex="-1"
        aria-hidden="true">

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


                            {{-- NIVEL --}}

                            <div class="docente-field">

                                <label for="formacionNivel">
                                    Nivel de formación
                                </label>

                                <select
                                    class="form-select"
                                    id="formacionNivel">

                                    <option value="-">
                                        -- Seleccionar --
                                    </option>

                                    <option value="Técnico">
                                        Técnico
                                    </option>

                                    <option value="Bachiller">
                                        Bachiller
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

                                    <option value="Postdoctorado">
                                        Postdoctorado
                                    </option>

                                </select>

                            </div>


                            {{-- PAÍS --}}

                            <div class="docente-field">

                                <label for="formacionPais">
                                    País
                                </label>

                                <select
                                    class="form-select"
                                    id="formacionPais">

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                    <option value="Perú">
                                        Perú
                                    </option>

                                    <option value="Argentina">
                                        Argentina
                                    </option>

                                    <option value="Brasil">
                                        Brasil
                                    </option>

                                    <option value="Chile">
                                        Chile
                                    </option>

                                    <option value="Colombia">
                                        Colombia
                                    </option>

                                    <option value="España">
                                        España
                                    </option>

                                    <option value="Estados Unidos">
                                        Estados Unidos
                                    </option>

                                    <option value="México">
                                        México
                                    </option>

                                    <option value="Otro">
                                        Otro
                                    </option>

                                </select>

                            </div>


                            {{-- UNIVERSIDAD --}}

                            <div class="docente-field docente-field--wide">

                                <label for="formacionUniversidad">
                                    Universidad / institución
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="formacionUniversidad"
                                    placeholder="Nombre de la universidad o institución">

                            </div>


                            {{-- ESPECIALIDAD --}}

                            <div class="docente-field docente-field--wide">

                                <label for="formacionEspecialidad">
                                    Especialidad
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="formacionEspecialidad"
                                    placeholder="Especialidad o carrera">

                            </div>


                            {{-- FECHA INICIO --}}

                            <div class="docente-field">

                                <label for="formacionFecInicio">
                                    Fecha de inicio
                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="formacionFecInicio">

                            </div>


                            {{-- FECHA FINAL --}}

                            <div class="docente-field">

                                <label for="formacionFecFinal">
                                    Fecha de finalización
                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="formacionFecFinal">

                            </div>


                            {{-- GRADO --}}

                            <div class="docente-field docente-field--wide">

                                <label for="formacionGrado">
                                    Grado obtenido
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="formacionGrado"
                                    placeholder="Ej. Ingeniero de Sistemas">

                            </div>


                            {{-- DOCUMENTO --}}

                            <div class="docente-field docente-field--wide">

                                <label for="formacionDoc">
                                    Documento de sustento
                                </label>

                                <input
                                    type="file"
                                    class="form-control"
                                    id="formacionDoc"
                                    accept=".pdf">

                                <small class="text-muted">
                                    Formato permitido: PDF
                                </small>

                            </div>


                            {{-- OBSERVACIÓN --}}

                            <div class="docente-field docente-field--wide">

                                <label for="formacionObs">
                                    Observación
                                </label>

                                <textarea
                                    class="form-control"
                                    id="formacionObs"
                                    rows="3"
                                    placeholder="Observaciones adicionales"></textarea>

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

                            Guardar formación

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
        tabindex="-1"
        aria-hidden="true">

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


                            {{-- NOMBRE --}}

                            <div class="docente-field docente-field--wide">

                                <label for="familiarNombre">
                                    Nombres y apellidos
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="familiarNombre"
                                    placeholder="Apellidos y nombres">

                            </div>


                            {{-- PARENTESCO --}}

                            <div class="docente-field">

                                <label for="familiarParentesco">
                                    Parentesco
                                </label>

                                <select
                                    class="form-select"
                                    id="familiarParentesco">

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                    <option value="Cónyuge">
                                        Cónyuge
                                    </option>

                                    <option value="Hijo(a)">
                                        Hijo(a)
                                    </option>

                                    <option value="Padre">
                                        Padre
                                    </option>

                                    <option value="Madre">
                                        Madre
                                    </option>

                                    <option value="Hermano(a)">
                                        Hermano(a)
                                    </option>

                                    <option value="Otro">
                                        Otro
                                    </option>

                                </select>

                            </div>


                            {{-- FECHA NACIMIENTO --}}

                            <div class="docente-field">

                                <label for="familiarFecNac">
                                    Fecha de nacimiento
                                </label>

                                <input
                                    type="date"
                                    class="form-control"
                                    id="familiarFecNac">

                            </div>


                            {{-- ESTADO CIVIL --}}

                            <div class="docente-field">

                                <label for="familiarEstadoCivil">
                                    Estado civil
                                </label>

                                <select
                                    class="form-select"
                                    id="familiarEstadoCivil">

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                    <option value="Soltero(a)">
                                        Soltero(a)
                                    </option>

                                    <option value="Casado(a)">
                                        Casado(a)
                                    </option>

                                    <option value="Divorciado(a)">
                                        Divorciado(a)
                                    </option>

                                    <option value="Viudo(a)">
                                        Viudo(a)
                                    </option>

                                </select>

                            </div>


                            {{-- ESTUDIOS --}}

                            <div class="docente-field">

                                <label for="familiarEstudios">
                                    Nivel de estudios
                                </label>

                                <select
                                    class="form-select"
                                    id="familiarEstudios">

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                    <option value="Inicial">
                                        Inicial
                                    </option>

                                    <option value="Primaria">
                                        Primaria
                                    </option>

                                    <option value="Secundaria">
                                        Secundaria
                                    </option>

                                    <option value="Técnico">
                                        Técnico
                                    </option>

                                    <option value="Universitario">
                                        Universitario
                                    </option>

                                    <option value="Maestría">
                                        Maestría
                                    </option>

                                    <option value="Doctorado">
                                        Doctorado
                                    </option>

                                    <option value="Otro">
                                        Otro
                                    </option>

                                </select>

                            </div>


                            {{-- OCUPACIÓN --}}

                            <div class="docente-field">

                                <label for="familiarOcupacion">
                                    Ocupación
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="familiarOcupacion"
                                    placeholder="Ocupación">

                            </div>


                            {{-- CELULAR --}}

                            <div class="docente-field">

                                <label for="familiarCelular">
                                    Celular
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="familiarCelular"
                                    placeholder="Número de celular">

                            </div>


                            {{-- DEPENDENCIA --}}

                            <div class="docente-field">

                                <label for="familiarDepende">
                                    ¿Depende económicamente?
                                </label>

                                <select
                                    class="form-select"
                                    id="familiarDepende">

                                    <option value="No">
                                        No
                                    </option>

                                    <option value="Si">
                                        Sí
                                    </option>

                                </select>

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

                            Guardar familiar

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


 




<meta name="csrf-token" content="{{ csrf_token() }}">


{{-- ============================================================
     RUTAS API
     ============================================================ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
    window.informacionApi = {
        informacion: "{{ url('/api/docente/informacion') }}",
        domicilios: "{{ url('/api/docente/domicilios') }}",
        familiares: "{{ url('/api/docente/familiares') }}",
        formaciones: "{{ url('/api/docente/formaciones') }}"
    };
</script>


{{-- ============================================================
     JAVASCRIPT
     ============================================================ --}}

<script src="{{ asset('js/informacion.js') }}"></script>





</x-app-layout>

