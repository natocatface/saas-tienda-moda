@extends('layouts.app')
@section('title','Mi Suscripción')
@section('page-title','Mi Suscripción')

@push('styles')
<style>
.plan-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:18px; }
.plan-card { background:#fff; border:1.5px solid var(--border); border-radius:14px; padding:22px; display:flex; flex-direction:column; }
.plan-card.actual { border-color:var(--accent); box-shadow:0 6px 22px rgba(232,57,140,.15); }
.plan-card h3 { font-size:18px; font-weight:700; color:var(--text-dark); }
.plan-price { font-size:30px; font-weight:800; color:var(--accent); margin:6px 0 2px; }
.plan-price small { font-size:13px; color:var(--text-muted); font-weight:500; }
.plan-feats { list-style:none; padding:0; margin:14px 0; font-size:13px; color:#374151; }
.plan-feats li { padding:5px 0; display:flex; gap:8px; align-items:flex-start; }
.plan-feats li i { color:#16a34a; margin-top:3px; }
.cur-badge { display:inline-block; font-size:11px; font-weight:700; background:linear-gradient(135deg,#e8398c,#7b1fa2); color:#fff; padding:3px 10px; border-radius:10px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Mi Suscripción</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Suscripción</div>
    </div>
</div>

@php $b = $tienda->estadoBadge(); $dr = $tienda->diasRestantes(); @endphp
<div class="card" style="margin-bottom:22px;display:flex;flex-wrap:wrap;gap:24px;align-items:center;justify-content:space-between;">
    <div>
        <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Plan actual</div>
        <div style="font-size:24px;font-weight:800;color:var(--accent);">{{ $tienda->plan->nombre ?? 'Sin plan' }}</div>
        <div style="margin-top:6px;">
            <span class="badge" style="background:{{ $b[2] }};color:{{ $b[1] }};">{{ $b[0] }}</span>
            @if($dr !== null)<span style="font-size:13px;color:var(--text-muted);margin-left:8px;">{{ $dr }} día(s) restantes · vence {{ $tienda->fecha_vencimiento?->format('d/m/Y') }}</span>@endif
        </div>
    </div>
    <div style="font-size:13px;color:var(--text-muted);max-width:340px;">
        @if($tienda->estado === 'prueba')
            Estás en período de prueba. Suscríbete a un plan para asegurar la continuidad del servicio.
        @elseif($dr !== null && $dr <= 7)
            Tu suscripción vence pronto. Renueva para no perder el acceso.
        @else
            Tu suscripción está activa. Puedes cambiar de plan o renovar cuando quieras.
        @endif
    </div>
</div>

<h2 style="font-size:16px;margin-bottom:14px;">Planes disponibles</h2>
<div class="plan-grid">
    @foreach($planes as $plan)
    @php $esActual = $tienda->plan_id === $plan->id; @endphp
    <div class="plan-card {{ $esActual ? 'actual' : '' }}">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <h3>{{ $plan->nombre }}</h3>
            @if($esActual)<span class="cur-badge">Actual</span>@endif
        </div>
        <div class="plan-price">S/ {{ number_format($plan->precio,0) }}<small>/mes</small></div>
        @if($plan->descripcion)<div style="font-size:12px;color:var(--text-muted);">{{ $plan->descripcion }}</div>@endif
        <ul class="plan-feats">
            <li><i class="fa-solid fa-check"></i> {{ $plan->max_productos == -1 ? 'Productos ilimitados' : $plan->max_productos.' productos' }}</li>
            <li><i class="fa-solid fa-check"></i> {{ $plan->max_usuarios == -1 ? 'Usuarios ilimitados' : $plan->max_usuarios.' usuarios' }}</li>
            <li><i class="fa-solid fa-check"></i> {{ $plan->max_ventas_mes == -1 ? 'Ventas ilimitadas' : $plan->max_ventas_mes.' ventas/mes' }}</li>
            @foreach((array) $plan->caracteristicas as $c)
                <li><i class="fa-solid fa-check"></i> {{ $c }}</li>
            @endforeach
        </ul>
        <a href="{{ route('suscripcion.checkout', $plan) }}" class="btn {{ $esActual ? 'btn-outline' : 'btn-primary' }}" style="margin-top:auto;text-align:center;">
            <i class="fa-solid fa-credit-card"></i> {{ $esActual ? 'Renovar' : 'Suscribirme' }}
        </a>
    </div>
    @endforeach
</div>

<div class="card" style="margin-top:24px;">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-receipt" style="color:var(--accent);margin-right:6px;"></i>Historial de pagos</div></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Recibo</th><th>Fecha</th><th>Plan</th><th>Período</th><th>Método</th><th>Monto</th><th></th></tr></thead>
            <tbody>
                @forelse($pagos as $p)
                <tr>
                    <td style="font-weight:600;">{{ $p->numero }}</td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                    <td>{{ $p->plan->nombre ?? '—' }}</td>
                    <td>{{ $p->periodo_meses }} mes(es)</td>
                    <td>{{ ucfirst($p->metodo) }}</td>
                    <td><strong>S/ {{ number_format($p->monto,2) }}</strong></td>
                    <td><a href="{{ route('suscripcion.recibo', $p) }}" target="_blank" class="btn btn-sm btn-outline"><i class="fa-solid fa-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:36px;color:var(--text-muted);">Aún no hay pagos registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
