<x-guest-layout>
    <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 420px; margin: 0 auto; background: white;">
        <div class="text-center mb-3">
            <!-- Escudo local de la UNI -->
            <img src="{{ asset('img/logo-uni.png') }}" 
                 alt="Logo UNI" 
                 style="max-height: 75px;" 
                 class="mx-auto mb-2 d-block">

            <h5 class="fw-bold text-danger mb-0">UNIVERSIDAD NACIONAL DE INGENIERÍA</h5>
            <p class="text-muted small fw-semibold mb-1">Facultad de Ingeniería Mecánica (FIM)</p>
            <hr class="my-2">
            <h6 class="fw-bold text-dark">Portal Académico - Iniciar Sesión</h6>
        </div>

        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Correo -->
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold text-secondary">Correo Institucional</label>
                <input id="email" class="form-control rounded-3" type="email" name="email" :value="old('email')" required autofocus placeholder="codigo@uni.edu.pe" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
            </div>

            <!-- Contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label small fw-bold text-secondary">Contraseña</label>
                <input id="password" class="form-control rounded-3" type="password" name="password" required placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
            </div>

            <!-- Recordar sesión -->
            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label small text-muted">Recordar datos</label>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-danger fw-bold rounded-3">
                    Ingresar al Sistema
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function() {
                alertify.success('Validando credenciales FIM-UNI...');
            });
        });
    </script>
</x-guest-layout>