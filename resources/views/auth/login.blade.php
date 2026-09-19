<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — SaaS Tienda Moda</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #e8398c;
            --accent-dark: #c2185b;
            --purple: #7b1fa2;
            --dark: #1e2139;
            --muted: #8a8ea8;
            --border: #e4e6f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            color: var(--dark);
            background: #fff;
        }

        /* ─── PANEL IZQUIERDO / MARCA ─── */
        .brand-panel {
            width: 50%;
            background: linear-gradient(160deg, #e8398c 0%, #c2185b 45%, #7b1fa2 100%);
            color: #fff;
            padding: 56px 64px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .brand-panel::before {
            content: '';
            position: absolute;
            width: 420px; height: 420px;
            border: 70px solid rgba(255,255,255,0.06);
            border-radius: 50%;
            top: -150px; right: -140px;
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            width: 260px; height: 260px;
            border: 46px solid rgba(255,255,255,0.05);
            border-radius: 50%;
            bottom: -90px; left: -70px;
        }
        .brand-inner { position: relative; z-index: 1; max-width: 460px; }
        .brand-logo {
            width: 78px; height: 78px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 34px;
            margin-bottom: 22px;
        }
        .brand-title { font-size: 40px; font-weight: 800; line-height: 1.1; letter-spacing: -0.5px; }
        .brand-sub {
            font-size: 12px; font-weight: 600; letter-spacing: 3px;
            text-transform: uppercase; opacity: 0.85; margin-top: 8px;
        }
        .brand-badge {
            display: inline-flex; align-items: center; gap: 8px;
            margin-top: 22px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.22);
            padding: 9px 18px; border-radius: 30px;
            font-size: 13px; font-weight: 500;
        }

        .features { margin-top: 40px; display: flex; flex-direction: column; gap: 22px; }
        .feature { display: flex; align-items: flex-start; gap: 16px; }
        .feature-icon {
            width: 44px; height: 44px; flex-shrink: 0;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
        }
        .feature-text .ft-title { font-size: 15px; font-weight: 600; }
        .feature-text .ft-desc  { font-size: 13px; opacity: 0.82; margin-top: 2px; line-height: 1.5; }

        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 44px; }
        .stat {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 14px;
            padding: 16px 12px; text-align: center;
        }
        .stat .stat-num { font-size: 22px; font-weight: 800; }
        .stat .stat-lbl { font-size: 11px; opacity: 0.82; margin-top: 3px; }

        /* ─── PANEL DERECHO / FORMULARIO ─── */
        .form-panel {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #fff;
        }
        .form-inner { width: 100%; max-width: 400px; }
        .form-title { font-size: 28px; font-weight: 700; color: var(--dark); }
        .form-lead { color: var(--muted); font-size: 14px; margin-top: 6px; }

        .form-group { margin-top: 22px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            color: #374151; margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
            color: var(--muted); font-size: 15px; transition: color 0.2s;
        }
        .input-wrap input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
            background: #fafbff;
            color: var(--dark);
        }
        .input-wrap input:focus {
            outline: none;
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(232,57,140,0.1);
        }
        .input-wrap input:focus + i { color: var(--accent); }

        .form-options {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 16px;
        }
        .remember-me {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #555; cursor: pointer;
        }
        .remember-me input { accent-color: var(--accent); width: 15px; height: 15px; }
        .link-accent { color: var(--accent); font-weight: 600; text-decoration: none; font-size: 13px; }
        .link-accent:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            margin-top: 24px;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 600; font-family: 'Poppins', sans-serif;
            cursor: pointer; transition: all 0.25s;
            display: flex; align-items: center; justify-content: center; gap: 9px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(232,57,140,0.4); }
        .btn-login:active { transform: translateY(0); }

        .alert {
            padding: 12px 16px; border-radius: 8px; font-size: 13px;
            margin-bottom: 6px; margin-top: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-error { background: #fff5f7; border: 1px solid #f8bbd0; border-left: 4px solid var(--accent); color: #c62828; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; border-left: 4px solid #16a34a; color: #166534; }

        /* Cuentas demo */
        .demo-divider {
            text-align: center; margin: 26px 0 14px;
            font-size: 12px; font-weight: 600; letter-spacing: 1px;
            text-transform: uppercase; color: var(--muted);
            position: relative;
        }
        .demo-divider::before, .demo-divider::after {
            content: ''; position: absolute; top: 50%; width: 26%; height: 1px; background: var(--border);
        }
        .demo-divider::before { left: 0; }
        .demo-divider::after { right: 0; }
        .demo-box { border: 1.5px solid var(--border); border-radius: 12px; overflow: hidden; }
        .demo-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px; cursor: pointer; transition: background 0.15s;
            font-size: 13.5px; color: var(--dark); background: #fff;
            border: none; width: 100%; font-family: 'Poppins', sans-serif; text-align: left;
        }
        .demo-row + .demo-row { border-top: 1px solid var(--border); }
        .demo-row:hover { background: #fdf2f8; }
        .demo-role {
            font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;
            background: #fce7f3; color: #9d174d;
        }
        .demo-role.super { background: linear-gradient(135deg, var(--accent), var(--purple)); color: #fff; }
        .demo-note { text-align: center; font-size: 12px; color: var(--muted); margin-top: 12px; }
        .demo-note b { color: var(--accent); }

        .form-foot { margin-top: 22px; text-align: center; font-size: 12px; color: #b6b9c9; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 980px) {
            .brand-panel { padding: 48px; }
            .brand-title { font-size: 32px; }
            .form-panel { padding: 40px 32px; }
        }
        @media (max-width: 820px) {
            body { flex-direction: column; }
            .brand-panel, .form-panel { width: 100%; }
            .brand-panel { padding: 40px 32px; }
            .stats { margin-top: 30px; }
            .form-panel { padding: 40px 24px; }
        }
        @media (max-width: 520px) {
            .brand-title { font-size: 28px; }
            .features { gap: 16px; margin-top: 28px; }
            .brand-panel::before, .brand-panel::after { display: none; }
        }
    </style>
</head>
<body>

    <!-- ─── PANEL DE MARCA ─── -->
    <aside class="brand-panel">
        <div class="brand-inner">
            <div class="brand-logo"><i class="fa-solid fa-shirt"></i></div>
            <div class="brand-title">Tienda Moda</div>
            <div class="brand-sub">Sistema de Gestión de Moda</div>

            <div class="brand-badge">
                <i class="fa-solid fa-cloud"></i> Aplicación SaaS · Multi-tienda
            </div>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    <div class="feature-text">
                        <div class="ft-title">Punto de Venta Ágil</div>
                        <div class="ft-desc">Cobra rápido con descuento de stock e IGV automático</div>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <div class="feature-text">
                        <div class="ft-title">Inventario y Variantes</div>
                        <div class="ft-desc">Controla stock por talla y color, con alertas y compras</div>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="feature-text">
                        <div class="ft-title">Reportes en Tiempo Real</div>
                        <div class="ft-desc">Métricas, gráficos y exportación a CSV</div>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="feature-text">
                        <div class="ft-title">Clientes y Roles</div>
                        <div class="ft-desc">Fidelización por puntos y accesos por rol de equipo</div>
                    </div>
                </div>
            </div>

            <div class="stats">
                <div class="stat"><div class="stat-num">IGV</div><div class="stat-lbl">Incluido</div></div>
                <div class="stat"><div class="stat-num">Damas</div><div class="stat-lbl">Caballeros · Niños</div></div>
                <div class="stat"><div class="stat-num">24/7</div><div class="stat-lbl">Disponible</div></div>
            </div>
        </div>
    </aside>

    <!-- ─── FORMULARIO ─── -->
    <main class="form-panel">
        <div class="form-inner">
            <div class="form-title">Bienvenido de vuelta <span style="font-weight:400;">👋</span></div>
            <p class="form-lead">Ingresa tus credenciales para acceder al sistema</p>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>{{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrap">
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="ejemplo@tiendamoda.com" autocomplete="email" required>
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    @error('email') <small style="color:var(--accent);font-size:12px;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <input type="password" id="password" name="password"
                               placeholder="••••••••" autocomplete="current-password" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    @error('password') <small style="color:var(--accent);font-size:12px;">{{ $message }}</small> @enderror
                </div>

                <div class="form-options">
                    <label class="remember-me"><input type="checkbox" name="remember"> Recordarme</label>
                    <a href="{{ route('password.request') }}" class="link-accent">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
                </button>
            </form>

            <div style="text-align:center;margin-top:18px;font-size:13.5px;color:#555;">
                ¿No tienes una tienda?
                <a href="{{ route('registro') }}" class="link-accent">Créala gratis →</a>
            </div>

            <div class="demo-divider">Cuentas de demostración</div>
            <div class="demo-box">
                <button type="button" class="demo-row" onclick="fillLogin('admin@tiendamoda.com','admin123')">
                    <span>admin@tiendamoda.com</span>
                    <span class="demo-role">Admin</span>
                </button>
                <button type="button" class="demo-row" onclick="fillLogin('superadmin@tiendamoda.com','super123')">
                    <span>superadmin@tiendamoda.com</span>
                    <span class="demo-role super">Super Admin</span>
                </button>
            </div>
            <p class="demo-note">Haz clic en una cuenta para autocompletar · Admin: <b>admin123</b> · Super: <b>super123</b></p>

            <div class="form-foot">© {{ date('Y') }} SaaS Tienda Moda · Todos los derechos reservados</div>
        </div>
    </main>

    <script>
        function fillLogin(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
            document.getElementById('password').focus();
        }
    </script>
</body>
</html>
