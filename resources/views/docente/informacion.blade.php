<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Información Docente') }}
        </h2>
    </x-slot>

    <style>
        /* Estilos en tonos Guinda y Formales */
        .card-header-custom {
            background: linear-gradient(135deg, #721e3b 0%, #9c2750 100%) !important;
            color: white;
        }
        .card-header-secundary {
            background: linear-gradient(135deg, #601931 0%, #852445 100%) !important;
            color: white;
        }
        .table-custom th {
            background-color: #f8f9fa;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
        }
        .table-custom td {
            font-size: 0.9rem;
        }
        /* Pestañas de navegación superiores */
        .nav-pills-custom .nav-link {
            color: #721e3b;
            font-weight: 600;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            margin-right: 4px;
        }
        .nav-pills-custom .nav-link.active {
            color: #fff;
            background-color: #721e3b;
            border-color: #721e3b;
        }
        .btn-uni-guinda {
            background-color: #721e3b;
            color: white;
            border: none;
        }
        .btn-uni-guinda:hover {
            background-color: #5a172e;
            color: white;
        }
    </style>

    <!-- INICIO DEL CONTENEDOR REPORTE -->
    <div class="container py-4" id="contenidoReporte">
        
        <!-- PESTAÑAS DE NAVEGACIÓN (1, 2, 3 y 4) -->
        <ul class="nav nav-pills nav-pills-custom mb-3" id="pagoTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-4 py-2 shadow-sm" id="pag-1-tab" data-bs-toggle="pill" data-bs-target="#pag-1" type="button" role="tab">1. Datos Personales</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-2 shadow-sm" id="pag-2-tab" data-bs-toggle="pill" data-bs-target="#pag-2" type="button" role="tab">2. Domicilio</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-2 shadow-sm" id="pag-3-tab" data-bs-toggle="pill" data-bs-target="#pag-3" type="button" role="tab">3. Formación Profesional</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-2 shadow-sm" id="pag-4-tab" data-bs-toggle="pill" data-bs-target="#pag-4" type="button" role="tab">4. Adicionales</button>
            </li>
        </ul>

        <!-- CABECERA DEL DOCENTE -->
        <div class="card mb-4 shadow-sm border-0 bg-white rounded-3">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle border border-2 shadow-sm d-flex align-items-center justify-content-center bg-light" style="width: 80px; height: 80px; border-color: #721e3b !important;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="#721e3b" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">OBREGON RAMOS, MAXIMO</h4>
                        <span class="badge" style="background-color: #721e3b;">Código: 08161078</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn fw-bold rounded-pill px-3 shadow-sm" style="color: #721e3b; border-color: #721e3b;" id="btnGenerarPDF">
                        <i class="fas fa-file-pdf me-1"></i> Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- CONTENIDO DE LAS PÁGINAS -->
        <div class="tab-content" id="pagoTabContent">
            
            <!-- PÁGINA 1: DATOS PERSONALES -->
            <div class="tab-pane fade show active" id="pag-1" role="tabpanel">
                <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header card-header-custom d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-id-card me-2"></i>1.- DATOS PERSONALES</h5>
                    </div>
                    <div class="card-body bg-white">
                        <form id="formDatosPersonales">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Sexo</label>
                                    <select id="select-sexo" class="form-select persistir-input">
                                        <option value="Masculino" selected>Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Fecha Nacimiento</label>
                                    <input type="date" id="input-fec-nac" class="form-control persistir-input" value="1975-02-16">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">DNI <i class="fas fa-check-circle text-success"></i></label>
                                    <input type="text" id="input-dni" class="form-control bg-light" value="08161078" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Teléfono Fijo</label>
                                    <input type="text" id="input-tel-fijo" class="form-control persistir-input" placeholder="5316292">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Celular</label>
                                    <input type="text" id="input-celular" class="form-control persistir-input" placeholder="951629294">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">RUC</label>
                                    <input type="text" id="input-ruc" class="form-control persistir-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Lib. Militar</label>
                                    <input type="text" id="input-lib-militar" class="form-control persistir-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Brevete y Categoría</label>
                                    <input type="text" id="input-brevete" class="form-control persistir-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Vehículo (Modelo, Placa, Año, Color)</label>
                                    <input type="text" id="input-vehiculo" class="form-control persistir-input">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold small text-muted">Estado Civil</label>
                                    <select id="select-estado-civil" class="form-select persistir-input">
                                        <option value="Soltero">Soltero(a)</option>
                                        <option value="Casado" selected>Casado(a)</option>
                                        <option value="Divorciado">Divorciado(a)</option>
                                    </select>
                                </div>
                                <div class="col-md-2" id="contenedor-fecha-matrimonio">
                                    <label class="form-label fw-bold small text-muted">Fecha Matrimonio</label>
                                    <input type="date" id="input-fec-matrimonio" class="form-control persistir-input" value="2005-04-01">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold small text-muted">Grupo Sanguíneo</label>
                                    <input type="text" id="input-grupo-sanguineo" class="form-control persistir-input" value="O+">
                                </div>
                            </div>

                            <h6 class="mt-4 mb-3 border-bottom pb-2 fw-bold" style="color: #721e3b;"><i class="fas fa-globe-americas me-2"></i>Lugar de Nacimiento</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="select-pais" class="form-label fw-bold small text-muted">País</label>
                                    <select id="select-pais" class="form-select persistir-input">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="PE">Perú</option>
                                        <option value="OTRO">Otro País</option>
                                    </select>
                                </div>
                                <div id="campo-depa" class="col-md-3 d-none">
                                    <label for="select-depa" class="form-label fw-bold small text-muted">Departamento</label>
                                    <select id="select-depa" class="form-select persistir-input"><option value="">-- Seleccionar --</option></select>
                                </div>
                                <div id="campo-prov" class="col-md-3 d-none">
                                    <label for="select-prov" class="form-label fw-bold small text-muted">Provincia</label>
                                    <select id="select-prov" class="form-select persistir-input" disabled><option value="">-- Seleccionar --</option></select>
                                </div>
                                <div id="campo-dist" class="col-md-3 d-none">
                                    <label for="select-dist" class="form-label fw-bold small text-muted">Distrito</label>
                                    <select id="select-dist" class="form-select persistir-input" disabled><option value="">-- Seleccionar --</option></select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-uni-guinda px-4 rounded-pill shadow-sm" id="btnGuardarDatosPersonales">
                                    <i class="fas fa-save me-1"></i> Guardar Datos Personales
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PÁGINA 2: DOMICILIO -->
            <div class="tab-pane fade" id="pag-2" role="tabpanel">
                <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header card-header-custom d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-home me-2"></i>2.- REGISTRO DE DOMICILIO</h5>
                        <button class="btn btn-light btn-sm fw-bold shadow-sm rounded-pill" style="color: #721e3b;" data-bs-toggle="modal" data-bs-target="#modalDomicilio">
                            <i class="fas fa-plus-circle me-1"></i> AÑADIR DOMICILIO
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0 text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th><th>País</th><th>Dirección Ext.</th><th>Departamento</th><th>Provincia</th><th>Distrito</th><th>Tipo Vía</th><th>Nombre Vía</th><th>Tipo Inm.</th><th>N° Inmueble</th><th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaDomicilio"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PÁGINA 3: FORMACIÓN PROFESIONAL -->
            <div class="tab-pane fade" id="pag-3" role="tabpanel">
                <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header card-header-custom d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-graduation-cap me-2"></i>3.- FORMACIÓN PROFESIONAL</h5>
                        <button class="btn btn-light btn-sm fw-bold shadow-sm rounded-pill" style="color: #721e3b;" data-bs-toggle="modal" data-bs-target="#modalFormacion">
                            <i class="fas fa-plus-circle me-1"></i> AÑADIR FORMACIÓN
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0 align-middle">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th><th>Formación</th><th>País</th><th>Universidad</th><th>Especialidad</th><th>Inicio</th><th>Fin</th><th>Grado</th><th>Doc.</th><th>Obs.</th><th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaFormacion"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PÁGINA 4: OTROS DATOS / ADICIONALES -->
            <div class="tab-pane fade" id="pag-4" role="tabpanel">
                <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header card-header-custom d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-folder-open me-2"></i>4.- INFORMACIÓN ADICIONAL Y CARGA FAMILIAR</h5>
                        <button class="btn btn-light btn-sm fw-bold shadow-sm rounded-pill" style="color: #721e3b;" data-bs-toggle="modal" data-bs-target="#modalFamiliar">
                            <i class="fas fa-plus-circle me-1"></i> AÑADIR FAMILIAR
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0 text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>Apellidos y Nombres</th><th>Parentesco</th><th>Fec. Nac.</th><th>Est. Civil</th><th>Estudios</th><th>Ocupación</th><th>Celular</th><th>Depende</th><th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaFamiliar"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL DOMICILIO -->
    <div class="modal fade" id="modalDomicilio" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #721e3b;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-map-marked-alt me-2"></i>Agregar Registro de Domicilio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formDomicilio">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">País</label>
                                <select class="form-select" id="selectPaisDom">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="PE">Perú</option>
                                    <option value="EXT">Extranjero</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Departamento</label>
                                <select class="form-select" id="selectDepartamentoDom" disabled><option value="">-- Seleccionar --</option></select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Provincia</label>
                                <select class="form-select" id="selectProvinciaDom" disabled><option value="">-- Seleccionar --</option></select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Distrito</label>
                                <select class="form-select" id="selectDistritoDom" disabled><option value="">-- Seleccionar --</option></select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tipo de Vía</label>
                                <select class="form-select" id="selectTipoVia">
                                    <option value="-">-- Seleccionar --</option>
                                    <option value="AV">Avenida</option>
                                    <option value="JR">Jirón</option>
                                    <option value="CALLE">Calle</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nombre de la Vía</label>
                                <input type="text" class="form-control" id="nombreVia">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tipo Inmueble</label>
                                <select class="form-select" id="selectTipoInmueble">
                                    <option value="-">-- Seleccionar --</option>
                                    <option value="CASA">Casa</option>
                                    <option value="DEP">Departamento</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Número de Inmueble</label>
                                <input type="text" class="form-control" id="numeroInmueble">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn fw-bold text-white" style="background-color: #721e3b;" id="btnGuardarDomicilio"><i class="fas fa-save me-1"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL FORMACIÓN -->
    <div class="modal fade" id="modalFormacion" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #721e3b;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-graduate me-2"></i>Agregar Formación Profesional</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formFormacion">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-bold small">Nivel</label><select class="form-select" id="formacionNivel"><option value="-">Seleccionar</option><option value="Licenciatura">Licenciatura</option><option value="Maestría">Maestría</option></select></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Universidad</label><input type="text" class="form-control" id="formacionUniversidad"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Especialidad</label><input type="text" class="form-control" id="formacionEspecialidad"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Grado Obtenido</label><input type="text" class="form-control" id="formacionGrado"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn fw-bold text-white" style="background-color: #721e3b;" id="btnGuardarFormacion"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FAMILIAR -->
    <div class="modal fade" id="modalFamiliar" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #721e3b;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-users me-2"></i>Agregar Familiar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formFamiliar">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-bold small">Nombres y Apellidos</label><input type="text" class="form-control" id="familiarNombre"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Parentesco</label><input type="text" class="form-control" id="familiarParentesco"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn fw-bold text-white" style="background-color: #721e3b;" id="btnGuardarFamiliar"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/informacion.js') }}"></script>
</x-app-layout>