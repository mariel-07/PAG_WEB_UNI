<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Horario Docente') }}
        </h2>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container py-4">
        <!-- Tarjeta de Gráficos de Horas Dictadas -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="fas fa-chart-bar me-2"></i>Horas de Dictado Semanal por Curso
                    </div>
                    <div class="card-body">
                        <canvas id="chartHorasSemanales" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="fas fa-chart-pie me-2"></i>Distribución de Carga Horaria
                    </div>
                    <div class="card-body">
                        <canvas id="chartDistribucionHoras" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla del Horario Semanal -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>Horario Semanal de Clases</h5>
                <button class="btn btn-light btn-sm fw-bold" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Imprimir Horario
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Hora</th>
                                <th>Lunes</th>
                                <th>Martes</th>
                                <th>Miércoles</th>
                                <th>Jueves</th>
                                <th>Viernes</th>
                                <th>Sábado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold bg-light">08:00 - 10:00</td>
                                <td>-</td>
                                <td class="bg-success text-white rounded">
                                    <strong>MB545-C</strong><br><small>CCLAB-1D</small>
                                </td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">10:00 - 12:00</td>
                                <td>-</td>
                                <td class="bg-success text-white rounded">
                                    <strong>MB545-C</strong><br><small>CCLAB-1D</small>
                                </td>
                                <td>-</td>
                                <td class="bg-primary text-white rounded">
                                    <strong>MB545-D</strong><br><small>CCLAB-1E</small>
                                </td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">13:00 - 16:00</td>
                                <td>-</td>
                                <td class="bg-info text-dark rounded">
                                    <strong>MB545-P</strong><br><small>CCLAB-2A</small>
                                </td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">19:00 - 22:00</td>
                                <td>-</td>
                                <td>-</td>
                                <td class="bg-purple text-white rounded" style="background-color: #6f42c1;">
                                    <strong>MB545-I</strong><br><small>CCLAB-2A</small>
                                </td>
                                <td class="bg-purple text-white rounded" style="background-color: #6f42c1;">
                                    <strong>MB545-I</strong><br><small>CCLAB-2A</small>
                                </td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE INICIALIZACIÓN DE GRÁFICOS (Chart.js) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Gráfico de Barras - Horas Semanales
            const ctxBarras = document.getElementById('chartHorasSemanales').getContext('2d');
            new Chart(ctxBarras, {
                type: 'bar',
                data: {
                    labels: ['MB545-C', 'MB545-D', 'MB545-P', 'MB545-I'],
                    datasets: [{
                        label: 'Horas Semanales',
                        data: [4, 2, 3, 6],
                        backgroundColor: ['#28a745', '#0d6efd', '#0dcaf0', '#6f42c1']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Horas' } }
                    }
                }
            });

            // 2. Gráfico de Pastel - Distribución de Carga
            const ctxPie = document.getElementById('chartDistribucionHoras').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Teoría', 'Práctica / Laboratorio'],
                    datasets: [{
                        data: [6, 9],
                        backgroundColor: ['#ffc107', '#0d6efd']
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
    </script>
</x-app-layout>