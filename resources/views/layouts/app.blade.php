<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Docente - UNI') }}</title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- jQuery (Requerido para Select2 y utilidades) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Select2 CSS y Tema Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    {{-- html2pdf --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    @livewireStyles

    <style>

        /* =====================================================
           VARIABLES
        ===================================================== */

        :root {
            --uni-maroon: #7A1015;
            --uni-maroon-dark: #560A0D;
            --uni-maroon-light: #F8EEF0;

            --uni-gold: #C6A15B;
            --uni-gold-light: #F3E9D1;

            --uni-blue: #173F67;
            --uni-blue-dark: #102F4D;

            --uni-bg: #F4F6F8;
            --uni-white: #FFFFFF;

            --text-dark: #1F2937;
            --text-medium: #526170;
            --text-light: #8A96A3;

            --border: #E3E7EB;

            --shadow: 0 8px 25px rgba(16, 47, 77, .07);
        }

        /* Personalización de Select2 para el tema institucional */
        .select2-container--bootstrap-5 .select2-dropdown .select2-results__option--highlighted[aria-selected] {
            background-color: var(--uni-maroon, #7A1015) !important;
            color: #ffffff !important;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: var(--uni-maroon-light, #F8EEF0) !important;
            border: 1px solid var(--uni-maroon, #7A1015) !important;
            color: var(--uni-maroon-dark, #560A0D) !important;
            font-size: 0.85rem !important;
            padding: 3px 8px !important;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: var(--uni-maroon, #7A1015) !important;
        }


        /* =====================================================
           GENERAL
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--uni-bg);
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
        }


        /* =====================================================
           TOPBAR
        ===================================================== */

        .uni-topbar {
            height: 72px;
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1000;
        }

        .topbar-container {
            width: 100%;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }


        /* LOGO */

        .uni-logo {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .uni-logo-symbol {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: var(--uni-maroon);
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            position: relative;
            overflow: hidden;

            box-shadow: 0 4px 10px rgba(122, 16, 21, .20);
        }

        .uni-logo-symbol::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--uni-gold);
        }

        .uni-logo-symbol i {
            font-size: 18px;
        }

        .uni-logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .uni-logo-text strong {
            color: var(--uni-blue-dark);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .uni-logo-text span {
            color: var(--text-light);
            font-size: 9px;
            font-weight: 600;
            margin-top: 3px;
        }


        /* TOP USER */

        .top-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-user-info {
            text-align: right;
            display: flex;
            flex-direction: column;
        }

        .top-user-info strong {
            color: var(--uni-blue-dark);
            font-size: 11px;
            font-weight: 700;
        }

        .top-user-info span {
            color: var(--text-light);
            font-size: 9px;
            margin-top: 2px;
        }

        .top-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;

            background: var(--uni-maroon-light);
            color: var(--uni-maroon);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;
            border: 1px solid #ECD9DB;
        }

        .top-logout {
            border: none;
            background: transparent;
            color: var(--text-light);

            width: 34px;
            height: 34px;

            border-radius: 6px;

            cursor: pointer;
            transition: .2s;
        }

        .top-logout:hover {
            color: var(--uni-maroon);
            background: var(--uni-maroon-light);
        }


        /* =====================================================
           MAIN LAYOUT
        ===================================================== */

        .uni-layout {
            display: flex;
            min-height: calc(100vh - 72px);
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .uni-sidebar {
            width: 250px;
            min-width: 250px;

            background: #ffffff;
            border-right: 1px solid var(--border);

            display: flex;
            flex-direction: column;

            position: sticky;
            top: 0;

            height: calc(100vh - 72px);
        }


        /* SIDEBAR HEADER */

        .sidebar-header {
            padding: 26px 22px 22px;
        }

        .sidebar-faculty {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .faculty-icon {
            width: 38px;
            height: 38px;

            background: var(--uni-gold-light);
            color: var(--uni-maroon);

            border-radius: 8px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 15px;
        }

        .faculty-name {
            display: flex;
            flex-direction: column;
        }

        .faculty-name strong {
            color: var(--uni-blue-dark);
            font-size: 12px;
            font-weight: 800;
        }

        .faculty-name span {
            color: var(--text-light);
            font-size: 8px;
            font-weight: 600;
            margin-top: 3px;
        }


        /* DIVIDER */

        .sidebar-divider {
            height: 1px;
            background: var(--border);
            margin: 0 20px 20px;
        }


        /* SECTION */

        .sidebar-section {
            padding: 0 20px 9px;

            color: #9AA4AE;

            font-size: 8px;
            font-weight: 800;

            letter-spacing: 1.2px;
        }


        /* NAVIGATION */

        .sidebar-navigation {
            padding: 0 12px;
        }

        .sidebar-link {
            height: 45px;

            display: flex;
            align-items: center;

            gap: 12px;

            padding: 0 12px;

            margin-bottom: 5px;

            border-radius: 8px;

            color: #657484;

            font-size: 10px;
            font-weight: 600;

            transition: all .2s ease;

            position: relative;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 13px;
        }

        .sidebar-link:hover {
            background: #FAF4F4;
            color: var(--uni-maroon);
        }

        .sidebar-link.active {
            background: var(--uni-maroon-light);
            color: var(--uni-maroon);
            font-weight: 700;
        }

        .sidebar-link.active::before {
            content: "";

            position: absolute;

            left: 0;
            top: 9px;
            bottom: 9px;

            width: 3px;

            background: var(--uni-maroon);

            border-radius: 0 3px 3px 0;
        }


        /* SIDEBAR BOTTOM */

        .sidebar-bottom {
            margin-top: auto;

            padding: 18px;

            border-top: 1px solid var(--border);
        }

        .sidebar-status {
            padding: 12px;

            border-radius: 8px;

            background: #F7F9FA;

            border: 1px solid var(--border);
        }

        .status-title {
            display: flex;
            align-items: center;
            gap: 7px;

            color: #4B5563;

            font-size: 9px;
            font-weight: 700;
        }

        .status-dot {
            width: 7px;
            height: 7px;

            background: #2D8A50;

            border-radius: 50%;
        }

        .status-text {
            margin-top: 5px;

            color: var(--text-light);

            font-size: 8px;
        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .uni-content {
            flex: 1;
            min-width: 0;

            padding: 30px 38px 40px;
        }

        .content-container {
            max-width: 1180px;
            margin: 0 auto;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 900px) {

            .uni-sidebar {
                width: 220px;
                min-width: 220px;
            }

            .uni-content {
                padding: 25px;
            }

            .topbar-container {
                padding: 0 20px;
            }
        }


        @media (max-width: 700px) {

            .uni-topbar {
                height: 64px;
            }

            .uni-layout {
                min-height: calc(100vh - 64px);
            }

            .uni-sidebar {
                display: none;
            }

            .uni-content {
                padding: 20px 15px;
            }

            .top-user-info {
                display: none;
            }

            .uni-logo-text span {
                display: none;
            }

            .uni-logo-text strong {
                font-size: 12px;
            }
        }


        /* =====================================================
           PDF
        ===================================================== */

        .modo-pdf .btn,
        .modo-pdf button,
        .modo-pdf .top-logout {
            display: none !important;
        }

        .modo-pdf .card {
            box-shadow: none !important;
            page-break-inside: avoid;
        }

        .modo-pdf input,
        .modo-pdf select,
        .modo-pdf textarea {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }

    </style>
</head>


<body>

    {{-- =====================================================
         BARRA SUPERIOR
    ====================================================== --}}

    <header class="uni-topbar">

        <div class="topbar-container">

            {{-- LOGO --}}
            <div class="uni-logo">

                <div class="uni-logo-symbol">
                    <i class="fas fa-university"></i>
                </div>

                <div class="uni-logo-text">

                    <strong>
                        UNIVERSIDAD NACIONAL DE INGENIERÍA
                    </strong>

                    <span>
                        Facultad de Ingeniería Mecánica · Portal Docente
                    </span>

                </div>

            </div>


            {{-- USUARIO --}}
            @auth

                <div class="top-user">

                    <div class="top-user-info">

                        <strong>
                            {{ Auth::user()->name }}
                        </strong>

                        <span>
                            Personal docente
                        </span>

                    </div>

                    <div class="top-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="top-logout"
                            title="Cerrar sesión"
                        >
                            <i class="fas fa-right-from-bracket"></i>
                        </button>

                    </form>

                </div>

            @endauth

        </div>

    </header>


    {{-- =====================================================
         LAYOUT
    ====================================================== --}}

    <div class="uni-layout">

        {{-- SIDEBAR --}}
        <aside class="uni-sidebar">

            <div class="sidebar-header">

                <div class="sidebar-faculty">

                    <div class="faculty-icon">
                        <i class="fas fa-building-columns"></i>
                    </div>

                    <div class="faculty-name">

                        <strong>
                            FIM
                        </strong>

                        <span>
                            FACULTAD DE INGENIERÍA MECÁNICA
                        </span>

                    </div>

                </div>

            </div>


            <div class="sidebar-divider"></div>


            <div class="sidebar-section">
                GESTIÓN ACADÉMICA
            </div>


            <nav class="sidebar-navigation">

                <a
                    href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    <i class="fas fa-house"></i>
                    <span>Panel principal</span>
                </a>


                <a
                    href="{{ route('docente.horario') }}"
                    class="sidebar-link {{ request()->routeIs('docente.horario') ? 'active' : '' }}"
                >
                    <i class="fas fa-calendar-days"></i>
                    <span>Horario docente</span>
                </a>


                <a
                    href="{{ route('docente.asistencia') }}"
                    class="sidebar-link {{ request()->routeIs('docente.asistencia') ? 'active' : '' }}"
                >
                    <i class="fas fa-calendar-check"></i>
                    <span>Asistencia docente</span>
                </a>


                <a
                    href="{{ route('docente.informacion') }}"
                    class="sidebar-link {{ request()->routeIs('docente.informacion') ? 'active' : '' }}"
                >
                    <i class="fas fa-id-card"></i>
                    <span>Información docente</span>
                </a>

                <a
                    href="{{ route('messages.inbox') }}"
                    class="sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}"
                >
                    <i class="fas fa-envelope"></i>
                    <span>Mensajería</span>
                    @if(auth()->check() && auth()->user()->unreadMessagesCount() > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.75rem;">
                            {{ auth()->user()->unreadMessagesCount() }}
                        </span>
                    @endif
                </a>

            </nav>


            {{-- ESTADO --}}
            <div class="sidebar-bottom">

                <div class="sidebar-status">

                    <div class="status-title">

                        <span class="status-dot"></span>

                        Sistema operativo

                    </div>

                    <div class="status-text">
                        Portal docente disponible
                    </div>

                </div>

            </div>

        </aside>


        {{-- CONTENIDO --}}
        <main class="uni-content">

            <div class="content-container">

                {{ $slot }}

            </div>

        </main>

    </div>


    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Select2 JS + Traducción al Español --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>

    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal:alert', (data) => {
                const eventData = Array.isArray(data) ? data[0] : data;
                if (window.Swal) {
                    Swal.fire({
                        icon: eventData.type || 'success',
                        title: eventData.title || '',
                        text: eventData.text || '',
                        confirmButtonColor: '#7A1015',
                    });
                }
            });
        });
    </script>

</body>

</html>