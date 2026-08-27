<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Información Docente') }}
        </h2>
    </x-slot>

    <style>
        .card-header-custom {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }
        .card-header-secundary {
            background: linear-gradient(135deg, #3a6073 0%, #3a7bd5 100%);
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
        .empty-row {
            color: #888;
            font-style: italic;
            text-align: center;
        }
    </style>

    <!-- INICIO DEL CONTENEDOR REPORTE -->
    <div class="container py-4" id="contenidoReporte">
        <!-- CABECERA DEL DOCENTE -->
        <div class="card mb-4 shadow-sm border-0 bg-white rounded-3">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <!-- Avatar SVG en código para evitar fallos de renderizado en html2pdf -->
                    <div class="rounded-circle border border-2 border-primary shadow-sm d-flex align-items-center justify-content-center bg-light" style="width: 80px; height: 80px;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="#1e3c72" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">OBREGON RAMOS, MAXIMO</h4>
                        <span class="badge bg-secondary">Código: 08161078</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-danger fw-bold rounded-pill px-3 shadow-sm" id="btnGenerarPDF">
                        <i class="fas fa-file-pdf me-1"></i> Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- 1.- DATOS PERSONALES -->
        <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-user-tie me-2"></i>1.- DATOS PERSONALES</h5>
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
                            <input type="text" id="input-tel-fijo" class="form-control persistir-input" placeholder="5316292 | 5456454">
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

                    <!-- LUGAR DE NACIMIENTO -->
                    <h6 class="mt-4 mb-3 text-primary border-bottom pb-2 fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Lugar de Nacimiento</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="select-pais" class="form-label fw-bold small text-muted">País</label>
                            <select id="select-pais" class="form-select persistir-input">
                                <option value="">-- Seleccionar --</option>
                                <option value="PE">Perú</option>
                                <option value="ARG">Argentina</option>
                                <option value="BRA">Brasil</option>
                                <option value="CHL">Chile</option>
                                <option value="COL">Colombia</option>
                                <option value="ESP">España</option>
                                <option value="USA">Estados Unidos</option>
                                <option value="OTRO">Otro País</option>
                            </select>
                        </div>

                        <div id="campo-depa" class="col-md-3 d-none">
                            <label for="select-depa" class="form-label fw-bold small text-muted">Departamento</label>
                            <select id="select-depa" class="form-select persistir-input">
                                <option value="">-- Seleccionar --</option>
                            </select>
                        </div>

                        <div id="campo-prov" class="col-md-3 d-none">
                            <label for="select-prov" class="form-label fw-bold small text-muted">Provincia</label>
                            <select id="select-prov" class="form-select persistir-input" disabled>
                                <option value="">-- Seleccionar --</option>
                            </select>
                        </div>

                        <div id="campo-dist" class="col-md-3 d-none">
                            <label for="select-dist" class="form-label fw-bold small text-muted">Distrito</label>
                            <select id="select-dist" class="form-select persistir-input" disabled>
                                <option value="">-- Seleccionar --</option>
                            </select>
                        </div>

                        <div id="contenedor-extranjero" class="col-md-9 d-none">
                            <label for="input-direccion-ext" class="form-label fw-bold small text-muted">Dirección / Estado / Ciudad Extranjera</label>
                            <input type="text" id="input-direccion-ext" class="form-control persistir-input" placeholder="Ingrese ciudad/estado del extranjero">
                        </div>
                    </div>

                    <!-- BOTÓN CONFIRMAR Y GUARDAR DATOS PERSONALES -->
                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-uni px-4 rounded-pill shadow-sm" id="btnGuardarDatosPersonales">
                            <i class="fas fa-check-circle me-1"></i> Confirmar y Guardar Datos
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2.- DOMICILIO -->
        <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header card-header-secundary d-flex justify-content-between align-items-center py-2">
                <h5 class="mb-0 fw-bold"><i class="fas fa-home me-2"></i>Domicilio</h5>
                <button class="btn btn-light btn-sm text-primary fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalDomicilio">
                    <i class="fas fa-plus-circle me-1"></i> AÑADIR REGISTRO
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

        <!-- DATOS FAMILIARES -->
        <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header card-header-secundary d-flex justify-content-between align-items-center py-2">
                <h5 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Carga Familiar</h5>
                <button class="btn btn-light btn-sm text-primary fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalFamiliar">
                    <i class="fas fa-plus-circle me-1"></i> AÑADIR REGISTRO
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

        <!-- 3.- DATOS DE FORMACIÓN PROFESIONAL -->
        <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header card-header-secundary d-flex justify-content-between align-items-center py-2">
                <h5 class="mb-0 fw-bold"><i class="fas fa-graduation-cap me-2"></i>3.- DATOS DE FORMACIÓN PROFESIONAL</h5>
                <button class="btn btn-light btn-sm text-primary fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalFormacion">
                    <i class="fas fa-plus-circle me-1"></i> AÑADIR REGISTRO
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

    <!-- MODAL DOMICILIO -->
    <div class="modal fade" id="modalDomicilio" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-map-marked-alt me-2"></i>Agregar Domicilio</h5>
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
                                <select class="form-select" id="selectDepartamentoDom" disabled>
                                    <option value="">-- Seleccionar --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Provincia</label>
                                <select class="form-select" id="selectProvinciaDom" disabled>
                                    <option value="">-- Seleccionar --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Distrito</label>
                                <select class="form-select" id="selectDistritoDom" disabled>
                                    <option value="">-- Seleccionar --</option>
                                </select>
                            </div>
                            <div class="col-md-12 d-none" id="contenedorDirExtDom">
                                <label class="form-label fw-bold small">Dirección Extranjera</label>
                                <input type="text" class="form-control" id="inputDirExtDom" placeholder="Escriba la dirección en el extranjero">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tipo de Vía</label>
                                <select class="form-select" id="selectTipoVia">
                                    <option value="-">-- Seleccionar --</option>
                                    <option value="AV">Avenida</option>
                                    <option value="JR">Jirón</option>
                                    <option value="CALLE">Calle</option>
                                    <option value="PSJ">Pasaje</option>
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
                                    <option value="INT">Interior</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Número de Inmueble</label>
                                <input type="text" class="form-control" id="numeroInmueble">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="button" class="btn btn-primary fw-bold" id="btnGuardarDomicilio"><i class="fas fa-save me-1"></i> GUARDAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL FAMILIAR -->
    <div class="modal fade" id="modalFamiliar" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i>Agregar Familiar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formFamiliar">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-bold small">Apellidos y Nombres</label><input type="text" class="form-control" id="familiarNombre" required></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Parentesco</label><select class="form-select" id="familiarParentesco"><option value="-">Seleccionar</option><option value="Padre">Padre</option><option value="Madre">Madre</option><option value="Hermano(a)">Hermano(a)</option><option value="Hijo(a)">Hijo(a)</option><option value="Cónyuge">Cónyuge</option></select></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Fecha Nacimiento</label><input type="date" class="form-control" id="familiarFecNac"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Estado Civil</label><select class="form-select" id="familiarEstCivil"><option value="-">Seleccionar</option><option value="Soltero(a)">Soltero(a)</option><option value="Casado(a)">Casado(a)</option><option value="Viudo(a)">Viudo(a)</option><option value="Divorciado(a)">Divorciado(a)</option></select></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Nivel Estudios</label><select class="form-select" id="familiarEstudios"><option value="-">Seleccionar</option><option value="Primaria">Primaria</option><option value="Secundaria">Secundaria</option><option value="Técnico">Técnico</option><option value="Universitario">Universitario</option></select></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Ocupación</label><input type="text" class="form-control" id="familiarOcupacion"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Celular</label><input type="text" class="form-control" id="familiarCelular"></div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small d-block">¿Depende económicamente?</label>
                                <div class="form-check form-check-inline mt-1">
                                    <input class="form-check-input" type="radio" name="depende" id="depSi" value="Si">
                                    <label class="form-check-label" for="depSi">Sí</label>
                                </div>
                                <div class="form-check form-check-inline mt-1">
                                    <input class="form-check-input" type="radio" name="depende" id="depNo" value="No" checked>
                                    <label class="form-check-label" for="depNo">No</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="button" class="btn btn-primary fw-bold" id="btnGuardarFamiliar"><i class="fas fa-save me-1"></i> GUARDAR</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FORMACIÓN -->
    <div class="modal fade" id="modalFormacion" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-graduate me-2"></i>Agregar Formación Profesional</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formFormacion">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-bold small">Formación Profesional</label><select class="form-select" id="formacionNivel"><option value="-">Seleccionar</option><option value="Licenciatura">Licenciatura</option><option value="Maestría">Maestría</option><option value="Doctorado">Doctorado</option></select></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">País</label><select class="form-select" id="formacionPais"><option value="-">Seleccionar</option><option value="Perú">Perú</option><option value="Extranjero">Extranjero</option></select></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Universidad</label><input type="text" class="form-control" id="formacionUniversidad"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Especialidad</label><input type="text" class="form-control" id="formacionEspecialidad"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Fecha Inicio</label><input type="date" class="form-control" id="formacionFecInicio"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Fecha Final</label><input type="date" class="form-control" id="formacionFecFinal"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Grado Obtenido</label><input type="text" class="form-control" id="formacionGrado"></div>
                            <div class="col-md-6"><label class="form-label fw-bold small">Documento Sustentatorio</label><input type="file" class="form-control" id="formacionDoc"></div>
                            <div class="col-md-12"><label class="form-label fw-bold small">Observación</label><textarea class="form-control" rows="2" id="formacionObs"></textarea></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="button" class="btn btn-primary fw-bold" id="btnGuardarFormacion"><i class="fas fa-save me-1"></i> GUARDAR</button>
                </div>
            </div>
        </div>
    </div>

<!-- REEMPLAZA EL BLOQUE DE SCRIPT ANTERIOR POR ESTE CÓDIGO ACTUALIZADO -->
@verbatim
<script>
    const ubigeoData = {
        "Amazonas": {
            "Chachapoyas": ["Chachapoyas", "Asunción", "Balsas", "Cheto", "Chiliquín", "Chuquibamba", "Gran Granada", "Huancas", "La Jalca", "Leimebamba", "Levanto", "Magdalena", "Mariscal Castilla", "Molinopampa", "Montevideo", "Olleros", "Quinjalca", "San Francisco de Daguas", "San Isidro de Maino", "Soloco", "Sonche"],
            "Bagua": ["Bagua", "Aramango", "Copallín", "El Parco", "Imaza", "La Peca"],
            "Bongará": ["Jumbilla", "Churuja", "Florida", "Jazán", "Recta", "San Carlos", "Shipasbamba", "Valera", "Yambrasbamba"],
            "Condorcanqui": ["Nieva", "El Cenepa", "Río Santiago"],
            "Luya": ["Lamud", "Camporredondo", "Cocabamba", "Colcamar", "Conila", "Inguilpata", "Lónguita", "Lonya Chico", "Luya", "Luya Viejo", "María", "Ocalli", "Ocumal", "Pisuquia", "Providencia", "San Cristóbal", "San Francisco del Yeso", "San Jerónimo", "San Juan de Lopecancha", "Santa Catalina", "Santo Tomás", "Tingo", "Trita"],
            "Rodríguez de Mendoza": ["San Nicolás", "Chirimoto", "Cochamal", "Huambo", "Limabamba", "Longar", "Mariscal Benavides", "Milpuc", "Omia", "Santa Rosa", "Totora", "Vista Alegre"],
            "Utcubamba": ["Bagua Grande", "Cajaruro", "Cumba", "El Milagro", "Jamalca", "Lonya Grande", "Yamubrasbamba"]
        },
        "Lima": {
            "Lima": ["Lima", "Ancón", "Ate", "Barranco", "Breña", "Carabayllo", "Chaclacayo", "Chorrillos", "Cieneguilla", "Comas", "El Agustino", "Independencia", "Jesús María", "La Molina", "La Victoria", "Lince", "Los Olivos", "Lurigancho", "Lurín", "Magdalena del Mar", "Miraflores", "Pachacámac", "Pucusana", "Pueblo Libre", "Puente Piedra", "Punta Hermosa", "Punta Negra", "Rímac", "San Bartolo", "San Borja", "San Isidro", "San Juan de Lurigancho", "San Juan de Miraflores", "San Luis", "San Martín de Porres", "San Miguel", "Santa Anita", "Santa María del Mar", "Santa Rosa", "Santiago de Surco", "Surquillo", "Villa El Salvador", "Villa María del Triunfo"]
        }
    };

    let dataDomicilio = JSON.parse(localStorage.getItem('docente_domicilio')) || [];
    let dataFamiliar = JSON.parse(localStorage.getItem('docente_familiar')) || [];
    let dataFormacion = JSON.parse(localStorage.getItem('docente_formacion')) || [];

    document.addEventListener('DOMContentLoaded', function() {
        initPersistenciaInputs();
        initUbigeoPrincipal();
        initUbigeoModalDom();
        
        renderTablaDomicilio();
        renderTablaFamiliar();
        renderTablaFormacion();
        initBotonPDF();

        // PERSISTENCIA Y GUARDADO DE DATOS PERSONALES
        function initPersistenciaInputs() {
            document.querySelectorAll('.persistir-input').forEach(element => {
                const key = 'docente_input_' + element.id;
                const savedValue = localStorage.getItem(key);
                
                if (savedValue !== null) {
                    element.value = savedValue;
                }

                element.addEventListener('change', function() {
                    localStorage.setItem(key, this.value);
                });
                element.addEventListener('input', function() {
                    localStorage.setItem(key, this.value);
                });
            });

            // Restauración secuencial de los desplegables de Ubigeo Nacimiento
            setTimeout(() => {
                const selectPais = document.getElementById('select-pais');
                const selectDep = document.getElementById('select-depa');
                const selectProv = document.getElementById('select-prov');
                const selectDist = document.getElementById('select-dist');

                if (selectPais) selectPais.dispatchEvent(new Event('change'));
                
                const savedDep = localStorage.getItem('docente_input_select-depa');
                if (savedDep && selectDep) {
                    selectDep.value = savedDep;
                    selectDep.dispatchEvent(new Event('change'));
                }

                const savedProv = localStorage.getItem('docente_input_select-prov');
                if (savedProv && selectProv) {
                    selectProv.value = savedProv;
                    selectProv.dispatchEvent(new Event('change'));
                }

                const savedDist = localStorage.getItem('docente_input_select-dist');
                if (savedDist && selectDist) {
                    selectDist.value = savedDist;
                }
            }, 100);

            // Acción del Botón Confirmar Datos
            const btnGuardarDatos = document.getElementById('btnGuardarDatosPersonales');
            if (btnGuardarDatos) {
                btnGuardarDatos.addEventListener('click', function() {
                    document.querySelectorAll('.persistir-input').forEach(element => {
                        const key = 'docente_input_' + element.id;
                        localStorage.setItem(key, element.value);
                    });

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Datos Guardados!',
                            text: 'La información personal se ha guardado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('¡Información personal guardada con éxito!');
                    }
                });
            }

            const selectEstadoCivil = document.getElementById('select-estado-civil');
            const contenedorFechaMatrimonio = document.getElementById('contenedor-fecha-matrimonio');
            if (selectEstadoCivil && contenedorFechaMatrimonio) {
                const toggleMatrimonio = () => {
                    if (selectEstadoCivil.value === 'Casado') {
                        contenedorFechaMatrimonio.classList.remove('d-none');
                    } else {
                        contenedorFechaMatrimonio.classList.add('d-none');
                    }
                };
                selectEstadoCivil.addEventListener('change', toggleMatrimonio);
                toggleMatrimonio();
            }
        }

        // UBIGEO LUGAR NACIMIENTO
        function initUbigeoPrincipal() {
            const selectPais = document.getElementById('select-pais');
            const selectDep = document.getElementById('select-depa');
            const selectProv = document.getElementById('select-prov');
            const selectDist = document.getElementById('select-dist');

            const campoDepa = document.getElementById('campo-depa');
            const campoProv = document.getElementById('campo-prov');
            const campoDist = document.getElementById('campo-dist');
            const contenedorExtranjero = document.getElementById('contenedor-extranjero');

            function resetSelect(selectElement) {
                if (selectElement) selectElement.innerHTML = '<option value="">-- Seleccionar --</option>';
            }

            function cargarDepartamentos() {
                if (!selectDep) return;
                resetSelect(selectDep);
                Object.keys(ubigeoData).sort().forEach(dep => {
                    let opt = document.createElement('option');
                    opt.value = dep;
                    opt.textContent = dep;
                    selectDep.appendChild(opt);
                });
            }

            function manejarCambioPais() {
                if (!selectPais) return;
                const val = selectPais.value.trim().toUpperCase();
                const esPeru = ['PE', 'PERU', 'PERÚ'].includes(val);

                if (esPeru) {
                    campoDepa.classList.remove('d-none');
                    campoProv.classList.remove('d-none');
                    campoDist.classList.remove('d-none');
                    contenedorExtranjero.classList.add('d-none');
                    if (selectDep && selectDep.options.length <= 1) cargarDepartamentos();
                } else if (val !== '') {
                    campoDepa.classList.add('d-none');
                    campoProv.classList.add('d-none');
                    campoDist.classList.add('d-none');
                    contenedorExtranjero.classList.remove('d-none');
                } else {
                    campoDepa.classList.add('d-none');
                    campoProv.classList.add('d-none');
                    campoDist.classList.add('d-none');
                    contenedorExtranjero.classList.add('d-none');
                }
            }

            if (selectPais) {
                selectPais.addEventListener('change', manejarCambioPais);
                manejarCambioPais();
            }

            if (selectDep) {
                selectDep.addEventListener('change', function() {
                    resetSelect(selectProv);
                    resetSelect(selectDist);
                    if (this.value && ubigeoData[this.value]) {
                        selectProv.disabled = false;
                        Object.keys(ubigeoData[this.value]).sort().forEach(p => {
                            let opt = document.createElement('option');
                            opt.value = p; opt.textContent = p;
                            selectProv.appendChild(opt);
                        });
                    } else {
                        selectProv.disabled = true;
                        selectDist.disabled = true;
                    }
                });
            }

            if (selectProv) {
                selectProv.addEventListener('change', function() {
                    resetSelect(selectDist);
                    const dep = selectDep.value;
                    if (dep && this.value && ubigeoData[dep][this.value]) {
                        selectDist.disabled = false;
                        ubigeoData[dep][this.value].sort().forEach(d => {
                            let opt = document.createElement('option');
                            opt.value = d; opt.textContent = d;
                            selectDist.appendChild(opt);
                        });
                    } else {
                        selectDist.disabled = true;
                    }
                });
            }
        }

        // UBIGEO MODAL DOMICILIO
        function initUbigeoModalDom() {
            const selectPais = document.getElementById('selectPaisDom');
            const selectDep = document.getElementById('selectDepartamentoDom');
            const selectProv = document.getElementById('selectProvinciaDom');
            const selectDist = document.getElementById('selectDistritoDom');
            const contenedorExt = document.getElementById('contenedorDirExtDom');

            function resetSelect(sel, disable = true) {
                if (sel) {
                    sel.innerHTML = '<option value="">-- Seleccionar --</option>';
                    sel.disabled = disable;
                }
            }

            if (selectPais) {
                selectPais.addEventListener('change', function() {
                    if (this.value === 'PE') {
                        resetSelect(selectDep, false);
                        resetSelect(selectProv, true);
                        resetSelect(selectDist, true);
                        contenedorExt.classList.add('d-none');
                        Object.keys(ubigeoData).sort().forEach(dep => {
                            let opt = document.createElement('option');
                            opt.value = dep; opt.textContent = dep;
                            selectDep.appendChild(opt);
                        });
                    } else if (this.value === 'EXT') {
                        resetSelect(selectDep, true);
                        resetSelect(selectProv, true);
                        resetSelect(selectDist, true);
                        contenedorExt.classList.remove('d-none');
                    } else {
                        resetSelect(selectDep, true);
                        resetSelect(selectProv, true);
                        resetSelect(selectDist, true);
                        contenedorExt.classList.add('d-none');
                    }
                });
            }

            if (selectDep) {
                selectDep.addEventListener('change', function() {
                    resetSelect(selectProv, true);
                    resetSelect(selectDist, true);
                    if (this.value && ubigeoData[this.value]) {
                        selectProv.disabled = false;
                        Object.keys(ubigeoData[this.value]).sort().forEach(p => {
                            let opt = document.createElement('option');
                            opt.value = p; opt.textContent = p;
                            selectProv.appendChild(opt);
                        });
                    }
                });
            }

            if (selectProv) {
                selectProv.addEventListener('change', function() {
                    resetSelect(selectDist, true);
                    const dep = selectDep.value;
                    if (dep && this.value && ubigeoData[dep][this.value]) {
                        selectDist.disabled = false;
                        ubigeoData[dep][this.value].sort().forEach(d => {
                            let opt = document.createElement('option');
                            opt.value = d; opt.textContent = d;
                            selectDist.appendChild(opt);
                        });
                    }
                });
            }
        }

        // TABLA DOMICILIO
        document.getElementById('btnGuardarDomicilio').addEventListener('click', function() {
            const paisSelect = document.getElementById('selectPaisDom');
            const paisText = paisSelect.options[paisSelect.selectedIndex]?.text || '-';
            const dep = document.getElementById('selectDepartamentoDom').value || '-';
            const prov = document.getElementById('selectProvinciaDom').value || '-';
            const dist = document.getElementById('selectDistritoDom').value || '-';
            const dirExt = document.getElementById('inputDirExtDom').value.trim() || '-';
            const tipoVia = document.getElementById('selectTipoVia').value || '-';
            const nombreVia = document.getElementById('nombreVia').value.trim() || '-';
            const tipoInmueble = document.getElementById('selectTipoInmueble').value || '-';
            const numeroInmueble = document.getElementById('numeroInmueble').value.trim() || '-';

            if (!paisSelect.value) {
                alert('Seleccione un país.');
                return;
            }

            const nuevoDomicilio = {
                id: Date.now(),
                pais: paisText,
                dirExt: paisSelect.value === 'EXT' ? dirExt : '-',
                dep: paisSelect.value === 'PE' ? dep : '-',
                prov: paisSelect.value === 'PE' ? prov : '-',
                dist: paisSelect.value === 'PE' ? dist : '-',
                tipoVia: tipoVia,
                nombreVia: nombreVia,
                tipoInmueble: tipoInmueble,
                numeroInmueble: numeroInmueble
            };

            dataDomicilio.push(nuevoDomicilio);
            localStorage.setItem('docente_domicilio', JSON.stringify(dataDomicilio));
            renderTablaDomicilio();

            document.getElementById('formDomicilio').reset();
            bootstrap.Modal.getInstance(document.getElementById('modalDomicilio')).hide();
        });

        function renderTablaDomicilio() {
            const tbody = document.getElementById('tablaDomicilio');
            tbody.innerHTML = '';

            if (dataDomicilio.length === 0) {
                tbody.innerHTML = `<tr><td colspan="11" class="empty-row py-3">No hay domicilios registrados</td></tr>`;
                return;
            }

            dataDomicilio.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="fw-bold">${index + 1}</td>
                    <td>${item.pais}</td>
                    <td>${item.dirExt}</td>
                    <td>${item.dep}</td>
                    <td>${item.prov}</td>
                    <td>${item.dist}</td>
                    <td>${item.tipoVia}</td>
                    <td>${item.nombreVia}</td>
                    <td>${item.tipoInmueble}</td>
                    <td>${item.numeroInmueble}</td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="eliminarDomicilio(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        window.eliminarDomicilio = function(id) {
            dataDomicilio = dataDomicilio.filter(item => item.id !== id);
            localStorage.setItem('docente_domicilio', JSON.stringify(dataDomicilio));
            renderTablaDomicilio();
        };

        // TABLA FAMILIAR
        document.getElementById('btnGuardarFamiliar').addEventListener('click', function() {
            const nombre = document.getElementById('familiarNombre').value.trim();
            const parentesco = document.getElementById('familiarParentesco').value;
            const fecNac = document.getElementById('familiarFecNac').value || '-';
            const estCivil = document.getElementById('familiarEstCivil').value || '-';
            const estudios = document.getElementById('familiarEstudios').value || '-';
            const ocupacion = document.getElementById('familiarOcupacion').value.trim() || '-';
            const celular = document.getElementById('familiarCelular').value.trim() || '-';
            const depende = document.querySelector('input[name="depende"]:checked')?.value || 'No';

            if (!nombre) {
                alert('El nombre del familiar es obligatorio.');
                return;
            }

            const nuevoFamiliar = {
                id: Date.now(),
                nombre: nombre,
                parentesco: parentesco,
                fecNac: fecNac,
                estCivil: estCivil,
                estudios: estudios,
                ocupacion: ocupacion,
                celular: celular,
                depende: depende
            };

            dataFamiliar.push(nuevoFamiliar);
            localStorage.setItem('docente_familiar', JSON.stringify(dataFamiliar));
            renderTablaFamiliar();

            document.getElementById('formFamiliar').reset();
            bootstrap.Modal.getInstance(document.getElementById('modalFamiliar')).hide();
        });

        function renderTablaFamiliar() {
            const tbody = document.getElementById('tablaFamiliar');
            tbody.innerHTML = '';

            if (dataFamiliar.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="empty-row py-3">No hay carga familiar registrada</td></tr>`;
                return;
            }

            dataFamiliar.forEach((item) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-start fw-bold">${item.nombre}</td>
                    <td>${item.parentesco}</td>
                    <td>${item.fecNac}</td>
                    <td>${item.estCivil}</td>
                    <td>${item.estudios}</td>
                    <td>${item.ocupacion}</td>
                    <td>${item.celular}</td>
                    <td><span class="badge ${item.depende === 'Si' ? 'bg-success' : 'bg-secondary'}">${item.depende}</span></td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="eliminarFamiliar(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        window.eliminarFamiliar = function(id) {
            dataFamiliar = dataFamiliar.filter(item => item.id !== id);
            localStorage.setItem('docente_familiar', JSON.stringify(dataFamiliar));
            renderTablaFamiliar();
        };

        // TABLA FORMACIÓN (Soporte Base64 + Vista Previa)
        document.getElementById('btnGuardarFormacion').addEventListener('click', function() {
            const nivel = document.getElementById('formacionNivel').value;
            const paisSelect = document.getElementById('formacionPais');
            const paisText = paisSelect.options[paisSelect.selectedIndex]?.text || '-';
            const universidad = document.getElementById('formacionUniversidad').value.trim() || '-';
            const especialidad = document.getElementById('formacionEspecialidad').value.trim() || '-';
            const fecInicio = document.getElementById('formacionFecInicio').value || '-';
            const fecFinal = document.getElementById('formacionFecFinal').value || '-';
            const grado = document.getElementById('formacionGrado').value.trim() || '-';
            const obs = document.getElementById('formacionObs').value.trim() || '-';
            const fileInput = document.getElementById('formacionDoc');

            if (nivel === '-') {
                alert('Seleccione el nivel de formación.');
                return;
            }

            const procesarGuardado = (pdfBase64 = '') => {
                const nuevaFormacion = {
                    id: Date.now(),
                    nivel: nivel,
                    pais: paisText,
                    universidad: universidad,
                    especialidad: especialidad,
                    fecInicio: fecInicio,
                    fecFinal: fecFinal,
                    grado: grado,
                    tieneDoc: pdfBase64 !== '',
                    docBase64: pdfBase64,
                    obs: obs
                };

                dataFormacion.push(nuevaFormacion);
                localStorage.setItem('docente_formacion', JSON.stringify(dataFormacion));
                renderTablaFormacion();

                document.getElementById('formFormacion').reset();
                bootstrap.Modal.getInstance(document.getElementById('modalFormacion')).hide();
            };

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    procesarGuardado(e.target.result);
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                procesarGuardado('');
            }
        });

        function renderTablaFormacion() {
            const tbody = document.getElementById('tablaFormacion');
            tbody.innerHTML = '';

            if (dataFormacion.length === 0) {
                tbody.innerHTML = `<tr><td colspan="11" class="empty-row py-3">No hay formación profesional registrada</td></tr>`;
                return;
            }

            dataFormacion.forEach((item, index) => {
                const tr = document.createElement('tr');
                const iconDoc = item.tieneDoc && item.docBase64
                    ? `<button type="button" class="btn btn-link p-0 text-danger" onclick="verPreviewDoc(${item.id})">
                        <i class="fas fa-file-pdf fs-4" title="Ver Vista Previa"></i>
                       </button>` 
                    : '-';

                tr.innerHTML = `
                    <td class="text-center fw-bold">${index + 1}</td>
                    <td>${item.nivel}</td>
                    <td>${item.pais}</td>
                    <td>${item.universidad}</td>
                    <td>${item.especialidad}</td>
                    <td class="text-center">${item.fecInicio}</td>
                    <td class="text-center">${item.fecFinal}</td>
                    <td>${item.grado}</td>
                    <td class="text-center">${iconDoc}</td>
                    <td>${item.obs}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="eliminarFormacion(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        window.verPreviewDoc = function(id) {
            const registro = dataFormacion.find(item => item.id === id);
            if (registro && registro.docBase64) {
                document.getElementById('framePreviewPDF').src = registro.docBase64;
                const modal = new bootstrap.Modal(document.getElementById('modalPreviewPDF'));
                modal.show();
            }
        };

        window.eliminarFormacion = function(id) {
            dataFormacion = dataFormacion.filter(item => item.id !== id);
            localStorage.setItem('docente_formacion', JSON.stringify(dataFormacion));
            renderTablaFormacion();
        };

        // GENERACIÓN PDF
        function initBotonPDF() {
            const btnGenerarPDF = document.getElementById('btnGenerarPDF');
            const elementoReporte = document.getElementById('contenidoReporte');

            if (btnGenerarPDF && elementoReporte) {
                btnGenerarPDF.addEventListener('click', function() {
                    if (typeof html2pdf === 'undefined') {
                        alert('Error: La librería html2pdf.js no está disponible.');
                        return;
                    }

                    btnGenerarPDF.disabled = true;
                    btnGenerarPDF.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generando...';
                    
                    elementoReporte.classList.add('modo-pdf');

                    const opciones = {
                        margin:       [10, 10, 10, 10],
                        filename:     'Ficha_Docente_OBREGON_RAMOS_MAXIMO.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2, useCORS: true, logging: false },
                        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    };

                    html2pdf().set(opciones).from(elementoReporte).save().then(() => {
                        elementoReporte.classList.remove('modo-pdf');
                        btnGenerarPDF.disabled = false;
                        btnGenerarPDF.innerHTML = '<i class="fas fa-file-pdf me-1"></i> Generar PDF';
                    }).catch(err => {
                        console.error('Error al generar PDF:', err);
                        elementoReporte.classList.remove('modo-pdf');
                        btnGenerarPDF.disabled = false;
                        btnGenerarPDF.innerHTML = '<i class="fas fa-file-pdf me-1"></i> Generar PDF';
                        alert('Error al generar el PDF.');
                    });
                });
            }
        }
    });
</script>
@endverbatim

<!-- MODAL VISTA PREVIA PDF (AGREGAR AL FINAL DE TU HTML DEBAJO DE LOS OTROS MODALES) -->
<div class="modal fade" id="modalPreviewPDF" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="height: 90vh;">
        <div class="modal-content h-100 border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-pdf me-2 text-danger"></i>Vista Previa del Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="framePreviewPDF" class="w-100 h-100" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
