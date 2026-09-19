@extends('superadmin.layout')
@section('title', 'Resumen')

@section('content')
<div class="pagetitle">Panel de Control SaaS</div>
<div class="pagesub">Visión global de todas las tiendas de la plataforma</div>

<div class="cards">
    <div class="card c-indigo"><i class="fa-solid fa-store ic"></i><div class="lbl">Tiendas totales</div><div class="val">{{ $totalTiendas }}</div></div>
    <div class="card c-green"><i class="fa-solid fa-circle-check ic"></i><div class="lbl">Activas</div><div class="val">{{ $activas }}</div></div>
    <div class="card c-amber"><i class="fa-solid fa-hourglass-half ic"></i><div class="lbl">En prueba</div><div class="val">{{ $prueba }}</div></div>
    <div class="card c-red"><i class="fa-solid fa-ban ic"></i><div class="lbl">Suspendidas</div><div class="val">{{ $suspendidas }}</div></div>
    <div class="card c-violet"><i class="fa-solid fa-sack-dollar ic"></i><div class="lbl">Ingreso mensual est.</div><div class="val">S/ {{ number_format($mrr, 0) }}</div></div>
    <div class="card c-blue"><i class="fa-solid fa-users ic"></i><div class="lbl">Usuarios</div><div class="val">{{ $totalUsuarios }}</div></div>
    <div class="card c-teal"><i class="fa-solid fa-box ic"></i><div class="lbl">Productos (global)</div><div class="val">{{ number_format($totalProductos) }}</div></div>
    <div class="card c-pink"><i class="fa-solid fa-chart-line ic"></i><div class="lbl">Ventas (global)</div><div class="val">S/ {{ number_format($ventasGlobal, 0) }}</div></div>
</div>

<div class="panel">
    <h3>Tiendas por plan</h3>
    <table>
        <thead><tr><th>Plan</th><th>Tiendas</th></tr></thead>
        <tbody>
            @forelse($tiendasPorPlan as $tp)
                <tr><td>{{ $tp->nombre }}</td><td>{{ $tp->total }}</td></tr>
            @empty
                <tr><td colspan="2" style="color:#64748b;">Sin datos</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="panel">
    <h3>Últimas tiendas registradas</h3>
    <table>
        <thead><tr><th>Tienda</th><th>Plan</th><th>Estado</th><th>Registrada</th></tr></thead>
        <tbody>
            @forelse($ultimasTiendas as $t)
                @php $b = $t->estadoBadge(); @endphp
                <tr>
                    <td style="color:#fff;font-weight:500;">{{ $t->nombre }}</td>
                    <td>{{ $t->plan->nombre ?? '—' }}</td>
                    <td><span class="badge b-{{ $t->estado }}">{{ $b[0] }}</span></td>
                    <td style="color:#94a3b8;">{{ $t->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="color:#64748b;">Aún no hay tiendas registradas</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
