<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin') — SaaS Tienda Moda</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; background:#0f172a; color:#e2e8f0; display:flex; min-height:100vh; }
        .sb { width:250px; background:#1e293b; padding:24px 0; flex-shrink:0; position:sticky; top:0; height:100vh; }
        .sb .brand { padding:0 24px 24px; border-bottom:1px solid #334155; }
        .sb .brand h2 { font-size:18px; color:#fff; }
        .sb .brand span { font-size:11px; color:#818cf8; font-weight:600; letter-spacing:.5px; }
        .sb nav { padding:18px 12px; }
        .sb a { display:flex; align-items:center; gap:12px; padding:12px 16px; color:#94a3b8;
            text-decoration:none; border-radius:10px; font-size:14px; margin-bottom:4px; transition:.2s; }
        .sb a:hover, .sb a.active { background:#4f46e5; color:#fff; }
        .sb form { padding:0 12px; margin-top:20px; border-top:1px solid #334155; padding-top:20px; }
        .sb button { width:100%; display:flex; align-items:center; gap:12px; padding:12px 16px;
            background:transparent; border:none; color:#f87171; font-family:inherit; font-size:14px;
            cursor:pointer; border-radius:10px; }
        .sb button:hover { background:#7f1d1d; color:#fff; }
        .main { flex:1; min-width:0; max-width:100%; padding:0; }
        .topbar { display:flex; align-items:center; justify-content:space-between;
            background:#1e293b; border-bottom:1px solid #334155; padding:14px 36px; position:sticky; top:0; z-index:10; }
        .topbar .tb-left { font-size:13px; color:#64748b; }
        .topbar .tb-user { display:flex; align-items:center; gap:12px; text-decoration:none; }
        .topbar .tb-avatar { width:38px; height:38px; border-radius:50%;
            background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; display:flex;
            align-items:center; justify-content:center; font-weight:700; font-size:14px; }
        .topbar .tb-meta .nm { font-size:13px; color:#fff; font-weight:600; }
        .topbar .tb-meta .rl { font-size:11px; color:#818cf8; }
        .content-pad { padding:32px 36px; }
        .sa-toggle { display:none; background:#0f172a; border:1px solid #334155; color:#e2e8f0;
            width:40px; height:40px; border-radius:10px; font-size:18px; cursor:pointer;
            align-items:center; justify-content:center; }
        .sa-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:30; }
        .sa-overlay.show { display:block; }
        .topbar .tb-left-wrap { display:flex; align-items:center; gap:14px; }
        @media (max-width:1024px) {
            .sb { position:fixed; top:0; left:0; z-index:40; transform:translateX(-100%);
                transition:transform .3s ease; box-shadow:4px 0 24px rgba(0,0,0,.4); }
            .sb.open { transform:translateX(0); }
            .sa-toggle { display:flex; }
            .topbar { padding:14px 16px; }
            .content-pad { padding:22px 16px; }
        }
        @media (max-width:640px) {
            .topbar .tb-left { display:none; }
            .cards { grid-template-columns:1fr 1fr !important; }
            .content-pad table { display:block; overflow-x:auto; white-space:nowrap; }
            .filters { flex-wrap:wrap; }
        }
        .pagetitle { font-size:24px; font-weight:700; color:#fff; margin-bottom:4px; }
        .pagesub { color:#64748b; font-size:13px; margin-bottom:26px; }
        .alert-ok { background:#064e3b; color:#6ee7b7; padding:12px 18px; border-radius:10px;
            margin-bottom:20px; font-size:14px; }
        .cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:18px; margin-bottom:26px; }
        .card { background:#1e293b; border:1px solid #334155; border-radius:14px; padding:20px 22px;
            position:relative; overflow:hidden; transition:transform .18s, box-shadow .18s; }
        .card:hover { transform:translateY(-3px); box-shadow:0 12px 28px rgba(0,0,0,.35); }
        .card .lbl { font-size:12px; color:rgba(255,255,255,.7); text-transform:uppercase; letter-spacing:.5px; }
        .card .val { font-size:26px; font-weight:700; color:#fff; margin-top:6px; }
        .card .ic { width:40px; height:40px; border-radius:11px; float:right; font-size:17px;
            display:flex; align-items:center; justify-content:center; color:#fff; background:rgba(255,255,255,.16); }
        /* Variantes de color para los paneles */
        .card.c-indigo { background:linear-gradient(135deg,#312e81,#4f46e5); border-color:#6366f1; }
        .card.c-green  { background:linear-gradient(135deg,#064e3b,#059669); border-color:#10b981; }
        .card.c-amber  { background:linear-gradient(135deg,#78350f,#d97706); border-color:#f59e0b; }
        .card.c-red    { background:linear-gradient(135deg,#7f1d1d,#dc2626); border-color:#ef4444; }
        .card.c-violet { background:linear-gradient(135deg,#4c1d95,#7c3aed); border-color:#8b5cf6; }
        .card.c-blue   { background:linear-gradient(135deg,#1e3a8a,#2563eb); border-color:#3b82f6; }
        .card.c-teal   { background:linear-gradient(135deg,#134e4a,#0d9488); border-color:#14b8a6; }
        .card.c-pink   { background:linear-gradient(135deg,#831843,#db2777); border-color:#ec4899; }
        .panel { background:#1e293b; border:1px solid #334155; border-radius:14px; padding:22px; margin-bottom:22px; overflow-x:auto; }
        .panel h3 { font-size:15px; color:#fff; margin-bottom:16px; }
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; font-size:11px; text-transform:uppercase; color:#64748b; padding:10px 12px; border-bottom:1px solid #334155; }
        td { padding:12px; font-size:13px; border-bottom:1px solid #273449; }
        tr:last-child td { border-bottom:none; }
        .badge { font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; }
        .b-activa { background:#064e3b; color:#6ee7b7; }
        .b-prueba { background:#78350f; color:#fcd34d; }
        .b-suspendida { background:#7f1d1d; color:#fca5a5; }
        select, input[type=text] { background:#0f172a; border:1px solid #334155; color:#e2e8f0;
            padding:7px 10px; border-radius:8px; font-family:inherit; font-size:13px; }
        .btn { padding:7px 12px; border:none; border-radius:8px; font-family:inherit; font-size:12px;
            font-weight:600; cursor:pointer; color:#fff; }
        .btn-i { background:#4f46e5; } .btn-g { background:#059669; } .btn-r { background:#dc2626; }
        .row-actions { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
        .filters { display:flex; gap:10px; margin-bottom:18px; }
    </style>
</head>
<body>
    <div class="sa-overlay" id="saOverlay"></div>
    <aside class="sb" id="saSidebar">
        <div class="brand">
            <h2><i class="fa-solid fa-shield-halved"></i> Control SaaS</h2>
            <span>SUPER ADMIN</span>
        </div>
        <nav>
            <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Resumen
            </a>
            <a href="{{ route('admin.tiendas') }}" class="{{ request()->routeIs('admin.tiendas') ? 'active' : '' }}">
                <i class="fa-solid fa-store"></i> Tiendas
            </a>
            <a href="{{ route('admin.planes') }}" class="{{ request()->routeIs('admin.planes') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Planes
            </a>
            <a href="{{ route('admin.perfil') }}" class="{{ request()->routeIs('admin.perfil') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i> Mi Perfil
            </a>
        </nav>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</button>
        </form>
    </aside>
    <main class="main">
        @php $u = auth()->user(); @endphp
        <div class="topbar">
            <div class="tb-left-wrap">
                <button class="sa-toggle" id="saToggle" aria-label="Abrir menú"><i class="fa-solid fa-bars"></i></button>
                <div class="tb-left"><i class="fa-regular fa-calendar"></i> {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}</div>
            </div>
            <a href="{{ route('admin.perfil') }}" class="tb-user">
                <div class="tb-meta" style="text-align:right;">
                    <div class="nm">{{ $u->name }}</div>
                    <div class="rl">Super Administrador</div>
                </div>
                <div class="tb-avatar">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
            </a>
        </div>
        <div class="content-pad">
            @if(session('success'))
                <div class="alert-ok"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-ok" style="background:#7f1d1d;color:#fecaca;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    @foreach($errors->all() as $e) {{ $e }} @endforeach
                </div>
            @endif
            @yield('content')
        </div>
    </main>
    <script>
        (function () {
            const sb = document.getElementById('saSidebar');
            const ov = document.getElementById('saOverlay');
            const tg = document.getElementById('saToggle');
            const cerrar = () => { sb.classList.remove('open'); ov.classList.remove('show'); };
            if (tg) tg.addEventListener('click', () => {
                sb.classList.toggle('open'); ov.classList.toggle('show');
            });
            if (ov) ov.addEventListener('click', cerrar);
            sb.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { if (innerWidth <= 1024) cerrar(); }));
            addEventListener('resize', () => { if (innerWidth > 1024) cerrar(); });
        })();
    </script>
</body>
</html>
