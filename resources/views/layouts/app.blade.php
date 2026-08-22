<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Académico - UNI') }}</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts: Inter / Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        /* Estilos Institucionales UNI (Granate / Guinda) */
        .bg-uni { background-color: #8B0000 !important; color: #ffffff !important; }
        .text-uni { color: #8B0000 !important; }
        .btn-uni {
            background-color: #8B0000;
            color: #fff;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-uni:hover {
            background-color: #660000;
            color: #fff;
            box-shadow: 0 4px 10px rgba(139, 0, 0, 0.3);
        }
        .card {
            border: none;
            border-radius: 10px;
        }
        .navbar-uni {
            background-color: #700000;
            border-bottom: 3px solid #d4af37; /* Detalle dorado UNI */
        }
    </style>
</head>
<body class="bg-light">
    <!-- Barra Superior de Navegación Institucional -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-uni shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <i class="fas fa-university"></i>
                <span>FIM UNI - Portal Docente</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link text-white fw-semibold" href="{{ route('dashboard') }}">
                            <i class="fas fa-th-large me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-bold d-flex align-items-center gap-2" href="#" id="userDrop" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg"></i>
                            {{ Auth::user()->name ?? 'Docente' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Encabezado Secundario -->
    @if (isset($header))
        <header class="bg-white shadow-sm py-3 mb-4 border-bottom">
            <div class="container">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Contenido Principal -->
    <main class="py-2">
        {{ $slot }}
    </main>

    <!-- Bootstrap 5 JS Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>