@extends('layouts.app')
@section('title','Facturación Electrónica')
@section('page-title','Configuración de Facturación')

@section('content')
<div class="page-header">
    <div>
        <h1>Facturación Electrónica</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Configuración de Facturación</div>
    </div>
    @php [$etxt, $efg, $ebg, $eic] = $config->estadoConfig(); @endphp
    <span class="badge" style="background:{{ $ebg }};color:{{ $efg }};font-size:13px;">
        <i class="fa-solid {{ $eic }}" style="margin-right:5px;"></i>{{ $etxt }}@if($config->exists) · {{ $config->modoLabel() }}@endif
    </span>
</div>

<div style="max-width:720px;">

    {{-- Banner: Facturación electrónica del Perú (SUNAT) --}}
    <div class="card" style="margin-bottom:20px;padding:0;overflow:hidden;border:none;">
        <div style="display:flex;gap:18px;align-items:center;padding:22px 24px;background:linear-gradient(135deg,#c1121f 0%,#e23744 50%,#7b1fa2 130%);color:#fff;">
            <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span style="font-size:18px;font-weight:700;">Facturación Electrónica del Perú</span>
                    <span style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.35);padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.5px;">PERÚ · SUNAT</span>
                </div>
                <p style="font-size:13px;opacity:.92;margin-top:5px;line-height:1.55;">
                    Este módulo emite comprobantes electrónicos válidos ante la <b>SUNAT</b> (Superintendencia Nacional de Aduanas y de Administración Tributaria). Configura los datos de tu empresa para empezar a facturar.
                </p>
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;padding:14px 24px;background:#fff;border:1px solid var(--border);border-top:none;">
            @foreach(['Factura','Boleta de venta','Nota de crédito','Comunicación de baja'] as $doc)
                <span class="badge badge-pink" style="font-size:12px;"><i class="fa-solid fa-check" style="margin-right:5px;"></i>{{ $doc }}</span>
            @endforeach
        </div>
    </div>

    {{-- Informativo: modos de emisión --}}
    <div class="card" style="margin-bottom:20px;border-left:4px solid var(--accent);">
        <div style="display:flex;gap:10px;align-items:flex-start;">
            <i class="fa-solid fa-circle-info" style="color:var(--accent);font-size:16px;margin-top:2px;"></i>
            <div style="font-size:13px;color:var(--text-dark);line-height:1.85;">
                <b>¿Cómo empezar?</b><br>
                <b>Pruebas (demo):</b> emite al ambiente beta de SUNAT con datos de prueba; ideal para conocer el flujo sin certificado.<br>
                <b>Pruebas (mi certificado):</b> igual que beta, pero validando tu propio certificado antes de pasar a producción.<br>
                <b>Producción:</b> emisión real y válida ante SUNAT. Requiere tu <b>RUC</b>, usuario y <b>clave SOL</b>, y tu <b>certificado digital</b> vigente.
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('facturacion.config.update') }}" enctype="multipart/form-data">
        @csrf

        {{-- Estado / modo --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-toggle-on" style="color:var(--accent);margin-right:6px;"></i>Estado y modo</div></div>

            <label class="remember-me" style="display:flex;align-items:center;gap:10px;font-size:14px;margin-bottom:18px;cursor:pointer;">
                <input type="checkbox" name="activo" value="1" {{ $config->activo ? 'checked' : '' }} style="width:16px;height:16px;accent-color:var(--accent);">
                Facturación electrónica activada para esta tienda
            </label>

            <div class="form-group">
                <label class="form-label">Modo de emisión</label>
                <select name="modo" class="form-control" id="modo">
                    <option value="beta_demo"  {{ $config->modo=='beta_demo'?'selected':'' }}>Pruebas — demo de SUNAT (sin certificado propio)</option>
                    <option value="beta"       {{ $config->modo=='beta'?'selected':'' }}>Pruebas — con mi certificado</option>
                    <option value="produccion" {{ $config->modo=='produccion'?'selected':'' }}>Producción (emisión real)</option>
                </select>
                <small style="color:var(--text-muted);font-size:12px;">En modo <b>demo</b> se emite al ambiente beta con el RUC de prueba 20000000001; no necesitas cargar nada.</small>
            </div>
        </div>

        {{-- Credenciales SOL --}}
        <div class="card" style="margin-bottom:20px;" id="bloqueCredenciales">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-key" style="color:var(--accent);margin-right:6px;"></i>Credenciales SUNAT (SOL)</div>
                <a href="https://www.sunat.gob.pe/sol.html" target="_blank" rel="noopener" style="color:var(--accent);font-weight:600;text-decoration:none;font-size:12px;">
                    Acceder a SUNAT SOL <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                </a>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">RUC del emisor</label>
                    <input type="text" name="ruc" class="form-control" maxlength="11" value="{{ old('ruc', $config->ruc) }}" placeholder="20123456789">
                </div>
                <div class="form-group">
                    <label class="form-label">Usuario SOL</label>
                    <input type="text" name="sol_usuario" class="form-control" value="{{ old('sol_usuario', $config->sol_usuario) }}" placeholder="MODDATOS">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Clave SOL</label>
                <input type="password" name="sol_clave" class="form-control" placeholder="{{ $config->sol_clave ? '•••••••• (guardada, escribe para cambiarla)' : 'Clave del usuario SOL' }}">
                <small style="color:var(--text-muted);font-size:12px;">Se guarda cifrada. Déjala vacía para no cambiarla.</small>
            </div>
        </div>

        {{-- Certificado --}}
        <div class="card" style="margin-bottom:20px;" id="bloqueCertificado">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-certificate" style="color:var(--accent);margin-right:6px;"></i>Certificado digital</div></div>

            @if($config->tieneCertificado())
                <p style="font-size:13px;color:#16a34a;margin-bottom:14px;"><i class="fa-solid fa-circle-check"></i> Hay un certificado cargado. Sube uno nuevo solo si quieres reemplazarlo.</p>
            @endif

            <div class="form-group">
                <label class="form-label">Archivo del certificado (.pfx, .p12 o .pem)</label>
                <input type="file" name="certificado" class="form-control" accept=".pfx,.p12,.pem">
                <small style="color:var(--text-muted);font-size:12px;">Si subes un <b>.pfx/.p12</b>, indica su contraseña abajo. Un <b>.pem</b> debe incluir certificado y llave privada.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Contraseña del certificado (.pfx/.p12)</label>
                <input type="password" name="certificado_password" class="form-control" placeholder="{{ $config->certificado_password ? '•••••••• (guardada)' : 'Solo si el .pfx la requiere' }}">
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" style="margin-right:6px;"></i>Guardar configuración</button>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver a ventas</a>
        </div>
    </form>

    <div class="card" style="margin-top:20px;background:#fdf2f8;border-color:#fbcfe8;">
        <div style="font-size:13px;color:#9d174d;line-height:1.7;">
            <b>¿Cómo obtener tu certificado y clave SOL?</b><br>
            El certificado digital lo emite una entidad autorizada (o puedes usar el certificado tributario de SUNAT). El usuario y clave SOL se generan en SUNAT Operaciones en Línea. Para <b>probar</b> sin nada de esto, deja el modo en <b>demo</b> y emite al ambiente beta.
            <div style="margin-top:12px;">
                <a href="https://www.sunat.gob.pe/sol.html" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Ir a SUNAT Operaciones en Línea (SOL)
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar/ocultar credenciales y certificado según el modo.
    (function () {
        const modo = document.getElementById('modo');
        const cred = document.getElementById('bloqueCredenciales');
        const cert = document.getElementById('bloqueCertificado');
        function toggle() {
            const demo = modo.value === 'beta_demo';
            cred.style.opacity = demo ? '0.55' : '1';
            cert.style.opacity = demo ? '0.55' : '1';
        }
        modo.addEventListener('change', toggle);
        toggle();
    })();
</script>
@endsection
