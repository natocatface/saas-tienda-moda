@extends('superadmin.layout')
@section('title', 'Tiendas')

@section('content')
<div class="pagetitle">Tiendas</div>
<div class="pagesub">Gestiona planes, estado y suscripción de cada tienda</div>

<form method="GET" action="{{ route('admin.tiendas') }}" class="filters">
    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar tienda...">
    <select name="estado">
        <option value="">Todos los estados</option>
        <option value="activa" {{ request('estado')=='activa'?'selected':'' }}>Activas</option>
        <option value="prueba" {{ request('estado')=='prueba'?'selected':'' }}>En prueba</option>
        <option value="suspendida" {{ request('estado')=='suspendida'?'selected':'' }}>Suspendidas</option>
    </select>
    <button type="submit" class="btn btn-i">Filtrar</button>
</form>

<div class="panel">
    <table>
        <thead>
            <tr>
                <th>Tienda</th><th>Plan</th><th>Usuarios</th><th>Estado</th>
                <th>Vence</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tiendas as $t)
                @php $b = $t->estadoBadge(); $dias = $t->diasRestantes(); @endphp
                <tr>
                    <td style="color:#fff;font-weight:500;">
                        {{ $t->nombre }}<br>
                        <span style="color:#64748b;font-size:11px;">{{ $t->email }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.tiendas.plan', $t) }}" style="display:flex;gap:6px;">
                            @csrf
                            <select name="plan_id" onchange="this.form.submit()">
                                @foreach($planes as $p)
                                    <option value="{{ $p->id }}" {{ $t->plan_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>{{ $t->usuarios_count }}</td>
                    <td><span class="badge b-{{ $t->estado }}">{{ $b[0] }}</span></td>
                    <td style="color:#94a3b8;">
                        {{ $t->fecha_vencimiento ? $t->fecha_vencimiento->format('d/m/Y') : '—' }}
                        @if($dias !== null)<br><span style="font-size:11px;color:{{ $dias <= 3 ? '#f87171' : '#64748b' }};">{{ $dias }} días</span>@endif
                    </td>
                    <td>
                        <div class="row-actions">
                            @if($t->estado !== 'suspendida')
                                <form method="POST" action="{{ route('admin.tiendas.estado', $t) }}">
                                    @csrf <input type="hidden" name="estado" value="suspendida">
                                    <button class="btn btn-r" title="Suspender"><i class="fa-solid fa-ban"></i> Suspender</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.tiendas.estado', $t) }}">
                                    @csrf <input type="hidden" name="estado" value="activa">
                                    <button class="btn btn-g" title="Activar"><i class="fa-solid fa-check"></i> Activar</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.tiendas.extender', $t) }}">
                                @csrf
                                <button class="btn btn-i" title="Extender 30 días"><i class="fa-solid fa-calendar-plus"></i> +30d</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="color:#64748b;">No hay tiendas que coincidan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
