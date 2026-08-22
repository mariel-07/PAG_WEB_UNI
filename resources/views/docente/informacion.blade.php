<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Información Docente') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <!-- 1.- DATOS PERSONALES -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">1.- DATOS PERSONALES</h5>
                <button class="btn btn-light btn-sm fw-bold"><i class="fas fa-file-pdf text-danger me-1"></i> Generar PDF</button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sexo</label>
                        <select class="form-select">
                            <option>Masculino</option>
                            <option>Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Fecha Nacimiento</label>
                        <input type="date" class="form-control" value="1975-02-16">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">DNI <i class="fas fa-check-circle text-success"></i></label>
                        <input type="text" class="form-control" value="08161078" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Estado Civil</label>
                        <select class="form-select">
                            <option>Soltero(a)</option>
                            <option selected>Casado(a)</option>
                            <option>Divorciado(a)</option>
                        </select>
                    </div>
                </div>

                <!-- LUGAR DE NACIMIENTO CON FILTRADO DINÁMICO -->
                <h5 class="mt-4 mb-3 text-secondary border-bottom pb-2 fw-bold">Lugar de Nacimiento</h5>
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="select-pais" class="form-label fw-bold">País</label>
                        <select id="select-pais" class="form-select">
                            <option value="">-- Seleccionar --</option>
                            <option value="PE" selected>Perú</option>
                            <option value="ARG">Argentina</option>
                            <option value="BRA">Brasil</option>
                            <option value="CHL">Chile</option>
                            <option value="COL">Colombia</option>
                            <option value="ESP">España</option>
                            <option value="USA">Estados Unidos</option>
                            <option value="OTRO">Otro País</option>
                        </select>
                    </div>

                    <div id="contenedor-ubigeo" class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="select-depa" class="form-label fw-bold">Departamento</label>
                                <select id="select-depa" class="form-select">
                                    <option value="">-- Seleccionar --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="select-prov" class="form-label fw-bold">Provincia</label>
                                <select id="select-prov" class="form-select" disabled>
                                    <option value="">-- Seleccionar --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="select-dist" class="form-label fw-bold">Distrito</label>
                                <select id="select-dist" class="form-select" disabled>
                                    <option value="">-- Seleccionar --</option>
                                </select>
                            </div>
                        </div>
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
                    <i class="fas fa-plus"></i> AÑADIR REGISTRO
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Orden</th><th>País</th><th>Dirección Extranjera</th><th>Departamento</th><th>Provincia</th><th>Distrito</th><th>Tipo Vía</th><th>Inmueble</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td><td>Perú</td><td>-</td><td>Lima</td><td>Lima</td><td>San Martín de Porres</td><td>Mz</td><td>F</td>
                            </tr>
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
                    <i class="fas fa-plus"></i> AÑADIR REGISTRO
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Orden</th><th>Formación</th><th>País</th><th>Universidad</th><th>Especialidad</th><th>F. Inicio</th><th>F. Final</th><th>Grado</th><th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Título Profesional</td>
                                <td>Perú</td>
                                <td>UNIVERSIDAD NACIONAL DE INGENIERÍA</td>
                                <td>Ingeniero Mecánico</td>
                                <td>08/08/93</td>
                                <td>12/12/99</td>
                                <td>Titulado</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">País</label><select class="form-select"><option>Perú</option></select></div>
                        <div class="col-md-6"><label class="form-label">Departamento</label><select class="form-select"><option>Ancash</option></select></div>
                        <div class="col-md-6"><label class="form-label">Provincia</label><select class="form-select"><option>Aija</option></select></div>
                        <div class="col-md-6"><label class="form-label">Distrito</label><select class="form-select"><option>Coris</option></select></div>
                        <div class="col-md-6"><label class="form-label">Tipo de Vía</label><select class="form-select"><option>Seleccionar</option></select></div>
                        <div class="col-md-6"><label class="form-label">Nombre de la Vía</label><input type="text" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">GUARDAR</button>
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
                        <div class="col-md-6"><label class="form-label">Formación profesional</label><select class="form-select"><option>Seleccionar</option></select></div>
                        <div class="col-md-6"><label class="form-label">País</label><select class="form-select"><option>Seleccionar</option></select></div>
                        <div class="col-md-6"><label class="form-label">Universidad</label><input type="text" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Especialidad</label><input type="text" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Fecha Inicio</label><input type="date" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Fecha Final</label><input type="date" class="form-control"></div>
                        <div class="col-md-12"><label class="form-label">Documento sustentatorio (PDF)</label><input type="file" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">GUARDAR</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE LÓGICA DE UBIGEO CORREGIDO -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
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
    "Áncash": {
        "Huaraz": ["Huaraz", "Cochabamba", "Colcabamba", "Huanchay", "Jangas", "La Libertad", "Olleroc", "Pampas Grande", "Pariacoto", "Pira", "Tarica"],
        "Aija": ["Aija", "Coris", "Huacllán", "La Merced", "Succha"],
        "Antonio Raimondi": ["Llamellín", "Aczo", "Chaccho", "Chingas", "Miras", "San Juan de Rontoy"],
        "Asunción": ["Chacas", "Acochaca"],
        "Bolognesi": ["Chiquián", "Abelardo Pardo Lezameta", "Antonio Raymondi", "Aquia", "Cajacay", "Canis", "Colquioc", "Huallanca", "Huasta", "Huayllacán", "La Primavera", "Mangas", "Pacllón", "San Miguel de Corpanqui", "Ticllos"],
        "Carhuaz": ["Carhuaz", "Acopampa", "Amashca", "Anta", "Ataquero", "Marcara", "Pariahuanca", "San Miguel de Aco", "Shilla", "Tinco", "Yungar"],
        "Carlos Fermín Fitzcarrald": ["San Luis", "San Nicolás", "Yauya"],
        "Casma": ["Casma", "Buena Vista Alta", "Comandante Noel", "Yaután"],
        "Corongo": ["Corongo", "Aco", "Bambas", "Cusca", "La Pampa", "Yanac", "Yupan"],
        "Huari": ["Huari", "Anra", "Cajay", "Chavín de Huántar", "Huacachi", "Huacchis", "Huachis", "Huántar", "Masin", "Paucas", "Ponto", "Rahuapampa", "Rapayán", "San Marcos", "San Pedro de Chaná", "Uco"],
        "Huarmey": ["Huarmey", "Culebras", "Huayán", "Malvas", "Cochapeti"],
        "Huaylas": ["Caraz", "Huata", "Huaylas", "Mato", "Pamparomás", "Pueblo Libre", "Santa Cruz", "Santo Toribio", "Yuracmarca"],
        "Mariscal Luzuriaga": ["Piscobamba", "Casca", "Eleazar Guzmán Barrón", "Fidel Olivas Escudero", "Llama", "Llumpa", "Lucma", "Musga"],
        "Ocros": ["Ocros", "Acapampa", "Cajamarquilla", "Carhuapampa", "Cochas", "Congas", "Llipa", "San Cristóbal de Raján", "San Pedro", "Santiago de Chilcas"],
        "Pallasca": ["Cabana", "Bolognesi", "Conchucos", "Huandoval", "Huacaschuque", "Lacabamba", "Lloque", "Pallasca", "Pampas", "Santa Rosa", "Tauca"],
        "Pomabamba": ["Pomabamba", "Huayllán", "Parobamba", "Quinuabamba"],
        "Recuay": ["Recuay", "Catac", "Cotaparaco", "Huayllapampa", "Llacllín", "Marca", "Pampas Chico", "Pararín", "Tapacocha", "Ticapampa"],
        "Santa": ["Chimbote", "Cáceres del Perú", "Coishco", "Macate", "Moro", "Nepeña", "Nuevo Chimbote", "Samanco", "Santa"],
        "Sihuas": ["Sihuas", "Acobamba", "Alfonso Ugarte", "Cashapampa", "Chingalpo", "Huayllabamba", "Quiches", "Ragash", "San Juan", "Sicsibamba"],
        "Yungay": ["Yungay", "Cascapara", "Mancos", "Matacoto", "Quillo", "Ranrahirca", "Shupluy", "Yanama"]
    },
    "Apurímac": {
        "Abancay": ["Abancay", "Chacoche", "Circa", "Curahuasi", "Huanipaca", "Lambrama", "Pichirhua", "San Pedro de Cachora", "Tamburco"],
        "Andahuaylas": ["Andahuaylas", "Andarapa", "Chiara", "Huancarama", "Huancaray", "Huayana", "Kiski", "Kaquiabamba", "Pacucha", "Pancorba", "Pacobamba", "Pampachiri", "Pomacocha", "San Antonio de Cachi", "San Jerónimo", "San Miguel de Chaccrampa", "Santa María de Chicmo", "Talavera", "Tumay Huaraca", "Turpo", "Kaquiabamba"],
        "Antabamba": ["Antabamba", "El Oro", "Huaquirca", "Juan Espinoza Medrano", "Oropesa", "Pachaconas", "Sabaino"],
        "Aymaraes": ["Chalhuanca", "Capaya", "Caraybamba", "Chapimarca", "Colcabamba", "Cotaruse", "Ihuayllo", "Justo Apu Sahuaraura", "Lucre", "Pocohuanca", "San Juan de Chacña", "Sañayca", "Soraya", "Tapairihua", "Tintay", "Toraya", "Yanaca"],
        "Cotabambas": ["Tambobamba", "Cotabambas", "Coyllurqui", "Haquira", "Mara", "Challhuahuacho"],
        "Chincheros": ["Chincheros", "Anco_Huallo", "Cocharcas", "Huaccana", "Ocobamba", "Ongoy", "Uranmarca", "Ranracancha", "Rocchacc", "El Porvenir", "Los Chankas"],
        "Grau": ["Chuquibambilla", "Curpahuasi", "Gamarra", "Huayllati", "Mamara", "Micaela Bastidas", "Pataypampa", "Progreso", "San Antonio", "Santa Rosa", "Turpay", "Vilcabamba", "Virundo", "Curasasco"]
    },
    "Arequipa": {
        "Arequipa": ["Arequipa", "Alto Selva Alegre", "Cayma", "Cerro Colorado", "Characato", "Chiguata", "Jacobo Hunter", "La Joya", "Mariano Melgar", "Miraflores", "Mollebaya", "Paucarpata", "Pocsi", "Polobaya", "Quequeña", "Sabanida", "Sachaca", "San Antonio de Chuca", "San Juan de Siguas", "San Juan de Tarucani", "Santa Isabel de Siguas", "Santa Rita de Siguas", "Socabaya", "Tiabaya", "Uchumayo", "Vítor", "Yanahuara", "Yarabamba", "Yura", "José Luis Bustamante y Rivero"],
        "Camaná": ["Camaná", "José María Quimper", "Mariano Nicolás Valcárcel", "Mariscal Cáceres", "Nicolás de Piérola", "Ocoña", "Quilca", "Samuel Pastor"],
        "Caravelí": ["Caravelí", "Acarí", "Atico", "Atiquipa", "Bella Unión", "Cahuacho", "Chala", "Chaparra", "Huanuhuanu", "Jaquí", "Lomas", "Quicacha", "Yauca"],
        "Castilla": ["Aplao", "Andagua", "Ayo", "Chachas", "Chilcaymarca", "Choco", "Huancarqui", "Machaguay", "Orcopampa", "Pampacolca", "Tipán", "Uñón", "Uraca", "Viraco"],
        "Caylloma": ["Chivay", "Achoma", "Cabanaconde", "Callalli", "Caylloma", "Coporaque", "Huambo", "Huanca", "Ichupampa", "Lari", "Lluta", "Maca", "Madrigal", "San Antonio de Chuca", "Sibayo", "Tapay", "Tisco", "Tuti", "Yanque", "Majes"],
        "Condesuyos": ["Chuquibamba", "Andaray", "Cayarani", "Chichas", "Iray", "Río Grande", "Salamanca", "Yanaquihua"],
        "Islay": ["Mollendo", "Cocachacra", "Dean Valdivia", "Isla Islay", "Mejía", "Punta de Bombón"],
        "La Unión": ["Cotahuasi", "Alca", "Charcana", "Huaynacotas", "Pampamarca", "Puyca", "Quechualla", "Sayla", "Tauría", "Tomepampa", "Toro"]
    },
    "Ayacucho": {
        "Huamanga": ["Ayacucho", "Acocro", "Acos Vinchos", "Carmen Alto", "Chiara", "Ocros", "Pacaycasa", "Quinua", "San José de Ticllas", "San Juan Bautista", "Santiago de Pischa", "Socos", "Tambillo", "Vinchos", "Jesús Nazareno", "Andrés Avelino Cáceres Dorregaray"],
        "Cangallo": ["Cangallo", "Chuschi", "Los Morochucos", "María Parado de Bellido", "Paras", "Totos"],
        "Huanca Sancos": ["Sancos", "Carapo", "Sacsamarca", "Santiago de Lucanamarca"],
        "Huanta": ["Huanta", "Ayahuanco", "Huamanguilla", "Iguain", "Luricocha", "Santillana", "Sivia", "Llochegua", "Canayre", "Uchuraccay", "Pucacolpa", "Chaca"],
        "La Mar": ["San Miguel", "Anco", "Ayna", "Chilcas", "Chungui", "Luis Carranza", "Santa Rosa", "Tambo", "Samugari", "Anchihuay"],
        "Lucanas": ["Puquio", "Aucara", "Cabana", "Carmen Salcedo", "Chaviña", "Chipao", "Leoncio Prado", "Llauta", "Lucanas", "Ocaña", "Otoca", "Saisa", "San Cristóbal", "San Juan", "San Pedro", "San Pedro de Palco", "Sancos", "Santa Ana de Huaycahuacho", "Santa Lucía"],
        "Parinacochas": ["Cora Cora", "Chumpi", "Coronel Castañeda", "Pacapausa", "Pullo", "Puyusca", "San Francisco de Ravacayco", "Upahuacho"],
        "Páucar del Sara Sara": "Pausa", ["Pausa", "Breña", "Colta", "Corculla", "Lampa", "Marcabamba", "Oyolo", "Pararca", "San Javier de Alpabamba", "San José de Ushua", "Sara Sara"],
        "Sucre": ["Querobamba", "Belén", "Chalcos", "Chilcayoc", "Huacaña", "Morcolla", "Paico", "San Salvador de Quije", "San Pedro de Larcay", "Santiagode Paucaray", "Soras"],
        "Víctor Fajardo": ["Huancapi", "Alcamenca", "Apongo", "Canaria", "Cayara", "Colca", "Huamanquiquia", "Huancaraylla", "Huaya", "Sarhua", "Vilcanchos"],
        "Vilcas Huamán": ["Vilcas Huamán", "Acomayo", "Concepción", "Huambalpa", "Independencia", "Saurama", "Vischongo", "Carhuanca"]
    },
    "Cajamarca": {
        "Cajamarca": ["Cajamarca", "Asunción", "Chetilla", "Coptes", "Encañada", "Jesús", "Llacanora", "Los Baños del Inca", "Magdalena", "Matara", "Namora", "San Juan"],
        "Cajabamba": ["Cajabamba", "Cachachi", "Condebamba", "Sitacocha"],
        "Celendín": ["Celendín", "Chumuch", "Cortegana", "Huasmín", "Jorge Chávez", "José Gálvez", "Miguel Iglesias", "Oxamarca", "Sorochuco", "Sucre", "Utco", "La Libertad de Pallán"],
        "Chota": ["Chota", "Anguía", "Chadin", "Chiguirip", "Chimban", "Choropampa", "Cochabamba", "Conchan", "Huambos", "Lajas", "Llama", "Miracosta", "Paccha", "Pión", "Querocoto", "San Antonio de Cachen", "San Juan de Licupis", "Tacabamba", "Tocmoche", "Chalamarca"],
        "Contumazá": ["Contumazá", "Chilete", "Cupisnique", "Guzmango", "San Benito", "Santa Cruz de Toledo", "Tantarica", "Yonan"],
        "Cutervo": ["Cutervo", "Callayuc", "Choros", "Cujillo", "La Succha", "Pinto Recodo", "San Andrés de Cutervo", "San Juan de Cutervo", "San Luis de Lucma", "Santa Cruz", "Santo Domingo de la Capilla", "Santo Tomás", "Socota", "Toribio Casanova"],
        "Hualgayoc": ["Bambamarca", "Chugur", "Hualgayoc"],
        "Jaén": ["Jaén", "Bellavista", "Chontali", "Colasay", "Huabal", "Las Pirias", "Pomahuaca", "Pucará", "Sallique", "San Felipe", "San José del Alto", "Santa Rosa"],
        "San Ignacio": ["San Ignacio", "Chirinos", "Huarango", "La Coipa", "Namballe", "San José de Lourdes", "Tabaconas"],
        "San Marcos": ["Pedro Gálvez", "Chancay", "Eduardo Villanueva", "Gregorio Pita", "Ichocán", "José Manuel Quiroz", "José Sabogal"],
        "San Miguel": ["San Miguel", "Bolívar", "Calquis", "Catilluc", "El Prado", "La Florida", "Llapa", "Nanchoc", "Niepos", "San Gregorio", "San Silvestre de Cochán", "Tongod", "Unión Agua Blanca"],
        "San Pablo": ["San Pablo", "San Bernardino", "San Luis", "Tumbaden"],
        "Santa Cruz": ["Santa Cruz", "Andabamba", "Catache", "Chancaybaños", "La Esperanza", "Ninabamba", "Pulán", "Saucepampa", "Sexi", "Uticyacu", "Yauyucan"]
    },
    "Callao": {
        "Callao": ["Callao", "Bellavista", "Carmen de la Legua", "La Perla", "La Punta", "Ventanilla", "Mi Perú"]
    },
    "Cusco": {
        "Cusco": ["Cusco", "Ccorca", "Poroy", "San Jerónimo", "San Sebastián", "Santiago", "Saylla", "Wanchaq"],
        "Acomayo": ["Acomayo", "Acopia", "Acos", "Mosan", "Poma Canchi", "Rondocan", "Sangarará"],
        "Anta": ["Anta", "Ancahuasi", "Cacharani", "Chinchaypujio", "Huarocondo", "Limatambo", "Mollepata", "Pucyura", "Zurite"],
        "Calca": ["Calca", "Coya", "Lamay", "Lares", "Pisac", "San Salvador", "Taray", "Yanatile"],
        "Canas": ["Yanaoca", "Checca", "Kunturkanqui", "Layo", "Pampamarca", "Quehue", "Túpac Amaru", "Tungasuca"],
        "Canchis": ["Sicuani", "Checacupe", "Combapata", "Marangani", "Pitumarca", "San Pablo", "San Pedro", "Tinta"],
        "Chumbivilcas": ["Santo Tomás", "Capacmarca", "Chamaca", "Colquemarca", "Livitaca", "Llusco", "Quiñota", "Velasille"],
        "Espinar": ["Espinar", "Condoroma", "Coporaque", "Ocoruro", "Pallpata", "Pichigua", "Quehue", "Suyckutambo"],
        "La Convención": ["Santa Ana", "Echarate", "Huayopata", "Marashuri", "Ocobamba", "Quellouno", "Kimbiri", "Santa Teresa", "Vilcabamba", "Pichari", "Inkawasi", "Villa Kintiarina", "Megantoni"],
        "Paruro": ["Paruro", "Accha", "Ccapi", "Colcha", "Huanoquite", "Omacha", "Paccaritambo", "Pillpinto", "Yaurisque"],
        "Paucartambo": ["Paucartambo", "Caicay", "Challabamba", "Colqueparta", "Huancarani", "Kosñipata"],
        "Quispicanchi": ["Urcos", "Andahuaylillas", "Camanti", "Ccarhuayo", "Ccatca", "Cusipata", "Lucre", "Marcapata", "Ocongate", "Oropesa", "Quiquijana", "Quispicanchi"],
        "Urubamba": ["Urubamba", "Chinchero", "Huayllabamba", "Machupicchu", "Maras", "Ollantaytambo", "Yucay"]
    },
    "Huancavelica": {
        "Huancavelica": ["Huancavelica", "Acobambilla", "Acoria", "Conayca", "Cuenca", "Huachocolpa", "Huayllahuara", "Izcuchaca", "Laria", "Manta", "Mariscal Cáceres", "Moya", "Nuevo Occoro", "Palca", "Pilchaca", "Vilca", "Yauli", "Ascensión", "Agua Blanca"],
        "Acobamba": ["Acobamba", "Andabamba", "Anta", "Caja", "Marcas", "Paucara", "Pomacocha", "Rosario"],
        "Angaraes": ["Lircay", "Anchonga", "Callanmarca", "Ccochaccasa", "Chincho", "Congalla", "Huanca-Huanca", "Huayllay Grande", "Julcamarca", "San Antonio de Antaparco", "Santo Tomás de Pata", "Secclla"],
        "Castrovirreyna": ["Castrovirreyna", "Arma", "Aurahua", "Capillas", "Chupamarca", "Cocos", "Huachos", "Huamatambo", "Mollepampa", "San Juan", "Santa Ana", "Tantara", "Ticrapo"],
        "Churcampa": ["Churcampa", "Anco", "Chinchihuasi", "El Carmen", "La Merced", "Locroja", "Paucarbamba", "San Pedro de Coris", "Pachamarca", "Cosme"],
        "Huaytará": ["Huaytará", "Ayavi", "Córdova", "Huayacundo Arma", "Laramarca", "Ocoyo", "Pilpichaca", "Querco", "Quito-Arma", "San Antonio de Cusicancha", "San Francisco de Sangayaico", "San Isidro", "Santiago de Chocorvos", "Santiago de Quirahuara", "Santo Domingo de Capillas", "Tambo"],
        "Tayacaja": ["Pampas", "Acostambo", "Acraquia", "Ahuaycha", "Colcabamba", "Daniel Hernández", "Huachocolpa", "Huaribamba", "Ñahuimpuquio", "Pazos", "Quishuar", "Salcabamba", "Salcahuasi", "San Marcos de Rocchac", "Surcubamba", "Pichos", "Roble", "Andaymarca"]
    },
    "Huánuco": {
        "Huánuco": ["Huánuco", "Amarilis", "Chinchao", "Churubamba", "Margos", "Quisqui", "San Francisco de Cayrán", "San Pedro de Chaulán", "Santa María del Valle", "Yarumayo", "Pillco Marca", "Yacus", "San Pablo de Pillao"],
        "Ambo": ["Ambo", "Cayna", "Colpas", "Conchamarca", "Huácar", "San Francisco", "San Rafael", "Tomay Kichwa"],
        "Dos de Mayo": ["La Unión", "Chuquis", "Marías", "Pachas", "Quivilla", "Ripan", "Shunqui", "Sillapata", "Yanas"],
        "Huacaybamba": ["Huacaybamba", "Canchabamba", "Cochabamba", "Pinra"],
        "Huamalíes": ["Llata", "Arancay", "Chavín de Pariarca", "Jacas Grande", "Jircan", "Miraflores", "Monzón", "Punchao", "Puños", "Singa", "Tantamayo"],
        "Leoncio Prado": ["Rupa-Rupa", "Daniel Alomía Robles", "Hermilio Valdizán", "Luyando", "Mariano Dámaso Beraún", "Pucayacu", "Castillo Grande", "Pueblo Nuevo", "Santo Domingo de Anda"],
        "Marañón": ["Huacrachuco", "Cholon", "San Buenaventura", "La Morada", "Santa Rosa de Alto Yanajanca"],
        "Pachitea": ["Panao", "Chaglla", "Molino", "Umari"],
        "Puerto Inca": ["Puerto Inca", "Codo del Pozuzo", "Honoria", "Tournavista", "Yuyapichis"],
        "Lauricocha": ["Jesús", "Baños", "Jivia", "Queropalca", "Rondos", "San Francisco de Asís", "San Miguel de Cauri"],
        "Yarowilca": ["Chavinillo", "Cahuac", "Chacabamba", "Aparicio Pomares", "Jacas Chico", "Obas", "Pampamarca", "Choras"]
    },
    "Ica": {
        "Ica": ["Ica", "La Tinguiña", "Los Aquijes", "Ocucaje", "Pachacútec", "Parcona", "Pueblo Nuevo", "Salas", "San José de Los Molinos", "San Juan Bautista", "Santiago", "Subtanjalla", "Tate", "Yauca del Rosario"],
        "Chincha": ["Chincha Alta", "Alto Larán", "Chavin", "Chincha Baja", "El Carmen", "Grocio Prado", "Pueblo Nuevo", "San Juan de Yanac", "San Pedro de Huacarpana", "Sunampe", "Tambo de Mora"],
        "Nasca": ["Nasca", "Changuillo", "El Ingenio", "Marcona", "Vista Alegre"],
        "Palpa": ["Palpa", "Llipata", "Río Grande", "Santa Cruz", "Tibillo"],
        "Pisco": ["Pisco", "Huancano", "Humay", "Independencia", "Paracas", "San Andrés", "San Clemente", "Túpac Amaru Inca"]
    },
    "Junín": {
        "Huancayo": ["Huancayo", "Carhuacallanga", "Chacapampa", "Chicche", "Chilca", "Chongos Alto", "Chupuro", "El Tambo", "Huacrapuquio", "Hualhuas", "Huancán", "Huasicancha", "Huayucachi", "Ingenio", "Pariahuanca", "Pilcomayo", "Pucará", "Quichuay", "Quilcas", "San Agustín", "San Jerónimo de Tunán", "Saño", "Sapallanga", "Sicaya", "Santo Domingo de Acobamba", "Viques"],
        "Concepción": ["Concepción", "Aco", "Andamarca", "Chambara", "Cochas", "Comas", "Heroínas Toledo", "Manzanares", "Mariscal Castilla", "Matahuasi", "Mito", "Nueve de Julio", "Orcotuna", "San José de Quero", "Santa Rosa de Ocopa"],
        "Chanchamayo": ["Chanchamayo", "Perené", "Pichanaqui", "San Luis de Shuaro", "San Ramón", "Vitoc"],
        "Jauja": ["Jauja", "Acolla", "Apata", "Aquel", "Canchayllo", "Curicaca", "El Mantaro", "Huamali", "Huaripampa", "Huertas", "Janjaillo", "Jauja", "Julcán", "Leonor Ordóñez", "Llocllapampa", "Marco", "Masma", "Masma Chicche", "Molinos", "Monobamba", "Muqui", "Muquiyauyo", "Paca", "Pancán", "Parco", "Pomeracancha", "Ricrán", "San Lorenzo", "San Pedro de Chunán", "Sausa", "Sincos", "Tunan Marca", "Yauli", "Yauyos"],
        "Junín": ["Junín", "Carhuamayo", "Ondores", "Ulcumayo"],
        "Satipo": ["Satipo", "Coviriali", "Llaylla", "Mazamari", "Pampa Hermosa", "Pangoa", "Río Negro", "Río Tambo", "Vizcatán del Ene"],
        "Tarma": ["Tarma", "Acobamba", "Huaricolca", "Huasahuasi", "La Unión", "Palca", "Palcamayo", "San Pedro de Cajas", "Tapo"],
        "Yauli": ["La Oroya", "Chacapalpa", "Huay-Huay", "Marcapomacocha", "Morococha", "Paccha", "Santa Bárbara de Carhuacayán", "Santa Rosa de Sacco", "Suitucancha", "Yauli"],
        "Chupaca": ["Chupaca", "Ahuac", "Chongos Bajo", "Huachac", "Huamancaca Chico", "San Juan de Iscos", "San Juan de Jarpa", "Tres de Diciembre", "Yanacancha"]
    },
    "La Libertad": {
        "Trujillo": ["Trujillo", "El Porvenir", "Florencia de Mora", "Huanchaco", "La Esperanza", "Laredo", "Moche", "Poroto", "Salaverry", "Simbal", "Víctor Larco Herrera"],
        "Ascope": ["Ascope", "Chicama", "Chocope", "Magdalena de Cao", "Paiján", "Rázuri", "Santiago de Cao", "Casa Grande"],
        "Bolívar": ["Bolívar", "Bambamarca", "Condormarca", "Longotea", "Uchumarca", "Uchucmarca"],
        "Chepén": ["Chepén", "Pacanga", "Pueblo Nuevo"],
        "Gran Chimú": ["Cascas", "Lucma", "Marmot", "Sayapullo"],
        "Julcán": ["Julcán", "Calamarca", "Carabamba", "Huaso"],
        "Otuzco": ["Otuzco", "Agallpampa", "Charat", "Huaranchal", "La Cuesta", "Mache", "Paranday", "Salpo", "Sinsicap", "Usquil"],
        "Pacasmayo": ["San Pedro de Lloc", "Guadalupe", "Jequetepeque", "Pacasmayo", "San José"],
        "Pataz": ["Tayabamba", "Buldibuyo", "Chillia", "Huancaspata", "Huaylillas", "Huayo", "Ongón", "Paroy", "Pataz", "Pías", "Santiago de Challas", "Taurija", "Urpay"],
        "Sánchez Carrión": ["Huamachuco", "Chugay", "Cochorco", "Curgos", "Marcabal", "Sanagorán", "Sartimbamba", "Sarin"],
        "Santiago de Chuco": ["Santiago de Chuco", "Angasmarca", "Cachicadan", "Mollebamba", "Mollepata", "Quiruvilca", "Santa Cruz de Chuca", "Sitabamba"],
        "Virú": ["Virú", "Chao", "Guadalupito"]
    },
    "Lambayeque": {
        "Chiclayo": ["Chiclayo", "Chongoyape", "Eten", "Eten Puerto", "José Leonardo Ortiz", "La Victoria", "Lagunas", "Monsefú", "Nueva Arica", "Oyotún", "Patsapo", "Pimentel", "Reque", "Santa Rosa", "Saña", "Cayaltí", "Pátapo", "Pomalca", "Pucalá", "Tumán"],
        "Ferreñafe": ["Ferreñafe", "Cañaris", "Incahuasi", "Manuel Antonio Mesones Muro", "Pítipo", "Pueblo Nuevo"],
        "Lambayeque": ["Lambayeque", "Chochope", "Íllimo", "Jayanca", "Mochumí", "Mórrope", "Motupe", "Olmos", "Pacora", "Salas", "San José", "Túcume"]
    },
    "Lima": {
        "Lima": ["Cercado de Lima", "Ancón", "Ate", "Barranco", "Breña", "Carabayllo", "Chaclacayo", "Chorrillos", "Cieneguilla", "Comas", "El Agustino", "Independencia", "Jesús María", "La Molina", "La Victoria", "Lince", "Los Olivos", "Lurigancho-Chosica", "Lurín", "Magdalena del Mar", "Miraflores", "Pachacámac", "Pucusana", "Pueblo Libre", "Puente Piedra", "Punta Hermosa", "Punta Negra", "Rímac", "San Bartolo", "San Borja", "San Isidro", "San Juan de Lurigancho", "San Juan de Miraflores", "San Luis", "San Martín de Porres", "San Miguel", "Santa Anita", "Santa María del Mar", "Santa Rosa", "Santiago de Surco", "Surquillo", "Villa El Salvador", "Villa María del Triunfo"],
        "Barranca": ["Barranca", "Paramonga", "Pativilca", "Supe", "Supe Puerto"],
        "Cajatambo": ["Cajatambo", "Copa", "Gorgor", "Huancapón", "Manás"],
        "Canta": ["Canta", "Arahuay", "Huamantanga", "Huaros", "Lachaqui", "San Buenaventura", "Santa Rosa de Quives"],
        "Cañete": ["San Vicente de Cañete", "Asia", "Calango", "Cerro Azul", "Chilca", "Coayllo", "Imperial", "Lunahuaná", "Mala", "Nuevo Imperial", "Pacarán", "Quilmaná", "San Antonio", "San Luis", "Santa Cruz de Flores"],
        "Huaral": ["Huaral", "Atavillos Alto", "Atavillos Bajo", "Aucallama", "Chancay", "Ihuarí", "Lampián", "Pacaraos", "San Miguel de Acos", "Santa Cruz de Andamarca", "Sumbilca", "Veintisiete de Noviembre"],
        "Huarochirí": ["Matucana", "Antioquía", "Callahuanca", "Carampoma", "Chicla", "Cuenca", "Huachupampa", "Huanza", "Huarochirí", "Lahuaytambo", "Langa", "Laraos", "Mariatana", "Ricardo Palma", "San Andrés de Tupicocha", "San Antonio", "San Bartolomé", "San Mateo", "San Mateo de Otao", "San Pedro de Casta", "San Pedro de Huancayre", "Sangallaya", "Santa Cruz de Cocachacra", "Santa Eulalia", "Santiago de Anchucaya", "Santiago de Tuna", "Santo Domingo de los Olleros", "Surco"],
        "Huaura": ["Huacho", "Ambar", "Caleta de Carquín", "Checras", "Hualmay", "Huaura", "Leoncio Prado", "Paccho", "Santa Leonor", "Santa María", "Sayán", "Végueta"],
        "Oyón": ["Oyón", "Andajes", "Caujul", "Cochamarca", "Naván", "Pachangara"],
        "Yauyos": ["Yauyos", "Alis", "Ayauca", "Ayavirí", "Azángaro", "Cacra", "Carania", "Catahuasi", "Chacos", "Chupamarca", "Colonia", "Cochas", "Huampara", "Huancaya", "Huangáscar", "Huantán", "Huañec", "Laraos", "Lincha", "Madean", "Miraflores", "Omas", "Putinja", "Quinches", "Quinocay", "San Joaquín", "San Pedro de Pilas", "Tanta", "Tauripampa", "Tomas", "Tupe", "Viñac", "Vitis"]
    },
    "Loreto": {
        "Maynas": ["Iquitos", "Alto Nanay", "Fernando Lores", "Indiana", "Las Amazonas", "Mazan", "Napo", "Punchana", "Torres Causana", "Belén", "San Juan Bautista"],
        "Alto Amazonas": ["Yurimaguas", "Balsapuerto", "Jeberos", "Lagunas", "Santa Cruz", "Teniente César López Rojas"],
        "Loreto": ["Nauta", "Parinari", "Tigre", "Trompeteros", "Urarinas"],
        "Mariscal Ramón Castilla": ["Ramón Castilla", "Pebas", "Yavari", "San Pablo"],
        "Requena": ["Requena", "Alto Tapiche", "Capelo", "Emilio San Martín", "Maquía", "Puinahua", "Saquena", "Soplin", "Tapiche", "Yaquerana"],
        "Ucayali": ["Contamana", "Inahuaya", "Padre Márquez", "Pampa Hermosa", "Sarayacu", "Vargas Guerra"],
        "Datem del Marañón": ["San Lorenzo", "Barranca", "Cahuapanas", "Manseriche", "Morona", "Pastaza"],
        "Putumayo": ["Putumayo", "Rosa Panduro", "Teniente Manuel Clavero", "Yaguas"]
    },
    "Madre de Dios": {
        "Tambopata": ["Puerto Maldonado", "Inambari", "Las Piedras", "Laberinto"],
        "Manu": ["Manu", "Fitzcarrald", "Madre de Dios", "Huepetuhe"],
        "Tahuamanu": ["Iñapari", "Iberia", "Tahuamanu"]
    },
    "Moquegua": {
        "Mariscal Nieto": ["Moquegua", "Carumas", "Cuchumbaya", "Samegua", "San Cristóbal", "Torata"],
        "General Sánchez Cerro": ["Omate", "Chojata", "Coalaque", "Ichuña", "La Capilla", "Lloque", "Matalaque", "Puquina", "Quinistaquillas", "Ubinas", "Yunga"],
        "Ilo": ["Ilo", "El Algarrobal", "Pacocha"]
    },
    "Pasco": {
        "Pasco": ["Chaupimarca", "Huachón", "Huariaca", "Huayllay", "Ninacaca", "Oxapampa", "Paucartambo", "San Francisco de Asís de Yarusyacán", "Simon Bolívar", "Ticlacayán", "Tinyahuarco", "Vicco", "Yanacancha"],
        "Daniel Alcides Carrión": ["Yanahuanca", "Chacayán", "Goyllarisquizga", "Paucar", "San Pedro de Pillao", "Santa Ana de Tusi", "Tapuc", "Vilcabamba"],
        "Oxapampa": ["Oxapampa", "Chontabamba", "Huancabamba", "Palcazu", "Pozuzo", "Puerto Bermúdez", "Villa Rica", "Constitución"]
    },
    "Piura": {
        "Piura": ["Piura", "Castilla", "Catacaos", "Cura Mori", "El Taller", "La Arena", "La Unión", "Las Lomas", "Tambo Grande", "Veintiséis de Octubre"],
        "Ayabaca": ["Ayabaca", "Frías", "Jililí", "Lagunas", "Montero", "Pacaipampa", "Paimas", "Sapillica", "Sicchez", "Suyo"],
        "Huancabamba": ["Huancabamba", "Canchaque", "El Carmen de la Frontera", "Huarmaca", "Lalaquiz", "San Miguel de El Faique", "Sondor", "Sondorillo"],
        "Morropón": ["Chulucanas", "Buenos Aires", "Chalaco", "La Matanza", "Morropón", "Salitral", "San Juan de Bigote", "Santa Catalina de Mossa", "Santo Domingo", "Yamango"],
        "Paita": ["Paita", "Amotape", "Arenal", "Colán", "La Huaca", "Tamarindo", "Vichayal"],
        "Sullana": ["Sullana", "Bellavista", "Ignacio Escudero", "Lancones", "Marcavelica", "Miguel Checa", "Querecotillo", "Salitral"],
        "Talara": ["Pariñas", "El Alto", "La Brea", "Lobitos", "Máncora", "Los Órganos"],
        "Sechura": ["Sechura", "Bellavista de la Unión", "Bernal", "Cristo Nos Valga", "Rincón de la Bahía", "Vice"]
    },
    "Puno": {
        "Puno": ["Puno", "Acora", "Amantaní", "Atuncolla", "Capachica", "Chucuito", "Coata", "Huata", "Mañazo", "Paucarcolla", "Pichacani", "Platería", "San Antonio", "Tiquillaca", "Vilque"],
        "Azángaro": ["Azángaro", "Arapa", "Asillo", "Caminaca", "Chupa", "José Domingo Choquehuanca", "Muñani", "Potoni", "Saman", "San Antón", "San José", "San Juan de Salinas", "Santiago de Pupuja", "Tirapata"],
        "Carabaya": ["Macusani", "Ajoyani", "Ayapata", "Coasa", "Corani", "Crucero", "Ituata", "Ollachea", "San Gabán", "Usicayos"],
        "Chucuito": ["Juli", "Desaguadero", "Huacullani", "Kelluyo", "Pisacoma", "Pomata", "Zepita"],
        "El Collao": ["Ilave", "Capazo", "Pilcuyo", "Santa Rosa", "Conduriri"],
        "Huancané": ["Huancané", "Cojata", "Huatasani", "Inchupalla", "Pusi", "Rosaspata", "Taraco", "Vilque Chico"],
        "Lampa": ["Lampa", "Cabanilla", "Calapuja", "Nicasio", "Ocuviri", "Palca", "Paratía", "Pucará", "Santa Lucía", "Vilavila"],
        "Melgar": ["Ayaviri", "Antauta", "Cupi", "Llalli", "Macari", "Nuñoa", "Orurillo", "Santa Rosa", "Umachiri"],
        "Moho": ["Moho", "Conima", "Huayrapata", "Tilali"],
        "San Antonio de Putina": ["Putina", "Ananea", "Pedro Vilca Apaza", "Quilcapuncu", "Sina"],
        "San Román": ["Juliaca", "Cabana", "Cabanillas", "Caracoto"],
        "Sandia": ["Sandia", "Cuyocuyo", "Limbani", "Patambuco", "Phara", "Quiaca", "San Juan del Oro", "Yanahuaya", "Alto Inambari", "San Pedro de Putina Punco"],
        "Yunguyo": ["Yunguyo", "Anapia", "Copani", "Cuturapi", "Ollaraya", "Tinicachi", "Unicachi"]
    },
    "San Martín": {
        "Moyobamba": ["Moyobamba", "Calzada", "Habana", "Jepelacio", "Soritor", "Yantaló"],
        "Bellavista": ["Bellavista", "Alto Biavo", "Bajo Biavo", "Huallaga", "San Rafael", "San Pablo"],
        "El Dorado": ["San José de Sisa", "Agua Blanca", "San Martín", "Santa Rosa", "Shatoja"],
        "Huallaga": ["Saposoa", "Alto Saposoa", "El Eslabón", "Piscoyacu", "Sacanche", "Tingo de Saposoa"],
        "Lamas": ["Lamas", "Alonso de Alvarado", "Barranquita", "Caynarachi", "Cuñumbuqui", "Pinto Recodo", "Rumisapa", "San Roque de Cumbaza", "Shanao", "Tabalosos", "Zapatero"],
        "Mariscal Cáceres": ["Juanjuí", "Campanilla", "Huicungo", "Pachiza", "Pajarillo"],
        "Picota": ["Picota", "Buenos Aires", "Caspizapa", "Pilluana", "Pucaraca", "San Cristóbal", "San Hilarión", "Shamboyacu", "Tingo de Ponasa", "Tres Unidos"],
        "Rioja": ["Rioja", "Awajún", "Elias Soplín Vargas", "Nueva Cajamarca", "Pardo Miguel", "Posic", "San Fernando", "Yorongos", "Yuracyacu"],
        "San Martín": ["Tarapoto", "Alberto Leveau", "Catorce de Mayo", "Chazuta", "Chipurana", "El Porvenir", "Huimbayoc", "Juan Guerra", "La Banda de Shilcayo", "Morales", "Papaplaya", "San Antonio", "Sauce", "Shapaja"],
        "Tocache": ["Tocache", "Nuevo Progreso", "Pólvora", "Shunté", "Uchiza"]
    },
    "Tacna": {
        "Tacna": ["Tacna", "Alto de la Alianza", "Calana", "Ciudad Nueva", "Inclán", "Pachía", "Palca", "Pocollay", "Sama", "Coronel Gregorio Albarracín Lanchipa", "La Yarada-Los Palos"],
        "Candarave": ["Candarave", "Cairani", "Camilaca", "Curibaya", "Huanuara", "Quilahuani"],
        "Jorge Basadre": ["Locumba", "Ilabaya", "Ite"],
        "Tarata": ["Tarata", "Chucatamani", "Estique", "Estique-Pampa", "Sitajara", "Susapaya", "Tarucachi", "Ticaco"]
    },
    "Tumbes": {
        "Tumbes": ["Tumbes", "Corrales", "La Cruz", "Pampas de Hospital", "San Jacinto", "San Juan de la Virgen"],
        "Contralmirante Villar": ["Zorritos", "Canoas de Punta Sal", "Casitas"],
        "Zarumilla": ["Zarumilla", "Aguas Verdes", "Matapalo", "Papayal"]
    },
    "Ucayali": {
        "Coronel Portillo": ["Pucallpa", "Campoverde", "Iparía", "Masisea", "Yarinacocha", "Nueva Requena", "Manantay"],
        "Atalaya": ["Raimondi", "Sepahua", "Tahuanía", "Yurúa"],
        "Padre Abad": ["Aguaytía", "Irazola", "Curimaná", "Neshuya", "Alexander von Humboldt"],
        "Purús": ["Esperanza"]
    }
};

        const selectPais = document.getElementById('select-pais');
        const selectDep = document.getElementById('select-depa');
        const selectProv = document.getElementById('select-prov');
        const selectDist = document.getElementById('select-dist');
        const contenedorUbigeo = document.getElementById('contenedor-ubigeo');
        const contenedorExtranjero = document.getElementById('contenedor-extranjero');

        function manejarCambioPais() {
            const paisSelected = selectPais.value;

            if (paisSelected === 'PE') {
                contenedorUbigeo.classList.remove('d-none');
                contenedorExtranjero.classList.add('d-none');
                cargarDepartamentos();
            } else if (paisSelected !== '') {
                contenedorUbigeo.classList.add('d-none');
                contenedorExtranjero.classList.remove('d-none');
            } else {
                contenedorUbigeo.classList.add('d-none');
                contenedorExtranjero.classList.add('d-none');
            }
        }

        function cargarDepartamentos() {
            resetSelect(selectDep, '-- Seleccionar --');
            resetSelect(selectProv, '-- Seleccionar --');
            resetSelect(selectDist, '-- Seleccionar --');
            selectDep.disabled = false;
            selectProv.disabled = true;
            selectDist.disabled = true;

            Object.keys(ubigeoData).forEach(dep => {
                let option = document.createElement('option');
                option.value = dep;
                option.textContent = dep;
                selectDep.appendChild(option);
            });
        }

        selectPais.addEventListener('change', manejarCambioPais);

        // Cambiar Departamento -> Cargar Provincias
        selectDep.addEventListener('change', function() {
            resetSelect(selectProv, '-- Seleccionar --');
            resetSelect(selectDist, '-- Seleccionar --');
            selectDist.disabled = true;

            const depSeleccionado = this.value;
            if (depSeleccionado && ubigeoData[depSeleccionado]) {
                selectProv.disabled = false;
                Object.keys(ubigeoData[depSeleccionado]).forEach(prov => {
                    let option = document.createElement('option');
                    option.value = prov;
                    option.textContent = prov;
                    selectProv.appendChild(option);
                });
            } else {
                selectProv.disabled = true;
            }
        });

        // Cambiar Provincia -> Cargar Distritos
        selectProv.addEventListener('change', function() {
            resetSelect(selectDist, '-- Seleccionar --');

            const depSeleccionado = selectDep.value;
            const provSeleccionada = this.value;

            if (depSeleccionado && provSeleccionada && ubigeoData[depSeleccionado][provSeleccionada]) {
                selectDist.disabled = false;
                ubigeoData[depSeleccionado][provSeleccionada].forEach(dist => {
                    let option = document.createElement('option');
                    option.value = dist;
                    option.textContent = dist;
                    selectDist.appendChild(option);
                });
            } else {
                selectDist.disabled = true;
            }
        });

        function resetSelect(selectElement, defaultText) {
            if (selectElement) {
                selectElement.innerHTML = `<option value="">${defaultText}</option>`;
            }
        }

        // Ejecutar al cargar la vista
        manejarCambioPais();
    });
    </script>
</x-app-layout>