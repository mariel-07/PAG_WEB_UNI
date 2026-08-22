<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0 fw-bold text-uni">
            <i class="fas fa-tachometer-alt me-2"></i>Panel Principal de Gestión Docente
        </h4>
    </x-slot>

    <div class="container py-3">
        <!-- Banner de Bienvenida -->
        <div class="card bg-uni text-white shadow-sm mb-4">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="fw-bold mb-1">Bienvenido(a), {{ Auth::user()->name ?? 'Docente' }}</h3>
                    <p class="mb-0 text-white-50">Facultad de Ingeniería Mecánica - Universidad Nacional de Ingeniería</p>
                </div>
                <i class="fas fa-graduation-cap fa-4x opacity-25 d-none d-md-block"></i>
            </div>
        </div>

        <!-- Módulos Principales -->
        <div class="row g-4">
            <!-- Tarjeta 1: Información Docente -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="bg-light text-uni rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                            <i class="fas fa-id-card fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark">Información Docente</h5>
                        <p class="card-text text-muted small">Gestión del legajo personal, residencia, Ubigeo y formación académica profesional.</p>
                        <a href="{{ route('docente.informacion') }}" class="btn btn-uni w-100 mt-2">
                            Ingresar al Módulo <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Asistencia Docente -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="bg-light text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark">Asistencia Docente</h5>
                        <p class="card-text text-muted small">Control de asistencia de clases, vista semanal interactiva y paso de lista de alumnos.</p>
                        <a href="{{ route('docente.asistencia') }}" class="btn btn-uni w-100 mt-2">
                            Ingresar al Módulo <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Horario Docente -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="bg-light text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark">Horario Docente</h5>
                        <p class="card-text text-muted small">Consulta la grilla de clases semanales y estadísticas de dictado de horas lectivas.</p>
                        <a href="{{ route('docente.horario') }}" class="btn btn-uni w-100 mt-2">
                            Ingresar al Módulo <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>