<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Información Docente') }}
        </h2>
    </x-slot>

    <!-- INICIO DEL CONTENEDOR REPORTE -->
    <div class="container py-4" id="contenidoReporte">
        <!-- CABECERA DEL DOCENTE -->
        <div class="card mb-4 shadow-sm border-0 bg-light">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://via.placeholder.com/80" class="rounded-circle border" alt="Foto Docente" width="80" height="80">
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">OBREGON RAMOS, MAXIMO</h4>
                        <span class="text-muted small">08161078</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-danger btn-sm fw-bold" id="btnGenerarPDF">
                        <i class="fas fa-file-pdf me-1"></i> Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- 1.- DATOS PERSONALES -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">1.- DATOS PERSONALES</h5>
                <button type="button" class="btn btn-light text-primary btn-sm fw-bold" id="btnAñadirDatosPersonales">
                    <i class="fas fa-plus-circle me-1"></i> AÑADIR REGISTRO
                </button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sexo</label>
                        <select id="select-sexo" class="form-select">
                            <option value="Masculino" selected>Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Fecha Nacimiento</label>
                        <input type="date" id="input-fec-nac" class="form-control" value="1975-02-16">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">DNI <i class="fas fa-check-circle text-success"></i></label>
                        <input type="text" id="input-dni" class="form-control" value="08161078" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Teléfono Fijo</label>
                        <input type="text" id="input-tel-fijo" class="form-control" placeholder="5316292 | 5456454">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Celular</label>
                        <input type="text" id="input-celular" class="form-control" placeholder="951629294">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">RUC</label>
                        <input type="text" id="input-ruc" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Lib. Militar</label>
                        <input type="text" id="input-lib-militar" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Brevete y Categoría</label>
                        <input type="text" id="input-brevete" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Vehículo (Modelo, Placa, Año, Color)</label>
                        <input type="text" id="input-vehiculo" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Estado Civil</label>
                        <select id="select-estado-civil" class="form-select">
                            <option value="Soltero">Soltero(a)</option>
                            <option value="Casado" selected>Casado(a)</option>
                            <option value="Divorciado">Divorciado(a)</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="contenedor-fecha-matrimonio">
                        <label class="form-label fw-bold">Fecha Matrimonio</label>
                        <input type="date" id="input-fec-matrimonio" class="form-control" value="2005-04-01">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Grupo Sanguíneo</label>
                        <input type="text" id="input-grupo-sanguineo" class="form-control" value="O+">
                    </div>
                </div>

                <!-- LUGAR DE NACIMIENTO -->
                <h5 class="mt-4 mb-3 text-secondary border-bottom pb-2 fw-bold">Lugar de Nacimiento</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="select-pais" class="form-label fw-bold">País</label>
                        <select id="select-pais" class="form-select">
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
                        <label for="select-depa" class="form-label fw-bold">Departamento</label>
                        <select id="select-depa" class="form-select">
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>

                    <div id="campo-prov" class="col-md-3 d-none">
                        <label for="select-prov" class="form-label fw-bold">Provincia</label>
                        <select id="select-prov" class="form-select" disabled>
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>

                    <div id="campo-dist" class="col-md-3 d-none">
                        <label for="select-dist" class="form-label fw-bold">Distrito</label>
                        <select id="select-dist" class="form-select" disabled>
                            <option value="">-- Seleccionar --</option>
                        </select>
                    </div>

                    <div id="contenedor-extranjero" class="col-md-9 d-none">
                        <label for="input-direccion-ext" class="form-label fw-bold">Dirección / Estado / Ciudad Extranjera</label>
                        <input type="text" id="input-direccion-ext" class="form-control" placeholder="Ingrese ciudad/estado del extranjero">
                    </div>
                </div>
            </div>
        </div>
        <!-- 2.- DOMICILIO -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Domicilio</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDomicilio">
            <i class="fas fa-plus me-1"></i> AÑADIR REGISTRO
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Orden</th><th>País</th><th>Dirección Extranjera</th><th>Departamento</th><th>Provincia</th><th>Distrito</th><th>Tipo de Vía</th><th>Nombre de la Vía</th><th>Tipo</th><th>Número de Inmueble</th><th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tablaDomicilio">
                    <!-- Registros dinámicos -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DATOS FAMILIARES -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Carga Familiar</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFamiliar">
            <i class="fas fa-plus me-1"></i> AÑADIR REGISTRO
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Apellidos y Nombres</th><th>Parentesco</th><th>Celular</th><th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tablaFamiliar">
                    <!-- Registros dinámicos -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 3.- DATOS DE FORMACIÓN PROFESIONAL -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">3.- DATOS DE FORMACIÓN PROFESIONAL</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFormacion">
            <i class="fas fa-plus me-1"></i> AÑADIR REGISTRO
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Orden</th><th>Formación Profesional</th><th>País</th><th>Universidad</th><th>Especialidad</th><th>Fecha Inicio</th><th>Fecha Final</th><th>Grado Obtenido</th><th>Documento Sustentatorio</th><th>Observación</th><th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tablaFormacion">
                    <!-- Registros dinámicos -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL AGREGAR DOMICILIO -->
<div class="modal fade" id="modalDomicilio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Agregar domicilio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDomicilio">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="selectPais">País</label>
                            <select class="form-select" id="selectPais" name="pais_id">
                                <option value="">-- Seleccionar --</option>
                                <option value="PE">Perú</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="selectDepartamento">Departamento</label>
                            <select class="form-select" id="selectDepartamento" name="departamento_id" disabled>
                                <option value="">-- Seleccionar --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="selectProvincia">Provincia</label>
                            <select class="form-select" id="selectProvincia" name="provincia_id" disabled>
                                <option value="">-- Seleccionar --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="selectDistrito">Distrito</label>
                            <select class="form-select" id="selectDistrito" name="distrito_id" disabled>
                                <option value="">-- Seleccionar --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="selectTipoVia">Tipo de Vía</label>
                            <select class="form-select" id="selectTipoVia" name="tipo_via">
                                <option value="">Seleccionar</option>
                                <option value="AV">Avenida</option>
                                <option value="JR">Jirón</option>
                                <option value="CALLE">Calle</option>
                                <option value="PSJ">Pasaje</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="nombreVia">Nombre de la Vía</label>
                            <input type="text" class="form-control" id="nombreVia" name="nombre_via">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="selectTipoInmueble">Tipo</label>
                            <select class="form-select" id="selectTipoInmueble" name="tipo_inmueble">
                                <option value="">Seleccionar</option>
                                <option value="CASA">Casa</option>
                                <option value="DEP">Departamento</option>
                                <option value="INT">Interior</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="numeroInmueble">Número de Inmueble</label>
                            <input type="text" class="form-control" id="numeroInmueble" name="numero_inmueble">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnGuardarDomicilio">GUARDAR</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL AGREGAR FAMILIAR -->
<div class="modal fade" id="modalFamiliar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Agregar Familiar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Apellidos y Nombres</label><input type="text" class="form-control" id="familiarNombre"></div>
                    <div class="col-md-6"><label class="form-label">Parentesco</label><select class="form-select" id="familiarParentesco"><option value="">Seleccionar</option><option value="Padre">Padre</option><option value="Madre">Madre</option><option value="Hermano(a)">Hermano(a)</option><option value="Hijo(a)">Hijo(a)</option><option value="Cónyuge">Cónyuge</option></select></div>
                    <div class="col-md-6"><label class="form-label">Fecha Nacimiento</label><input type="date" class="form-control" id="familiarFecNac"></div>
                    <div class="col-md-6"><label class="form-label">Est. Civil</label><select class="form-select" id="familiarEstCivil"><option value="">Seleccionar</option><option value="Soltero(a)">Soltero(a)</option><option value="Casado(a)">Casado(a)</option><option value="Viudo(a)">Viudo(a)</option><option value="Divorciado(a)">Divorciado(a)</option></select></div>
                    <div class="col-md-6"><label class="form-label">Nivel Estudios</label><select class="form-select" id="familiarEstudios"><option value="">Seleccionar</option><option value="Primaria">Primaria</option><option value="Secundaria">Secundaria</option><option value="Técnico">Técnico</option><option value="Universitario">Universitario</option></select></div>
                    <div class="col-md-6"><label class="form-label">Ocupación</label><input type="text" class="form-control" id="familiarOcupacion"></div>
                    <div class="col-md-6"><label class="form-label">Celular</label><input type="text" class="form-control" id="familiarCelular"></div>
                    <div class="col-md-6">
                        <label class="form-label d-block">¿Depende económicamente de Ud.?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="depende" id="depSi" value="Si">
                            <label class="form-check-label" for="depSi">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="depende" id="depNo" value="No" checked>
                            <label class="form-check-label" for="depNo">No</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnGuardarFamiliar">GUARDAR</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL AGREGAR FORMACIÓN PROFESIONAL -->
<div class="modal fade" id="modalFormacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Agregar formación profesional</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Formación profesional</label><select class="form-select" id="formacionNivel"><option value="">Seleccionar</option><option value="Licenciatura">Licenciatura</option><option value="Maestría">Maestría</option><option value="Doctorado">Doctorado</option></select></div>
                    <div class="col-md-6"><label class="form-label">País</label><select class="form-select" id="formacionPais"><option value="">Seleccionar</option><option value="PE">Perú</option><option value="EXTRANJERO">Extranjero</option></select></div>
                    <div class="col-md-6"><label class="form-label">Universidad</label><input type="text" class="form-control" id="formacionUniversidad"></div>
                    <div class="col-md-6"><label class="form-label">Especialidad</label><input type="text" class="form-control" id="formacionEspecialidad"></div>
                    <div class="col-md-6"><label class="form-label">Fecha Inicio</label><input type="date" class="form-control" id="formacionFecInicio"></div>
                    <div class="col-md-6"><label class="form-label">Fecha Final</label><input type="date" class="form-control" id="formacionFecFinal"></div>
                    <div class="col-md-6"><label class="form-label">Grado Obtenido</label><input type="text" class="form-control" id="formacionGrado"></div>
                    <div class="col-md-6"><label class="form-label">Documento sustentatorio</label><input type="file" class="form-control" id="formacionDoc"></div>
                    <div class="col-md-12"><label class="form-label">Observación</label><textarea class="form-control" rows="2" id="formacionObs"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnGuardarFormacion">GUARDAR</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
            </div>
        </div>
    </div>
</div>

    <!-- SCRIPT DE LÓGICA DE UBIGEO Y ESTADO CIVIL -->
@verbatim
<script>
// Data de Ubigeo
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
        
    };
document.addEventListener('DOMContentLoaded', function() {
    const selectPais = document.getElementById('select-pais');
    const selectDep = document.getElementById('select-depa');
    const selectProv = document.getElementById('select-prov');
    const selectDist = document.getElementById('select-dist');

    const campoDepa = document.getElementById('campo-depa');
    const campoProv = document.getElementById('campo-prov');
    const campoDist = document.getElementById('campo-dist');
    const contenedorExtranjero = document.getElementById('contenedor-extranjero');

    function resetSelect(selectElement, defaultText = '-- Seleccionar --') {
        if (selectElement) {
            selectElement.innerHTML = `<option value="">${defaultText}</option>`;
        }
    }

    function cargarDepartamentos() {
        if (!selectDep) return;
        resetSelect(selectDep);
        resetSelect(selectProv);
        resetSelect(selectDist);

        Object.keys(ubigeoData).sort().forEach(dep => {
            let option = document.createElement('option');
            option.value = dep;
            option.textContent = dep;
            selectDep.appendChild(option);
        });
    }

    function manejarCambioPais() {
        if (!selectPais) return;
        const val = selectPais.value.trim().toUpperCase();

        // Evalúa si el valor es Perú (por código PE, texto Peru, Perú o ID 1)
        const esPeru = ['PE', 'PERU', 'PERÚ', '1'].includes(val);

        if (esPeru) {
            if (campoDepa) campoDepa.classList.remove('d-none');
            if (campoProv) campoProv.classList.remove('d-none');
            if (campoDist) campoDist.classList.remove('d-none');
            if (contenedorExtranjero) contenedorExtranjero.classList.add('d-none');
            
            // Solo carga departamentos si el select está vacío
            if (selectDep && selectDep.options.length <= 1) {
                cargarDepartamentos();
            }
        } else if (val !== '') {
            if (campoDepa) campoDepa.classList.add('d-none');
            if (campoProv) campoProv.classList.add('d-none');
            if (campoDist) campoDist.classList.add('d-none');
            if (contenedorExtranjero) contenedorExtranjero.classList.remove('d-none');
        } else {
            if (campoDepa) campoDepa.classList.add('d-none');
            if (campoProv) campoProv.classList.add('d-none');
            if (campoDist) campoDist.classList.add('d-none');
            if (contenedorExtranjero) contenedorExtranjero.classList.add('d-none');
        }
    }

    if (selectPais) {
        selectPais.addEventListener('change', manejarCambioPais);
    }

    if (selectDep) {
        selectDep.addEventListener('change', function() {
            resetSelect(selectProv);
            resetSelect(selectDist);

            const depSeleccionado = this.value;
            if (depSeleccionado && ubigeoData[depSeleccionado]) {
                if (selectProv) selectProv.disabled = false;
                Object.keys(ubigeoData[depSeleccionado]).sort().forEach(prov => {
                    let option = document.createElement('option');
                    option.value = prov;
                    option.textContent = prov;
                    selectProv.appendChild(option);
                });
            }
        });
    }

    if (selectProv) {
        selectProv.addEventListener('change', function() {
            resetSelect(selectDist);

            const depSeleccionado = selectDep ? selectDep.value : '';
            const provSeleccionada = this.value;

            if (depSeleccionado && provSeleccionada && ubigeoData[depSeleccionado][provSeleccionada]) {
                if (selectDist) selectDist.disabled = false;
                ubigeoData[depSeleccionado][provSeleccionada].sort().forEach(dist => {
                    let option = document.createElement('option');
                    option.value = dist;
                    option.textContent = dist;
                    selectDist.appendChild(option);
                });
            }
        });
    }

    // Manejo de Estado Civil
    const selectEstadoCivil = document.getElementById('select-estado-civil');
    const contenedorFechaMatrimonio = document.getElementById('contenedor-fecha-matrimonio');

    if (selectEstadoCivil && contenedorFechaMatrimonio) {
        selectEstadoCivil.addEventListener('change', function() {
            if (this.value === 'Casado') {
                contenedorFechaMatrimonio.classList.remove('d-none');
            } else {
                contenedorFechaMatrimonio.classList.add('d-none');
            }
        });
    }

    // Forzar la inicialización del país
    manejarCambioPais();
});


document.addEventListener('DOMContentLoaded', function() {

    // --- 1. LÓGICA UBIGEO ---
    const selectPais = document.getElementById('selectPais');
    const selectDep = document.getElementById('selectDepartamento');
    const selectProv = document.getElementById('selectProvincia');
    const selectDist = document.getElementById('selectDistrito');

    function resetSelect(selectElement, disabled = true) {
        if (selectElement) {
            selectElement.innerHTML = '<option value="">-- Seleccionar --</option>';
            selectElement.disabled = disabled;
        }
    }

    if (selectPais) {
        selectPais.addEventListener('change', function() {
            const val = this.value.trim().toUpperCase();
            if ((val === 'PE' || val === 'PERÚ' || val === 'PERU') && typeof ubigeoData !== 'undefined') {
                resetSelect(selectDep, false);
                resetSelect(selectProv, true);
                resetSelect(selectDist, true);
                Object.keys(ubigeoData).sort().forEach(dep => {
                    let option = document.createElement('option');
                    option.value = dep;
                    option.textContent = dep;
                    selectDep.appendChild(option);
                });
            } else {
                resetSelect(selectDep, true);
                resetSelect(selectProv, true);
                resetSelect(selectDist, true);
            }
        });
    }

    if (selectDep) {
        selectDep.addEventListener('change', function() {
            resetSelect(selectProv, true);
            resetSelect(selectDist, true);
            const dep = this.value;
            if (dep && ubigeoData[dep]) {
                selectProv.disabled = false;
                Object.keys(ubigeoData[dep]).sort().forEach(prov => {
                    let option = document.createElement('option');
                    option.value = prov;
                    option.textContent = prov;
                    selectProv.appendChild(option);
                });
            }
        });
    }

    if (selectProv) {
        selectProv.addEventListener('change', function() {
            resetSelect(selectDist, true);
            const dep = selectDep ? selectDep.value : '';
            const prov = this.value;
            if (dep && prov && ubigeoData[dep][prov]) {
                selectDist.disabled = false;
                ubigeoData[dep][prov].sort().forEach(dist => {
                    let option = document.createElement('option');
                    option.value = dist;
                    option.textContent = dist;
                    selectDist.appendChild(option);
                });
            }
        });
    }

    // --- 2. GUARDAR DOMICILIO ---
    const btnGuardarDomicilio = document.getElementById('btnGuardarDomicilio');
    const tablaDomicilio = document.getElementById('tablaDomicilio');

    if (btnGuardarDomicilio && tablaDomicilio) {
        btnGuardarDomicilio.addEventListener('click', function() {
            const orden = tablaDomicilio.children.length + 1;
            const paisText = selectPais.options[selectPais.selectedIndex]?.text || '-';
            const dep = selectDep.value || '-';
            const prov = selectProv.value || '-';
            const dist = selectDist.value || '-';
            const tipoVia = document.getElementById('selectTipoVia')?.value || '-';
            const nombreVia = document.getElementById('nombreVia')?.value || '-';
            const tipoInmueble = document.getElementById('selectTipoInmueble')?.value || '-';
            const numeroInmueble = document.getElementById('numeroInmueble')?.value || '-';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${orden}</td>
                <td>${paisText}</td>
                <td>-</td>
                <td>${dep}</td>
                <td>${prov}</td>
                <td>${dist}</td>
                <td>${tipoVia}</td>
                <td>${nombreVia}</td>
                <td>${tipoInmueble}</td>
                <td>${numeroInmueble}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tablaDomicilio.appendChild(tr);

            // Cerrar modal y limpiar
            const modalEl = document.getElementById('modalDomicilio');
            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalInstance.hide();
            
            document.getElementById('formDomicilio').reset();
            resetSelect(selectDep, true);
            resetSelect(selectProv, true);
            resetSelect(selectDist, true);
        });
    }

    // --- 3. GUARDAR FAMILIAR ---
    const modalFamiliar = document.getElementById('modalFamiliar');
    const tablaFamiliar = document.getElementById('tablaFamiliar');

    if (modalFamiliar && tablaFamiliar) {
        const btnGuardarFamiliar = modalFamiliar.querySelector('.btn-primary');
        btnGuardarFamiliar.addEventListener('click', function() {
            const nombre = document.getElementById('familiarNombre')?.value;
            const parentesco = document.getElementById('familiarParentesco')?.value;

            if (!nombre) {
                alert('Ingrese el nombre del familiar.');
                return;
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-start">${nombre}</td>
                <td>${parentesco || '-'}</td>
                <td>-</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tablaFamiliar.appendChild(tr);

            const modalInstance = bootstrap.Modal.getInstance(modalFamiliar) || new bootstrap.Modal(modalFamiliar);
            modalInstance.hide();
        });
    }

    // --- 4. GUARDAR FORMACIÓN ---
    const modalFormacion = document.getElementById('modalFormacion');
    const tablaFormacion = document.getElementById('tablaFormacion');

    if (modalFormacion && tablaFormacion) {
        const btnGuardarFormacion = modalFormacion.querySelector('.btn-primary');
        btnGuardarFormacion.addEventListener('click', function() {
            const orden = tablaFormacion.children.length + 1;
            const nivel = document.getElementById('formacionNivel')?.value || '-';
            const paisSelect = document.getElementById('formacionPais');
            const paisText = paisSelect?.options[paisSelect.selectedIndex]?.text || '-';
            const universidad = document.getElementById('formacionUniversidad')?.value || '-';
            const especialidad = document.getElementById('formacionEspecialidad')?.value || '-';
            const fecInicio = document.getElementById('formacionFecInicio')?.value || '-';
            const fecFinal = document.getElementById('formacionFecFinal')?.value || '-';
            const grado = document.getElementById('formacionGrado')?.value || '-';
            const obs = document.getElementById('formacionObs')?.value || '-';
            const fileInput = document.getElementById('formacionDoc');
            
            let docIcon = fileInput && fileInput.files.length > 0 
                ? '<i class="fas fa-file-pdf text-danger fs-5"></i>' 
                : '-';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center">${orden}</td>
                <td>${nivel}</td>
                <td>${paisText}</td>
                <td>${universidad}</td>
                <td>${especialidad}</td>
                <td class="text-center">${fecInicio}</td>
                <td class="text-center">${fecFinal}</td>
                <td>${grado}</td>
                <td class="text-center">${docIcon}</td>
                <td>${obs}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="fas fa-minus"></i></button>
                </td>
            `;
            tablaFormacion.appendChild(tr);

            const modalInstance = bootstrap.Modal.getInstance(modalFormacion) || new bootstrap.Modal(modalFormacion);
            modalInstance.hide();
        });
    }

    // --- 5. ELIMINAR FILAS GLOBAL ---
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            const fila = btn.closest('tr');
            const tabla = fila.closest('tbody');
            fila.remove();

            if (tabla.id === 'tablaDomicilio' || tabla.id === 'tablaFormacion') {
                Array.from(tabla.children).forEach((tr, index) => {
                    tr.cells[0].textContent = index + 1;
                });
            }
        }
    });

});
document.addEventListener('DOMContentLoaded', function() {

    // --- MANEJO DEL BOTÓN "AÑADIR REGISTRO" DE DATOS PERSONALES ---
    const btnAñadirDatos = document.getElementById('btnAñadirDatosPersonales');
    const tablaDomicilio = document.getElementById('tablaDomicilio');

    if (btnAñadirDatos && tablaDomicilio) {
        btnAñadirDatos.addEventListener('click', function() {
            const selectPais = document.getElementById('select-pais');
            const selectDep = document.getElementById('select-depa');
            const selectProv = document.getElementById('select-prov');
            const selectDist = document.getElementById('select-dist');
            const inputExt = document.getElementById('input-direccion-ext');

            // Obtener texto seleccionado de los dropdowns
            const paisText = selectPais.options[selectPais.selectedIndex]?.text || '-';
            const esPeru = selectPais.value === 'PE';

            let dirExtranjera = '-';
            let depText = '-';
            let provText = '-';
            let distText = '-';

            if (esPeru) {
                depText = selectDep.value ? selectDep.options[selectDep.selectedIndex]?.text : '-';
                provText = selectProv.value ? selectProv.options[selectProv.selectedIndex]?.text : '-';
                distText = selectDist.value ? selectDist.options[selectDist.selectedIndex]?.text : '-';
            } else if (selectPais.value !== '') {
                dirExtranjera = inputExt.value.trim() !== '' ? inputExt.value.trim() : '-';
            } else {
                alert('Por favor, seleccione al menos el País en Lugar de Nacimiento.');
                return;
            }

            const orden = tablaDomicilio.children.length + 1;

            // Construcción de la fila para la Tabla Domicilio
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center">${orden}</td>
                <td>${paisText}</td>
                <td>${dirExtranjera}</td>
                <td>${depText}</td>
                <td>${provText}</td>
                <td>${distText}</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar"><i class="fas fa-trash"></i></button>
                </td>
            `;

            tablaDomicilio.appendChild(tr);
        });
    }

    // --- MANEJO MOSTRAR / OCULTAR UBIGEO NACIONAL VS EXTRANJERO ---
    const selectPais = document.getElementById('select-pais');
    const campoDepa = document.getElementById('campo-depa');
    const campoProv = document.getElementById('campo-prov');
    const campoDist = document.getElementById('campo-dist');
    const contenedorExt = document.getElementById('contenedor-extranjero');

    if (selectPais) {
        selectPais.addEventListener('change', function() {
            if (this.value === 'PE') {
                campoDepa.classList.remove('d-none');
                campoProv.classList.remove('d-none');
                campoDist.classList.remove('d-none');
                contenedorExt.classList.add('d-none');
            } else if (this.value !== '') {
                campoDepa.classList.add('d-none');
                campoProv.classList.add('d-none');
                campoDist.classList.add('d-none');
                contenedorExt.classList.remove('d-none');
            } else {
                campoDepa.classList.add('d-none');
                campoProv.classList.add('d-none');
                campoDist.classList.add('d-none');
                contenedorExt.classList.add('d-none');
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerarPDF = document.getElementById('btnGenerarPDF');
    const elementoReporte = document.getElementById('contenidoReporte');

    if (btnGenerarPDF && elementoReporte) {
        btnGenerarPDF.addEventListener('click', function() {
            // Oculta botones y aplica formato de impresión
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
            }).catch(err => {
                console.error('Error al generar PDF:', err);
                elementoReporte.classList.remove('modo-pdf');
            });
        });
    } else {
        console.error('No se encontró el botón con id "btnGenerarPDF" o el contenedor "contenidoReporte"');
    }
});

</script>

@endverbatim
</x-app-layout>
