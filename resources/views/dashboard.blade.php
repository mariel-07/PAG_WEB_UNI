<x-app-layout>

    {{-- =====================================================
         DASHBOARD DOCENTE - FIM UNI
    ====================================================== --}}

    <div class="dashboard-page">

        {{-- =================================================
             ENCABEZADO
        ================================================== --}}
        <section class="dashboard-header">
            <div>
                <div class="breadcrumb-custom">
                    <span>
                        <i class="fas fa-house"></i>
                        Inicio
                    </span>
                    <i class="fas fa-chevron-right"></i>
                    <strong>
                        Panel principal
                    </strong>
                </div>

                <h1>
                    Panel principal
                </h1>
                <p>
                    Bienvenido al Sistema de Gestión Docente de la Facultad de Ingeniería Mecánica.
                </p>
            </div>

            <div class="header-date">
                <div class="date-icon">
                    <i class="far fa-calendar"></i>
                </div>
                <div>
                    <span>
                        PORTAL DOCENTE
                    </span>
                    <strong>
                        Gestión académica
                    </strong>
                </div>
            </div>
        </section>

        {{-- =================================================
             LÍNEA INSTITUCIONAL
        ================================================== --}}
        <div class="institution-line">
            <span></span>
        </div>

        {{-- =================================================
             BANNER
        ================================================== --}}
        <section class="welcome-card">
            <div class="welcome-content">
                <span class="welcome-tag">
                    <i class="fas fa-shield-halved"></i>
                    PORTAL DOCENTE · FIM
                </span>

                <h2>
                    Bienvenido(a),
                    <strong>{{ Auth::user()->name ?? 'Docente' }}</strong>
                </h2>

                <p>
                    Acceda rápidamente a sus herramientas de gestión académica,
                    horarios, asistencia e información docente.
                </p>

                <div class="welcome-institution">
                    <i class="fas fa-building-columns"></i>
                    <span>
                        Facultad de Ingeniería Mecánica ·
                        Universidad Nacional de Ingeniería
                    </span>
                </div>
            </div>

            {{-- ELEMENTO DECORATIVO --}}
            <div class="welcome-decoration">
                <div class="decoration-circle circle-one"></div>
                <div class="decoration-circle circle-two"></div>
                <div class="decoration-building">
                    <i class="fas fa-university"></i>
                </div>
            </div>
        </section>

        {{-- =================================================
             TÍTULO MÓDULOS
        ================================================== --}}
        <section class="modules-heading">
            <div>
                <span>
                    GESTIÓN ACADÉMICA
                </span>
                <h2>
                    Módulos docentes
                </h2>
                <p>
                    Seleccione el módulo que desea consultar o gestionar.
                </p>
            </div>
        </section>

        {{-- =================================================
             TARJETAS
        ================================================== --}}
        <section class="modules-grid">

            {{-- =================================================
                 HORARIO
            ================================================== --}}
            <a href="{{ route('docente.horario') }}" class="module-card">
                <div class="module-card-header">
                    <div class="module-icon">
                        <i class="fas fa-calendar-days"></i>
                    </div>
                    <span class="module-number">
                        01
                    </span>
                </div>

                <div class="module-content">
                    <span class="module-label">
                        PROGRAMACIÓN
                    </span>
                    <h3>
                        Horario docente
                    </h3>
                    <p>
                        Consulte sus cursos, aulas y horarios
                        correspondientes a la programación académica.
                    </p>
                </div>

                <div class="module-footer">
                    <span>
                        Ver horario
                    </span>
                    <div class="module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            {{-- =================================================
                 ASISTENCIA
            ================================================== --}}
            <a href="{{ route('docente.asistencia') }}" class="module-card">
                <div class="module-card-header">
                    <div class="module-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="module-number">
                        02
                    </span>
                </div>

                <div class="module-content">
                    <span class="module-label">
                        CONTROL ACADÉMICO
                    </span>
                    <h3>
                        Asistencia docente
                    </h3>
                    <p>
                        Registre y consulte la asistencia relacionada
                        con sus actividades académicas.
                    </p>
                </div>

                <div class="module-footer">
                    <span>
                        Gestionar asistencia
                    </span>
                    <div class="module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            {{-- =================================================
                 INFORMACIÓN
            ================================================== --}}
            <a href="{{ route('docente.informacion') }}" class="module-card">
                <div class="module-card-header">
                    <div class="module-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <span class="module-number">
                        03
                    </span>
                </div>

                <div class="module-content">
                    <span class="module-label">
                        PERFIL DOCENTE
                    </span>
                    <h3>
                        Información docente
                    </h3>
                    <p>
                        Consulte su información personal, académica
                        y profesional registrada en el sistema.
                    </p>
                </div>

                <div class="module-footer">
                    <span>
                        Ver información
                    </span>
                    <div class="module-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

        </section>

        {{-- =================================================
             INFORMACIÓN INSTITUCIONAL
        ================================================== --}}
        <section class="institution-panel">
            <div class="institution-panel-icon">
                <i class="fas fa-circle-info"></i>
            </div>

            <div class="institution-panel-content">
                <span>
                    INFORMACIÓN DEL SISTEMA
                </span>
                <h3>
                    Sistema de Gestión Docente
                </h3>
                <p>
                    Plataforma destinada a facilitar la gestión y consulta
                    de información académica del personal docente de la
                    Facultad de Ingeniería Mecánica.
                </p>
            </div>

            <div class="institution-panel-status">
                <span class="online-dot"></span>
                Sistema disponible
            </div>
        </section>

        {{-- =================================================
             FOOTER
        ================================================== --}}
        <footer class="dashboard-footer">
            <div>
                <strong>
                    UNIVERSIDAD NACIONAL DE INGENIERÍA
                </strong>
                <span>
                    Facultad de Ingeniería Mecánica
                </span>
            </div>

            <span class="footer-copy">
                Portal Docente · FIM UNI
            </span>
        </footer>

    </div>

    {{-- =====================================================
         ESTILOS DASHBOARD
    ====================================================== --}}
    <style>
        .dashboard-page {
            width: 100%;
        }

        .dashboard-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 25px;
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 10px;
            color: #98A2AD;
            font-size: 9px;
            font-weight: 500;
        }

        .breadcrumb-custom i {
            font-size: 7px;
        }

        .breadcrumb-custom strong {
            color: var(--uni-maroon);
            font-weight: 700;
        }

        .dashboard-header h1 {
            margin: 0;
            color: var(--uni-blue-dark);
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .dashboard-header p {
            margin: 7px 0 0;
            color: var(--text-medium);
            font-size: 10px;
            line-height: 1.6;
        }

        .header-date {
            min-width: 180px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 13px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 9px;
            box-shadow: 0 3px 10px rgba(0,0,0,.025);
        }

        .date-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            background: var(--uni-maroon-light);
            color: var(--uni-maroon);
            font-size: 12px;
        }

        .header-date span {
            display: block;
            color: #9AA4AE;
            font-size: 7px;
            font-weight: 800;
            letter-spacing: .8px;
        }

        .header-date strong {
            display: block;
            color: var(--uni-blue-dark);
            font-size: 9px;
            margin-top: 3px;
        }

        .institution-line {
            height: 3px;
            margin: 20px 0 22px;
            background: linear-gradient(
                to right,
                var(--uni-maroon) 0%,
                var(--uni-maroon) 72%,
                var(--uni-gold) 72%,
                var(--uni-gold) 100%
            );
            border-radius: 10px;
        }

        .welcome-card {
            min-height: 190px;
            position: relative;
            overflow: hidden;
            border-radius: 13px;
            background: linear-gradient(
                110deg,
                #6D0E13 0%,
                #7A1015 55%,
                #681015 100%
            );
            box-shadow: 0 10px 30px rgba(122,16,21,.14);
            color: white;
            padding: 30px 35px;
            margin-bottom: 32px;
        }

        .welcome-content {
            position: relative;
            z-index: 5;
            max-width: 700px;
        }

        .welcome-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--uni-gold);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.2px;
        }

        .welcome-content h2 {
            margin: 11px 0 7px;
            font-size: 23px;
            font-weight: 500;
            letter-spacing: -.3px;
        }

        .welcome-content h2 strong {
            font-weight: 800;
        }

        .welcome-content > p {
            max-width: 570px;
            margin: 0;
            color: rgba(255,255,255,.74);
            font-size: 10px;
            line-height: 1.7;
        }

        .welcome-institution {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.86);
            font-size: 8px;
            font-weight: 600;
        }

        .welcome-institution i {
            color: var(--uni-gold);
        }

        .welcome-decoration {
            position: absolute;
            width: 360px;
            height: 360px;
            right: -65px;
            top: -85px;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.09);
        }

        .circle-one {
            width: 300px;
            height: 300px;
            right: 0;
            top: 0;
        }

        .circle-two {
            width: 220px;
            height: 220px;
            right: 40px;
            top: 40px;
        }

        .decoration-building {
            position: absolute;
            width: 82px;
            height: 82px;
            right: 108px;
            top: 108px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.15);
            background: rgba(255,255,255,.045);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,.6);
            font-size: 28px;
        }

        .modules-heading {
            margin-bottom: 18px;
        }

        .modules-heading span {
            color: var(--uni-maroon);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .modules-heading h2 {
            margin: 5px 0 3px;
            color: var(--uni-blue-dark);
            font-size: 17px;
            font-weight: 800;
        }

        .modules-heading p {
            margin: 0;
            color: var(--text-light);
            font-size: 9px;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 17px;
        }

        .module-card {
            min-height: 245px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .module-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 3px;
            background: var(--uni-maroon);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .25s ease;
        }

        .module-card:hover {
            transform: translateY(-5px);
            border-color: #D9C4C6;
            box-shadow: 0 14px 35px rgba(16,47,77,.09);
        }

        .module-card:hover::before {
            transform: scaleX(1);
        }

        .module-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .module-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--uni-maroon-light);
            color: var(--uni-maroon);
            font-size: 17px;
        }

        .module-number {
            color: #B3BCC4;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .module-content {
            flex: 1;
            padding-top: 24px;
        }

        .module-label {
            color: #9AA4AE;
            font-size: 7px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .module-content h3 {
            margin: 6px 0 8px;
            color: var(--uni-blue-dark);
            font-size: 15px;
            font-weight: 800;
        }

        .module-content p {
            margin: 0;
            color: var(--text-medium);
            font-size: 9px;
            line-height: 1.7;
        }

        .module-footer {
            padding-top: 15px;
            margin-top: 15px;
            border-top: 1px solid #EDF0F2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--uni-maroon);
            font-size: 8px;
            font-weight: 800;
        }

        .module-arrow {
            width: 27px;
            height: 27px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--uni-maroon-light);
            transition: .2s;
        }

        .module-card:hover .module-arrow {
            background: var(--uni-maroon);
            color: white;
            transform: translateX(3px);
        }

        .institution-panel {
            margin-top: 24px;
            min-height: 78px;
            padding: 15px 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .institution-panel-icon {
            width: 39px;
            height: 39px;
            min-width: 39px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--uni-gold-light);
            color: var(--uni-maroon);
            font-size: 14px;
        }

        .institution-panel-content {
            min-width: 0;
        }

        .institution-panel-content span {
            color: #9AA4AE;
            font-size: 7px;
            font-weight: 800;
            letter-spacing: .8px;
        }

        .institution-panel-content h3 {
            margin: 3px 0;
            color: var(--uni-blue-dark);
            font-size: 10px;
            font-weight: 800;
        }

        .institution-panel-content p {
            margin: 0;
            color: var(--text-light);
            font-size: 8px;
            line-height: 1.5;
        }

        .institution-panel-status {
            margin-left: auto;
            white-space: nowrap;
            color: #398050;
            font-size: 8px;
            font-weight: 700;
        }

        .online-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            margin-right: 4px;
            border-radius: 50%;
            background: #398050;
        }

        .dashboard-footer {
            margin-top: 25px;
            padding-top: 17px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-footer strong {
            display: block;
            color: var(--uni-blue-dark);
            font-size: 8px;
            font-weight: 800;
        }

        .dashboard-footer span {
            display: block;
            color: var(--text-light);
            font-size: 7px;
            margin-top: 3px;
        }

        .dashboard-footer .footer-copy {
            color: #9AA4AE;
            font-size: 7px;
        }

        @media (max-width: 1000px) {
            .modules-grid {
                grid-template-columns: 1fr;
            }
            .module-card {
                min-height: 210px;
            }
        }

        @media (max-width: 700px) {
            .dashboard-header {
                display: block;
            }
            .header-date {
                display: none;
            }
            .dashboard-header h1 {
                font-size: 23px;
            }
            .welcome-card {
                padding: 25px 22px;
            }
            .welcome-decoration {
                opacity: .35;
            }
            .institution-panel {
                align-items: flex-start;
            }
            .institution-panel-status {
                display: none;
            }
            .dashboard-footer {
                display: block;
            }
            .footer-copy {
                margin-top: 8px;
            }
        }
    </style>

</x-app-layout>