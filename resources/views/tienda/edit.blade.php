@extends('layouts.app')
@section('title','Mi Tienda')
@section('page-title','Mi Tienda')

@section('content')
<div class="page-header">
    <div>
        <h1>Mi Tienda</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Mi Tienda</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;" class="cat-grid">
    <!-- Suscripción -->
    <div class="card" style="overflow:hidden;padding:0;">
        <div style="background:linear-gradient(135deg,#e8398c,#7b1fa2);padding:26px;text-align:center;color:#fff;">
            <div style="width:70px;height:70px;border-radius:18px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 12px;"><i class="fa-solid fa-store"></i></div>
            <div style="font-size:18px;font-weight:700;">{{ $tienda->nombre }}</div>
            <div style="font-size:12px;opacity:.85;">{{ $tienda->email }}</div>
        </div>
        <div style="padding:20px;">
            @php $b = $tienda->estadoBadge(); $dr = $tienda->diasRestantes(); @endphp
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:13px;">
                <span style="color:var(--text-muted);">Plan</span>
                <span style="font-weight:700;color:var(--accent);">{{ $tienda->plan->nombre ?? 'Sin plan' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:13px;">
                <span style="color:var(--text-muted);">Estado</span>
                <span class="badge" style="background:{{ $b[2] }};color:{{ $b[1] }};">{{ $b[0] }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:13px;">
                <span style="color:var(--text-muted);">Vence</span>
                <span style="font-weight:600;">{{ $tienda->fecha_vencimiento ? $tienda->fecha_vencimiento->format('d/m/Y') : '—' }}</span>
            </div>
            @if($dr !== null)
            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;">
                <span style="color:var(--text-muted);">Días restantes</span>
                <span style="font-weight:700;color:{{ $dr <= 5 ? '#dc2626' : '#16a34a' }};">{{ $dr }} días</span>
            </div>
            @endif
            @if($tienda->plan)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);line-height:1.9;">
                Productos: <b>{{ $tienda->plan->max_productos == -1 ? 'Ilimitados' : $tienda->plan->max_productos }}</b><br>
                Usuarios: <b>{{ $tienda->plan->max_usuarios == -1 ? 'Ilimitados' : $tienda->plan->max_usuarios }}</b>
            </div>
            @endif
        </div>
    </div>

    <!-- Datos de la tienda -->
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa-solid fa-id-card" style="color:var(--accent);margin-right:6px;"></i>Datos de la tienda</div></div>
        <form method="POST" action="{{ route('tienda.update') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre de la tienda</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $tienda->nombre) }}" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">RUC</label>
                    <input type="text" name="ruc" class="form-control" value="{{ old('ruc', $tienda->ruc) }}" placeholder="Opcional">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $tienda->telefono) }}" placeholder="Opcional">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Correo de contacto</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $tienda->email) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $tienda->direccion) }}" placeholder="Opcional">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
        </form>
    </div>
</div>
<style>@media(max-width:768px){.cat-grid{grid-template-columns:1fr !important;}}</style>
@endsection
