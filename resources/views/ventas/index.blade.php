@extends('layouts.app')
@section('title','Ventas')
@section('page-title','Historial de Ventas')

@section('content')
<div class="page-header">
    <div>
        <h1>Historial de Ventas</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Ventas</div>
    </div>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nueva Venta</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <form method="GET" class="form-row" style="align-items:flex-end;">
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-control">
                <option value="">Todos</option>
                <option value="completada" {{ request('estado')=='completada'?'selected':'' }}>Completada</option>
                <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                <option value="anulada" {{ request('estado')=='anulada'?'selected':'' }}>Anulada</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Filtrar</button>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary" style="margin-left:6px;">Limpiar</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>N° Venta</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Comprobante</th><th>Total</th><th>Pago</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($ventas as $v)
                <tr>
                    <td><strong style="color:var(--accent);">{{ $v->numero_venta }}</strong></td>
                    <td>{{ $v->fecha->format('d/m/Y') }}</td>
                    <td>{{ $v->cliente ? $v->cliente->nombre.' '.$v->cliente->apellido : '<em style="color:#aaa;">Cliente directo</em>' }}</td>
                    <td>{{ $v->vendedor->name ?? '—' }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($v->tipo_comprobante) }}</span></td>
                    <td><strong>S/ {{ number_format($v->total,2) }}</strong></td>
                    <td>{{ ucfirst($v->metodo_pago) }}</td>
                    <td>
                        <span class="badge {{ $v->estado=='completada'?'badge-success':($v->estado=='anulada'?'badge-danger':'badge-warning') }}">
                            {{ ucfirst($v->estado) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('ventas.show',$v) }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i></a>
                            @if($v->estado !== 'anulada')
                            <form method="POST" action="{{ route('ventas.anular',$v) }}" onsubmit="return confirm('¿Anular esta venta?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" title="Anular"><i class="fa-solid fa-ban"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted);">No hay ventas registradas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $ventas->withQueryString()->links() }}
</div>
@endsection
